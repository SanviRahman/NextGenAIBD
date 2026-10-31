<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomepageFeaturedItem;
use App\Models\HomepageServiceSetting;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomepageServicesController extends Controller
{
    public function index()
    {
        $settings = HomepageServiceSetting::query()->first() ?? new HomepageServiceSetting();
        $services = Service::query()->where('status', '1')->orderBy('title')->orderBy('id')->get();
        $featured = HomepageFeaturedItem::query()->where('section_key', 'services')
            ->where('item_type', 'service')->orderBy('sort_order')->orderBy('id')->get()->keyBy('item_id');

        return view('backend.homepage.services', compact('settings', 'services', 'featured'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'heading' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1200'],
            'button_label' => ['required', 'string', 'max:80'],
            'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'card_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'cards_limit' => ['required', 'integer', 'between:3,12'],
            'is_active' => ['required', 'boolean'],
        ]);

        $settings = HomepageServiceSetting::query()->first() ?? new HomepageServiceSetting();
        $settings->fill($data)->save();

        return back()->with('services_success', 'Homepage Services settings saved.');
    }

    public function updateFeatured(Request $request)
    {
        $data = $request->validate([
            'selected' => ['sometimes', 'array', 'max:60'],
            'selected.*' => ['integer', 'distinct'],
            'order' => ['sometimes', 'array', 'max:60'],
            'order.*' => ['integer', 'between:0,100000'],
        ]);

        $ids = array_values(array_unique(array_map('intval', $data['selected'] ?? [])));
        $activeIds = Service::query()->where('status', '1')->whereIn('id', $ids)->pluck('id')->map(fn ($id) => (int)$id)->all();
        if (count($activeIds) !== count($ids)) {
            throw ValidationException::withMessages(['selected' => 'Only currently active services can be featured.']);
        }

        DB::transaction(function () use ($ids, $data) {
            $orders = $data['order'] ?? [];
            // Only the homepage services selection is replaced. Other featured sections remain untouched.
            HomepageFeaturedItem::query()->where('section_key', 'services')->where('item_type', 'service')->delete();
            foreach ($ids as $position => $id) {
                HomepageFeaturedItem::create([
                    'section_key' => 'services',
                    'item_type' => 'service',
                    'item_id' => $id,
                    'sort_order' => (int)($orders[$id] ?? ($position + 1) * 10),
                    'is_active' => true,
                ]);
            }
        });

        return back()->with('services_success', count($ids)
            ? 'Featured Services updated. Only selected active services will appear.'
            : 'Featured selection cleared. Homepage will show the latest active services automatically.');
    }
}
