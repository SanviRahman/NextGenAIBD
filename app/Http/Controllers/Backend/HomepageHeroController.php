<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\HomepageHeroSetting;
use App\Models\HomepageHeroSlide;
use App\Models\HomepageHighlight;
use App\Support\HomepageHeroMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HomepageHeroController extends Controller
{
    public function index()
    {
        return view('backend.homepage.hero', [
            'banner' => Banner::where('status', '1')->orderBy('id')->first() ?? Banner::first(),
            'settings' => HomepageHeroSetting::first() ?? new HomepageHeroSetting(),
            'slides' => HomepageHeroSlide::with(['highlights' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }])->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    private function safeLinkRule(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if (!is_string($value) || strlen($value) > 1024) {
                $fail('Invalid link length.');
                return;
            }
            $local = preg_match('@^/(?!/)[A-Za-z0-9/_?#=&%.\-~]*$@D', $value);
            $external = str_starts_with($value, 'https://') && filter_var($value, FILTER_VALIDATE_URL);
            if (!$local && !$external) {
                $fail('Use a local /path or an https:// URL.');
            }
        };
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'sort_title' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'primary_cta_label' => ['required', 'string', 'max:70'],
            'primary_cta_url' => ['required', $this->safeLinkRule()],
            'secondary_cta_label' => ['required', 'string', 'max:70'],
            'secondary_cta_url' => ['required', $this->safeLinkRule()],
            'background_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'text_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'autoplay_enabled' => ['required', 'boolean'],
        ]);
        DB::transaction(function () use ($data) {
            $banner = Banner::where('status', '1')->orderBy('id')->first() ?? Banner::first() ?? new Banner();
            $banner->fill([
                'title' => $data['title'],
                'sort_title' => $data['sort_title'],
                'description' => $data['description'] ?? null,
                'status' => '1',
            ]);
            $banner->save();
            $settingsData = collect($data)->except(['title', 'sort_title', 'description'])->all();
            $settings = HomepageHeroSetting::first() ?? new HomepageHeroSetting();
            $settings->fill($settingsData);
            $settings->save();
        });
        return back()->with('hero_success', 'Hero text, CTA buttons and appearance saved.');
    }

    private function slideInput(Request $request, bool $creating = false): array
    {
        return $request->validate([
            'internal_name' => ['required', 'string', 'max:140'],
            'main_image_alt' => ['required', 'string', 'max:220'],
            'main_image' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'background_shape' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'remove_background' => ['nullable', 'boolean'],
            'caption_text' => ['nullable', 'string', 'max:255'],
            'caption_animation' => ['required', Rule::in(['reveal', 'typewriter', 'none'])],
            'display_duration_ms' => ['required', 'integer', 'between:3000,15000'],
            'sort_order' => ['required', 'integer', 'between:0,100000'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function persistSlide(Request $request, HomepageHeroSlide $slide, bool $creating): void
    {
        $input = $this->slideInput($request, $creating);
        unset($input['main_image'], $input['background_shape'], $input['remove_background']);
        $slide->fill($input);
        if ($request->hasFile('main_image')) {
            $slide->main_image_path = HomepageHeroMedia::upload($request->file('main_image'));
        }
        if ($request->boolean('remove_background')) {
            $slide->background_shape_path = null;
        } elseif ($request->hasFile('background_shape')) {
            $slide->background_shape_path = HomepageHeroMedia::upload($request->file('background_shape'));
        }
        $slide->save();
    }

    public function storeSlide(Request $request)
    {
        $this->persistSlide($request, new HomepageHeroSlide(), true);
        return back()->with('hero_success', 'Hero slide created.');
    }

    public function updateSlide(Request $request, HomepageHeroSlide $slide)
    {
        $this->persistSlide($request, $slide, false);
        return back()->with('hero_success', 'Hero slide updated.');
    }

    public function deleteSlide(HomepageHeroSlide $slide)
    {
        // The foreign key makes old highlights unassigned, not deleted.
        // Uploaded files are retained for safe manual cleanup/rollback.
        $slide->delete();
        return back()->with('hero_success', 'Slide removed. Its image files were retained.');
    }

    private function highlightInput(Request $request): array
    {
        $data = $request->validate([
            'hero_slide_id' => ['required', 'integer', 'exists:homepage_hero_slides,id'],
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'display_value' => ['nullable', 'string', 'max:60'],
            'link_url' => ['nullable', $this->safeLinkRule()],
            'icon_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['required', 'integer', 'between:0,100000'],
            'is_active' => ['required', 'boolean'],
        ]);
        unset($data['icon_image']);
        return $data;
    }

    public function storeHighlight(Request $request)
    {
        $input = $this->highlightInput($request);
        $highlight = new HomepageHighlight($input);
        if ($request->hasFile('icon_image')) {
            $highlight->icon_path = HomepageHeroMedia::upload($request->file('icon_image'));
        }
        $highlight->save();
        return back()->with('hero_success', 'Floating card created.');
    }

    public function updateHighlight(Request $request, HomepageHighlight $highlight)
    {
        $input = $this->highlightInput($request);
        $highlight->fill($input);
        if ($request->hasFile('icon_image')) {
            $highlight->icon_path = HomepageHeroMedia::upload($request->file('icon_image'));
        }
        $highlight->save();
        return back()->with('hero_success', 'Floating card updated.');
    }

    public function deleteHighlight(HomepageHighlight $highlight)
    {
        $highlight->delete();
        return back()->with('hero_success', 'Floating card removed.');
    }
}
