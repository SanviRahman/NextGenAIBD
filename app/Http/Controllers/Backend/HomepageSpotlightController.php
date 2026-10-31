<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomepagePressMention;
use App\Models\HomepageSpotlightSetting;
use App\Models\HomepageSpotlightVideo;
use App\Support\HomepageSpotlightMedia;
use App\Support\HomepageSpotlightUrl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class HomepageSpotlightController extends Controller
{
    public function index()
    {
        return view('backend.homepage.spotlight', [
            'settings' => HomepageSpotlightSetting::query()->first() ?? new HomepageSpotlightSetting(),
            'videos' => HomepageSpotlightVideo::query()->orderBy('sort_order')->orderBy('id')->get(),
            'mentions' => HomepagePressMention::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'heading' => ['required', 'string', 'max:180'],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'background_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'accent_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'logo_scroll_seconds' => ['required', 'integer', 'between:18,90'],
            'logo_autoplay' => ['required', 'boolean'],
            'video_autoplay' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);
        $settings = HomepageSpotlightSetting::query()->first() ?? new HomepageSpotlightSetting();
        $settings->fill($data)->save();
        return back()->with('spotlight_success', 'Spotlight settings saved.');
    }

    private function urlRule(): \Closure
    {
        return static function (string $attribute, $value, \Closure $fail): void {
            if (!is_string($value) || strlen($value) > 2048 || !filter_var($value, FILTER_VALIDATE_URL) ||
                (parse_url($value, PHP_URL_SCHEME) !== 'https')) {
                $fail('Use a valid HTTPS URL.');
            }
        };
    }

    private function videoInput(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'caption' => ['nullable', 'string', 'max:2000'],
            'poster' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'preview_video' => ['nullable', 'file', 'mimes:mp4,webm', 'max:30720'],
            'full_video_upload' => ['nullable', 'file', 'mimes:mp4,webm', 'max:65536'],
            'full_video_provider' => ['required', Rule::in(['youtube', 'vimeo', 'upload'])],
            'full_video_url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['required', 'integer', 'between:0,100000'],
            'is_active' => ['required', 'boolean'],
            'remove_preview' => ['nullable', 'boolean'],
        ]);
    }

    private function persistVideo(Request $request, HomepageSpotlightVideo $video, bool $creating): void
    {
        $data = $this->videoInput($request, $creating);
        $provider = $data['full_video_provider'];
        $url = trim((string) ($data['full_video_url'] ?? ''));

        if ($provider === 'youtube' && !HomepageSpotlightUrl::youtubeId($url)) {
            throw ValidationException::withMessages(['full_video_url' => 'Provide a valid YouTube video URL (watch, shorts, live or youtu.be).']);
        }
        if ($provider === 'vimeo' && !HomepageSpotlightUrl::vimeoId($url)) {
            throw ValidationException::withMessages(['full_video_url' => 'Provide a supported HTTPS Vimeo video URL.']);
        }
        if ($provider === 'upload' && !$request->hasFile('full_video_upload') &&
            ($creating || $video->full_video_provider !== 'upload' || !str_starts_with((string) $video->full_video_url, 'media/homepage/spotlight/'))) {
            throw ValidationException::withMessages(['full_video_upload' => 'Upload the complete video for Local Upload provider.']);
        }

        $video->fill(collect($data)->only(['title', 'caption', 'sort_order', 'is_active'])->all());
        if ($request->hasFile('poster')) {
            $video->poster_path = HomepageSpotlightMedia::upload($request->file('poster'), 'spotlight');
        }
        if ($request->boolean('remove_preview')) {
            $video->preview_video_path = null;
        } elseif ($request->hasFile('preview_video')) {
            $video->preview_video_path = HomepageSpotlightMedia::upload($request->file('preview_video'), 'spotlight');
        }
        $video->full_video_provider = $provider;
        if ($provider === 'upload') {
            if ($request->hasFile('full_video_upload')) {
                $video->full_video_url = HomepageSpotlightMedia::upload($request->file('full_video_upload'), 'spotlight');
            }
        } else {
            $video->full_video_url = $url;
        }
        $video->save();
    }

    public function storeVideo(Request $request)
    {
        $this->persistVideo($request, new HomepageSpotlightVideo(), true);
        return back()->with('spotlight_success', 'Spotlight video created.');
    }

    public function updateVideo(Request $request, HomepageSpotlightVideo $video)
    {
        $this->persistVideo($request, $video, false);
        return back()->with('spotlight_success', 'Spotlight video updated.');
    }

    public function deleteVideo(HomepageSpotlightVideo $video)
    {
        $video->delete();
        return back()->with('spotlight_success', 'Video record removed; uploaded files retained for recovery.');
    }

    private function mentionInput(Request $request, bool $creating): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'alt_text' => ['nullable', 'string', 'max:200'],
            'website_url' => ['nullable', $this->urlRule()],
            'logo' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['required', 'integer', 'between:0,100000'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function persistMention(Request $request, HomepagePressMention $mention, bool $creating): void
    {
        $data = $this->mentionInput($request, $creating);
        $mention->fill(collect($data)->except(['logo'])->all());
        if ($request->hasFile('logo')) {
            $mention->logo_path = HomepageSpotlightMedia::upload($request->file('logo'), 'press');
        }
        $mention->save();
    }

    public function storeMention(Request $request)
    {
        $this->persistMention($request, new HomepagePressMention(), true);
        return back()->with('spotlight_success', 'Logo added. Publish only authentic/approved media or partner marks.');
    }

    public function updateMention(Request $request, HomepagePressMention $mention)
    {
        $this->persistMention($request, $mention, false);
        return back()->with('spotlight_success', 'Logo updated.');
    }

    public function deleteMention(HomepagePressMention $mention)
    {
        $mention->delete();
        return back()->with('spotlight_success', 'Logo record removed; file retained.');
    }
}
