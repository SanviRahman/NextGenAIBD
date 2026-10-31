<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\HomepageConsultationSetting;
use Illuminate\Http\Request;
use App\Support\WebsiteMedia;
use Illuminate\Validation\Rule;

class HomepageConsultationController extends Controller
{
    public function index(Request $request)
    {
        $filter = (string) $request->query('status', '');
        $statuses = ['new', 'contacted', 'qualified', 'closed', 'spam'];
        $requests = ConsultationRequest::query()
            ->when(in_array($filter, $statuses, true), fn($q) => $q->where('status', $filter))
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('backend.homepage.consultation', [
            'settings' => HomepageConsultationSetting::query()->first() ?? new HomepageConsultationSetting(),
            'leads' => $requests,
            'statuses' => $statuses,
            'filter' => $filter,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'heading' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_alt' => ['required', 'string', 'max:200'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/D'],
            'is_active' => ['required', 'boolean'],
        ]);
        $settings = HomepageConsultationSetting::query()->first() ?? new HomepageConsultationSetting();
        $settings->fill(collect($data)->except('photo')->all());

        if ($request->hasFile('photo')) {
            $settings->image_path = WebsiteMedia::upload($request->file('photo'), 'media/homepage/consultation');
            // Retain the old file for safe deployment rollback.
        }
        $settings->save();
        return redirect()->route('admin.consultation.index')->with('consultation_admin_success', 'Consultation section settings updated.');
    }

    public function show(ConsultationRequest $consultation)
    {
        return view('backend.homepage.consultation-detail', ['lead' => $consultation]);
    }

    public function updateLead(Request $request, ConsultationRequest $consultation)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'contacted', 'qualified', 'closed', 'spam'])],
            'internal_notes' => ['nullable', 'string', 'max:4000'],
        ]);
        if ($consultation->status === 'new' && $data['status'] !== 'new') {
            $consultation->responded_at = now();
        }
        $consultation->fill($data)->save();
        return back()->with('consultation_admin_success', 'Request updated.');
    }
}
