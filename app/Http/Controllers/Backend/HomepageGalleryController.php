<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomepageGalleryController extends Controller
{
    private const SETTINGS_ROLE = 'gallery_settings';
    private const ITEM_ROLE = 'gallery_item';
    private const UPLOAD_DIRECTORY = 'media/homepage/gallery';

    public function index()
    {
        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $items = About::query()->where('homepage_role', self::ITEM_ROLE)
            ->orderBy('homepage_sort_order')->orderBy('id')->get();

        return view('backend.homepage.gallery', compact('settings', 'items'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:800'],
            'homepage_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'homepage_accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'status' => ['required', Rule::in(['0', '1'])],
        ]);

        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $settings->fill($data);
        $settings->homepage_role = self::SETTINGS_ROLE;
        $settings->save();

        return back()->with('gallery_success', 'Gallery section settings saved.');
    }

    public function store(Request $request)
    {
        $data = $this->validateItem($request, true);
        $imageName = $this->uploadImage($request->file('image'));

        try {
            About::query()->create([
                'homepage_role' => self::ITEM_ROLE,
                'title' => $data['title'] ?? null,
                'description' => null,
                'image' => $imageName,
                'homepage_image_alt' => $data['homepage_image_alt'],
                'homepage_gallery_size_variant' => $data['homepage_gallery_size_variant'],
                'homepage_gallery_taken_on' => $data['homepage_gallery_taken_on'] ?? null,
                'homepage_gallery_permission_confirmed' => true,
                'homepage_sort_order' => (int)$data['homepage_sort_order'],
                'status' => $data['status'],
            ]);
        } catch (\Throwable $exception) {
            $this->deleteManagedImage($imageName);
            throw $exception;
        }

        return back()->with('gallery_success', 'Gallery photo uploaded.');
    }

    public function update(Request $request, About $item)
    {
        $this->ensureGalleryItem($item);
        $data = $this->validateItem($request, false);
        $oldImage = $item->image;
        $newImage = $request->hasFile('image') ? $this->uploadImage($request->file('image')) : null;

        try {
            $item->fill([
                'title' => $data['title'] ?? null,
                'homepage_image_alt' => $data['homepage_image_alt'],
                'homepage_gallery_size_variant' => $data['homepage_gallery_size_variant'],
                'homepage_gallery_taken_on' => $data['homepage_gallery_taken_on'] ?? null,
                'homepage_gallery_permission_confirmed' => $request->boolean('homepage_gallery_permission_confirmed'),
                'homepage_sort_order' => (int)$data['homepage_sort_order'],
                'status' => $data['status'],
            ]);
            if ($newImage !== null) {
                $item->image = $newImage;
            }
            $item->save();
        } catch (\Throwable $exception) {
            $this->deleteManagedImage($newImage);
            throw $exception;
        }

        if ($newImage !== null) {
            $this->deleteManagedImage($oldImage);
        }
        return back()->with('gallery_success', 'Gallery photo updated.');
    }

    public function destroy(About $item)
    {
        $this->ensureGalleryItem($item);
        $oldImage = $item->image;
        $item->delete();
        $this->deleteManagedImage($oldImage);

        return back()->with('gallery_success', 'Gallery photo deleted.');
    }

    private function validateItem(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:180'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
            'homepage_image_alt' => ['required', 'string', 'max:200'],
            'homepage_gallery_size_variant' => ['required', Rule::in(['regular', 'wide', 'tall'])],
            'homepage_gallery_taken_on' => ['nullable', 'date', 'before_or_equal:today'],
            'homepage_sort_order' => ['required', 'integer', 'between:0,100000'],
            'status' => ['required', Rule::in(['0', '1'])],
            'homepage_gallery_permission_confirmed' => [$creating ? 'accepted' : 'sometimes', 'boolean'],
        ]);
    }

    private function ensureGalleryItem(About $item): void
    {
        abort_unless($item->homepage_role === self::ITEM_ROLE, 404);
    }

    private function uploadImage(UploadedFile $image): string
    {
        // The Hostinger public_html directory contains /media; local Windows uses public/media junction.
        $directory = base_path(self::UPLOAD_DIRECTORY);
        File::ensureDirectoryExists($directory);
        $filename = 'sda-gallery-' . Str::uuid() . '.' . $image->extension();
        $image->move($directory, $filename);
        return $filename;
    }

    private function deleteManagedImage(?string $filename): void
    {
        // Never unlink any original About/Client/Benefits files.
        if ($filename && preg_match('/^sda-gallery-[0-9a-f-]+\.(png|jpe?g|webp)$/iD', $filename)) {
            File::delete(base_path(self::UPLOAD_DIRECTORY . '/' . $filename));
        }
    }
}
