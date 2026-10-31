@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start border-bottom mb-4 pb-3">
        <div><h1 class="h3 fw-bold mb-1">Homepage · Client Ratings Showcase</h1>
            <p class="text-muted mb-0">Manage real review-platform profiles. This is separate from the existing video testimonials.</p></div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#client-ratings" target="_blank" rel="noopener noreferrer">View Ratings ↗</a>
    </div>
    @if(session('ratings_success'))<div class="alert alert-success">{{ session('ratings_success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><strong>Please review:</strong><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="alert alert-info">This section stays hidden publicly until at least one <strong>active and verified</strong> review profile exists. Only add profiles belonging to Sunnah Digital Agency. Do not copy competitors’ ratings, badges, or logos.</div>
    <div class="card mb-4"><div class="card-header"><strong>1. Section Settings</strong></div><div class="card-body">
        <form method="POST" action="{{ route('admin.ratings.settings.update') }}">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label" for="ratings-eyebrow">Small heading (optional)</label><input id="ratings-eyebrow" class="form-control" name="eyebrow" maxlength="100" value="{{ old('eyebrow', $settings->eyebrow) }}" placeholder="CLIENT FEEDBACK"></div>
                <div class="col-md-4"><label class="form-label" for="ratings-heading">Main heading</label><input id="ratings-heading" class="form-control" name="heading" maxlength="180" required value="{{ old('heading', $settings->heading ?: 'Where Clients Find Our Reviews') }}"></div>
                <div class="col-md-4"><label class="form-label" for="ratings-speed">Scroll duration (seconds)</label><input id="ratings-speed" class="form-control" type="number" min="15" max="90" name="scroll_seconds" value="{{ old('scroll_seconds', $settings->scroll_seconds ?: 36) }}" required></div>
                <div class="col-12"><label class="form-label" for="ratings-description">Description (optional)</label><textarea id="ratings-description" class="form-control" name="description" rows="2" maxlength="1000">{{ old('description', $settings->description) }}</textarea></div>
                <div class="col-md-3"><label class="form-label" for="ratings-bg">Background color</label><input id="ratings-bg" type="color" class="form-control form-control-color" name="background_color" value="{{ old('background_color', $settings->background_color ?: '#090D14') }}" required></div>
                <div class="col-md-3"><label class="form-label" for="ratings-accent">Accent color</label><input id="ratings-accent" type="color" class="form-control form-control-color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?: '#C74659') }}" required></div>
                <div class="col-md-3"><label class="form-label" for="ratings-autoplay">Auto scroll</label><select id="ratings-autoplay" class="form-select" name="autoplay"><option value="1" {{ (int)old('autoplay', $settings->exists ? $settings->autoplay : 1) === 1 ? 'selected' : '' }}>Enabled</option><option value="0" {{ (int)old('autoplay', $settings->exists ? $settings->autoplay : 1) === 0 ? 'selected' : '' }}>Disabled</option></select></div>
                <div class="col-md-3"><label class="form-label" for="ratings-active">Show section</label><select id="ratings-active" class="form-select" name="is_active"><option value="1" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 1 ? 'selected' : '' }}>Enabled</option><option value="0" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 0 ? 'selected' : '' }}>Hidden</option></select></div>
            </div><button class="btn btn-primary mt-3" type="submit">Save Ratings Settings</button>
        </form>
    </div></div>
    <div class="card mb-4"><div class="card-header"><strong>2. Add a verified review profile</strong></div><div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.ratings.platforms.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Platform name</label><input name="platform_name" value="{{ old('platform_name') }}" class="form-control" maxlength="100" required placeholder="Your actual Google Business Profile"></div>
                <div class="col-md-4"><label class="form-label">Approved logo PNG/JPG/WebP ≤3MB</label><input name="logo" type="file" accept=".png,.jpg,.jpeg,.webp" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Your public review profile HTTPS link</label><input name="profile_url" type="url" class="form-control" value="{{ old('profile_url') }}" required maxlength="2048" placeholder="https://..."></div>
                <div class="col-md-3"><label class="form-label">Verified rating / 5 (optional)</label><input name="rating_value" type="number" step="0.01" min="0" max="5" value="{{ old('rating_value') }}" class="form-control" placeholder="Only verified rating"></div>
                <div class="col-md-3"><label class="form-label">Verified review count (optional)</label><input name="review_count" type="number" min="0" value="{{ old('review_count') }}" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Sort order</label><input name="sort_order" type="number" min="0" max="100000" value="{{ old('sort_order', 0) }}" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="0">Inactive</option><option value="1" {{ old('is_active') === '1' ? 'selected' : '' }}>Active</option></select></div>
                <div class="col-md-2"><label class="form-label">Verification</label><select name="verified" class="form-select"><option value="0">Pending</option><option value="1" {{ old('verified') === '1' ? 'selected' : '' }}>Verified by admin</option></select></div>
            </div>
            <p class="small text-muted mt-3 mb-0">Selecting “Verified by admin” confirms you checked Sunnah’s genuine public profile, permission to show its logo, and any entered score/count.</p>
            <button type="submit" class="btn btn-primary mt-3">Add Review Profile</button>
        </form>
    </div></div>
    <h2 class="h5 mb-3">3. Manage Review Profiles ({{ $platforms->count() }})</h2>
    @forelse($platforms as $platform)
    <div class="card mb-3"><div class="card-body"><div class="row g-3 align-items-start">
        <div class="col-md-2 text-center"><img src="{{ \App\Support\WebsiteMedia::url($platform->logo_path) }}" alt="{{ $platform->platform_name }}" style="max-width:150px;max-height:90px;object-fit:contain;background:#192432;padding:12px;border-radius:8px"><div class="mt-2 small text-muted">{{ $platform->verified_at ? 'Verified: '.$platform->verified_at->format('Y-m-d') : 'Not verified' }}</div></div>
        <div class="col-md-10"><form method="POST" action="{{ route('admin.ratings.platforms.update', $platform) }}" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label">Platform</label><input name="platform_name" maxlength="100" class="form-control" required value="{{ $platform->platform_name }}"></div>
                <div class="col-md-4"><label class="form-label">Replace logo (optional)</label><input type="file" name="logo" accept=".png,.jpg,.jpeg,.webp" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Review profile HTTPS URL</label><input type="url" name="profile_url" maxlength="2048" required class="form-control" value="{{ $platform->profile_url }}"></div>
                <div class="col-md-2"><label class="form-label">Rating / 5</label><input type="number" step="0.01" min="0" max="5" name="rating_value" class="form-control" value="{{ $platform->rating_value }}"></div>
                <div class="col-md-2"><label class="form-label">Reviews</label><input type="number" min="0" name="review_count" class="form-control" value="{{ $platform->review_count }}"></div>
                <div class="col-md-2"><label class="form-label">Order</label><input type="number" min="0" max="100000" name="sort_order" class="form-control" required value="{{ $platform->sort_order }}"></div>
                <div class="col-md-3"><label class="form-label">Visibility</label><select class="form-select" name="is_active"><option value="1" {{ $platform->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$platform->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                <div class="col-md-3"><label class="form-label">Verification</label><select class="form-select" name="verified"><option value="1" {{ $platform->verified_at ? 'selected' : '' }}>Verified by admin</option><option value="0" {{ !$platform->verified_at ? 'selected' : '' }}>Pending</option></select></div>
            </div><button type="submit" class="btn btn-outline-primary mt-3">Save Profile #{{ $platform->id }}</button>
        </form>
        <form class="mt-2" action="{{ route('admin.ratings.platforms.destroy', $platform) }}" method="POST" onsubmit="return confirm('Delete this profile?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete Profile</button></form>
        </div>
    </div></div></div>
    @empty<div class="alert alert-secondary">No review profiles yet. Add only genuine, approved review profiles to display the section publicly.</div>@endforelse
</section>
@endsection
