<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomepageBenefitsController extends Controller
{
    private const ROLE_SETTINGS = 'benefits_settings';
    private const ROLE_BENEFIT = 'benefit';

    public function index()
    {
        $settings = About::query()->where('homepage_role', self::ROLE_SETTINGS)->first() ?? new About();
        $benefits = About::query()->where('homepage_role', self::ROLE_BENEFIT)
            ->orderBy('homepage_sort_order')->orderBy('id')->get();
        $originalAbout = About::query()->whereNull('homepage_role')->orderBy('id')->first();

        return view('backend.homepage.benefits', compact('settings', 'benefits', 'originalAbout'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'homepage_bg_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'homepage_accent_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'homepage_image_alt' => ['nullable', 'string', 'max:200'],
            'status' => ['required', 'in:0,1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:6144'],
            'remove_image' => ['sometimes', 'boolean'],
        ]);

        $settings = About::query()->where('homepage_role', self::ROLE_SETTINGS)->first() ?? new About();
        $settings->homepage_role = self::ROLE_SETTINGS;
        $settings->title = $data['title'];
        $settings->description = $data['description'] ?? null;
        $settings->status = $data['status'];
        $settings->homepage_bg_color = $data['homepage_bg_color'];
        $settings->homepage_accent_color = $data['homepage_accent_color'];
        $settings->homepage_image_alt = $data['homepage_image_alt'] ?? null;

        if ($request->boolean('remove_image')) {
            $this->removeManagedImage($settings->image);
            $settings->image = null;
        }

        if ($request->hasFile('image')) {
            $newFilename = $this->uploadImage($request->file('image'));
            $this->removeManagedImage($settings->image);
            $settings->image = $newFilename;
        }
        $settings->save();

        return back()->with('benefits_success', 'Benefits section settings saved.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'max:1000'],
            'homepage_sort_order' => ['required', 'integer', 'between:0,100000'],
            'status' => ['required', 'in:0,1'],
        ]);

        About::query()->create([
            'homepage_role' => self::ROLE_BENEFIT,
            'title' => $data['title'],
            'description' => $data['description'],
            'homepage_sort_order' => $data['homepage_sort_order'],
            'status' => $data['status'],
        ]);

        return back()->with('benefits_success', 'New benefit added.');
    }

    public function update(Request $request, About $benefit)
    {
        $this->ensureBenefit($benefit);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'max:1000'],
            'homepage_sort_order' => ['required', 'integer', 'between:0,100000'],
            'status' => ['required', 'in:0,1'],
        ]);
        $benefit->update($data);

        return back()->with('benefits_success', 'Benefit updated.');
    }

    public function destroy(About $benefit)
    {
        $this->ensureBenefit($benefit);
        $benefit->delete();

        return back()->with('benefits_success', 'Benefit deleted.');
    }

    private function ensureBenefit(About $benefit): void
    {
        abort_unless($benefit->homepage_role === self::ROLE_BENEFIT, 404);
    }

    private function uploadImage(\Illuminate\Http\UploadedFile $image): string
    {
        // Project convention: /media/about is the same public URL on Windows and Hostinger.
        $directory = base_path('media/about');
        File::ensureDirectoryExists($directory);
        $filename = 'sda-benefits-' . Str::uuid() . '.' . $image->extension();
        $image->move($directory, $filename);

        return $filename;
    }

    private function removeManagedImage(?string $filename): void
    {
        // Only remove files uploaded by this specific new module, never the About Us photo.
        if ($filename && preg_match('/^sda-benefits-[a-f0-9-]+\.(?:png|jpg|jpeg|webp)$/iD', $filename)) {
            File::delete(base_path('media/about/' . $filename));
        }
    }
}
