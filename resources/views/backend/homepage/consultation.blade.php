@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4">
        <div><h1 class="h2 fw-bold">Homepage · Free Consultation</h1>
            <p class="text-muted mb-0">Manage the homepage section and incoming consultation requests. Leads are visible only to administrators.</p></div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#consultation" target="_blank" rel="noopener noreferrer">View homepage ↗</a>
    </div>
    @if(session('consultation_admin_success'))<div class="alert alert-success" role="status">{{ session('consultation_admin_success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="card mb-4"><div class="card-header"><h2 class="h5 mb-0">1. Consultation section settings</h2></div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.consultation.settings.update') }}">@csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-lg-4"><label class="form-label" for="c-eyebrow">Small heading</label><input id="c-eyebrow" class="form-control" name="eyebrow" maxlength="100" value="{{ old('eyebrow', $settings->eyebrow) }}" placeholder="Start a Conversation"></div>
                    <div class="col-lg-5"><label class="form-label" for="c-heading">Main heading</label><input id="c-heading" class="form-control" name="heading" required maxlength="180" value="{{ old('heading', $settings->heading ?: 'Book a Free Consultation') }}"></div>
                    <div class="col-lg-3"><label class="form-label" for="c-status">Section visibility</label><select id="c-status" name="is_active" class="form-select"><option value="1" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 1 ? 'selected':'' }}>Active</option><option value="0" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 0 ? 'selected':'' }}>Hidden</option></select></div>
                    <div class="col-12"><label class="form-label" for="c-description">Description (optional)</label><textarea id="c-description" name="description" maxlength="1000" rows="2" class="form-control">{{ old('description', $settings->description) }}</textarea></div>
                    <div class="col-md-4"><label class="form-label" for="c-photo">Consultation image (JPG/PNG/WebP ≤6MB)</label><input id="c-photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label" for="c-alt">Image description</label><input id="c-alt" name="image_alt" maxlength="200" required class="form-control" value="{{ old('image_alt', $settings->image_alt ?: 'Sunnah Digital Agency team collaborating') }}"></div>
                    <div class="col-md-2"><label class="form-label" for="c-bg">Background color</label><input id="c-bg" type="color" name="background_color" class="form-control form-control-color" value="{{ old('background_color', $settings->background_color ?: '#0B0F16') }}"></div>
                    <div class="col-md-2"><label class="form-label" for="c-accent">Accent color</label><input id="c-accent" type="color" name="accent_color" class="form-control form-control-color" value="{{ old('accent_color', $settings->accent_color ?: '#C74659') }}"></div>
                    <div class="col-12">
                        @if($settings->image_path && \App\Support\WebsiteMedia::exists($settings->image_path))
                            <img src="{{ \App\Support\WebsiteMedia::url($settings->image_path) }}" alt="Consultation image preview" style="max-width:240px;max-height:140px;object-fit:cover" class="rounded">
                        @elseif($settings->image_path)
                            <div class="alert alert-warning py-2 mb-0" role="status">Saved image is missing from the live media directory. Run the media repair command or upload the photo again. The homepage uses a safe fallback until restored.</div>
                        @else
                            <span class="text-muted small">Default photo from existing frontend assets. Upload your own team photo to replace.</span>
                        @endif
                    </div>
                </div><button type="submit" class="btn btn-primary mt-3">Save Consultation Settings</button>
            </form>
        </div>
    </div>

    <div class="card mb-4"><div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
        <h2 class="h5 mb-0">2. Consultation Inbox ({{ $leads->total() }})</h2>
        <form method="GET" action="{{ route('admin.consultation.index') }}" class="d-flex gap-2 align-items-center">
            <label for="c-filter">Status</label><select id="c-filter" name="status" class="form-select form-select-sm">
                <option value="">All</option>@foreach($statuses as $status)<option value="{{ $status }}" {{ $filter===$status?'selected':'' }}>{{ ucfirst($status) }}</option>@endforeach
            </select><button class="btn btn-outline-primary btn-sm">Filter</button>
        </form></div>
        <div class="table-responsive"><table class="table table-hover align-middle mb-0">
            <thead><tr><th>Date</th><th>Name</th><th>Company</th><th>Contact</th><th>Status</th><th></th></tr></thead>
            <tbody>@forelse($leads as $lead)<tr>
                <td class="text-nowrap">{{ $lead->created_at?->format('d M Y, H:i') }}</td>
                <td>{{ $lead->name }}</td><td>{{ $lead->company_name ?: '—' }}</td>
                <td><span class="d-block">{{ $lead->email ?: '—' }}</span><small>{{ $lead->phone }}</small></td>
                <td><span class="badge bg-secondary">{{ ucfirst($lead->status) }}</span></td>
                <td><a class="btn btn-outline-primary btn-sm" href="{{ route('admin.consultation.requests.show', $lead) }}">View request</a></td>
            </tr>@empty<tr><td colspan="6" class="text-muted py-4 text-center">No consultation requests yet.</td></tr>@endforelse</tbody>
        </table></div>
        <div class="card-footer">{{ $leads->links() }}</div>
    </div>
    <p class="small text-muted">Spam prevention: 5 submissions / 10 minutes per client + honeypot. Optional Google reCAPTCHA v2 checkbox requires valid keys in .env. No raw applicant content is rendered as HTML.</p>
</section>
@endsection
