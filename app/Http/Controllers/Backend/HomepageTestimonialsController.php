<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\ClientReview;
use App\Support\TestimonialVideoUrl;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class HomepageTestimonialsController extends Controller
{
    private const SETTINGS_ROLE = 'testimonials_settings';

    public function index()
    {
        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $reviews = ClientReview::query()->orderBy('homepage_sort_order')->orderBy('id')->get();

        return view('backend.homepage.testimonials', compact('settings', 'reviews'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:800'],
            'homepage_bg_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'homepage_accent_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'status' => ['required', Rule::in(['0', '1'])],
        ]);

        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $settings->homepage_role = self::SETTINGS_ROLE;
        $settings->title = $data['title'];
        $settings->description = $data['description'] ?? null;
        $settings->homepage_bg_color = $data['homepage_bg_color'];
        $settings->homepage_accent_color = $data['homepage_accent_color'];
        $settings->status = $data['status'];
        $settings->save();

        return back()->with('testimonials_success', 'Video testimonials settings saved.');
    }

    public function store(Request $request)
    {
        $data = $this->validatedReview($request, true);
        $imageName = $this->saveThumbnail($request->file('image'));

        try {
            $review = new ClientReview();
            $this->applyReviewFields($review, $data, $request);
            $review->image = $imageName;
            $review->save();
        } catch (\Throwable $error) {
            $this->deleteOwnedThumbnail($imageName);
            throw $error;
        }

        return back()->with('testimonials_success', 'Video testimonial created.');
    }

    public function update(Request $request, ClientReview $review)
    {
        $data = $this->validatedReview($request, false);
        $newImage = $request->hasFile('image') ? $this->saveThumbnail($request->file('image')) : null;
        $oldImage = $review->image;

        try {
            $this->applyReviewFields($review, $data, $request);
            if ($newImage !== null) {
                $review->image = $newImage;
            }
            $review->save();
        } catch (\Throwable $error) {
            $this->deleteOwnedThumbnail($newImage);
            throw $error;
        }

        if ($newImage !== null) {
            $this->deleteOwnedThumbnail($oldImage);
        }

        return back()->with('testimonials_success', 'Video testimonial updated.');
    }

    public function destroy(ClientReview $review)
    {
        $image = $review->image;
        $review->delete();
        $this->deleteOwnedThumbnail($image);

        return back()->with('testimonials_success', 'Video testimonial deleted.');
    }

    private function validatedReview(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'user_name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1500'],
            'video_url' => ['required', 'url', 'max:2048'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'homepage_sort_order' => ['required', 'integer', 'between:0,100000'],
            'status' => ['required', Rule::in(['0', '1'])],
            'homepage_permission_confirmed' => [$creating ? 'accepted' : 'sometimes', 'boolean'],
        ]);
        if (TestimonialVideoUrl::embed($data['video_url']) === null) {
            throw ValidationException::withMessages([
                'video_url' => 'Use a valid HTTPS YouTube or Vimeo video URL (not a playlist or arbitrary embed).',
            ]);
        }
        return $data;
    }

    private function applyReviewFields(ClientReview $review, array $data, Request $request): void
    {
        $review->user_name = $data['user_name'];
        $review->description = $data['description'] ?? null;
        $review->video_url = $data['video_url'];
        $review->homepage_sort_order = (int)$data['homepage_sort_order'];
        $review->homepage_permission_confirmed = $request->boolean('homepage_permission_confirmed');
        $review->status = $data['status'];
        // Preserve legacy slug, views, SEO and other unrelated fields on update.
    }

    private function saveThumbnail(UploadedFile $image): string
    {
        $directory = base_path('media/homepage/testimonials');
        File::ensureDirectoryExists($directory);
        $filename = 'sda-testimonial-' . Str::uuid() . '.' . $image->extension();
        $image->move($directory, $filename);
        return $filename;
    }

    private function deleteOwnedThumbnail(?string $image): void
    {
        if ($image && preg_match('/^sda-testimonial-[0-9a-f-]+\.(?:jpg|jpeg|png|webp)$/iD', $image)) {
            File::delete(base_path('media/homepage/testimonials/' . $image));
        }
    }
}
