<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomepageRatingPlatform;
use App\Models\HomepageRatingSetting;
use Illuminate\Http\Request;
use App\Support\WebsiteMedia;
use Illuminate\Validation\ValidationException;

class HomepageRatingsController extends Controller
{
    public function index()
    {
        return view('backend.homepage.ratings', [
            'settings' => HomepageRatingSetting::query()->first() ?? new HomepageRatingSetting(),
            'platforms' => HomepageRatingPlatform::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'eyebrow' => ['nullable','string','max:100'],
            'heading' => ['required','string','max:180'],
            'description' => ['nullable','string','max:1000'],
            'background_color' => ['required','regex:/^#[0-9a-fA-F]{6}$/D'],
            'accent_color' => ['required','regex:/^#[0-9a-fA-F]{6}$/D'],
            'scroll_seconds' => ['required','integer','between:15,90'],
            'autoplay' => ['required','boolean'],
            'is_active' => ['required','boolean'],
        ]);
        $settings = HomepageRatingSetting::query()->first() ?? new HomepageRatingSetting();
        $settings->fill($data)->save();
        return redirect()->route('admin.ratings.index')->with('ratings_success', 'Ratings section settings saved.');
    }

    private function validatePlatform(Request $request, bool $create): array
    {
        $data = $request->validate([
            'platform_name' => ['required','string','max:100'],
            'profile_url' => ['required','url','max:2048','regex:/^https:\/\//i'],
            'logo' => [$create ? 'required' : 'nullable','image','mimes:jpg,jpeg,png,webp','max:3072'],
            'rating_value' => ['nullable','numeric','between:0,5'],
            'review_count' => ['nullable','integer','min:0','max:100000000'],
            'sort_order' => ['required','integer','between:0,100000'],
            'is_active' => ['required','boolean'],
            'verified' => ['required','boolean'],
        ]);

        // An active rating badge without manual verification is prohibited.
        if (!(bool)$data['verified'] && (bool)$data['is_active']) {
            throw ValidationException::withMessages(['verified' => 'Verify this is your real public review profile before enabling it.']);
        }
        // The number must be an administrator-verified public score, never made up by the UI.
        if (!(bool)$data['verified'] && ($request->filled('rating_value') || $request->filled('review_count'))) {
            throw ValidationException::withMessages(['verified' => 'Do not enter rating numbers until they are verified.']);
        }
        return $data;
    }

    private function storeLogo(Request $request): ?string
    {
        if (!$request->hasFile('logo')) return null;
        return WebsiteMedia::upload($request->file('logo'), 'media/homepage/ratings');
    }

    public function store(Request $request)
    {
        $data = $this->validatePlatform($request, true);
        $logoPath = $this->storeLogo($request);
        HomepageRatingPlatform::create([
            'platform_name' => $data['platform_name'],
            'profile_url' => $data['profile_url'],
            'logo_path' => $logoPath,
            'rating_value' => $data['rating_value'] ?? null,
            'review_count' => $data['review_count'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active' => (bool)$data['is_active'],
            'verified_at' => $data['verified'] ? now() : null,
        ]);
        return redirect()->route('admin.ratings.index')->with('ratings_success', 'Review profile added.');
    }

    public function update(Request $request, HomepageRatingPlatform $platform)
    {
        $data = $this->validatePlatform($request, false);
        $platform->fill([
            'platform_name' => $data['platform_name'],
            'profile_url' => $data['profile_url'],
            'rating_value' => $data['rating_value'] ?? null,
            'review_count' => $data['review_count'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active' => (bool)$data['is_active'],
            'verified_at' => $data['verified'] ? ($platform->verified_at ?: now()) : null,
        ]);
        if ($newLogo = $this->storeLogo($request)) $platform->logo_path = $newLogo;
        $platform->save();
        return redirect()->route('admin.ratings.index')->with('ratings_success', 'Review profile updated.');
    }

    public function destroy(HomepageRatingPlatform $platform)
    {
        $platform->delete();
        // Retain image in media folder for backups/rollbacks; clean orphan assets separately after review.
        return redirect()->route('admin.ratings.index')->with('ratings_success', 'Review profile deleted.');
    }
}
