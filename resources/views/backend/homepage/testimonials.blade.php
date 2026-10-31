@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start border-bottom mb-4 pb-3">
        <div><h1 class="h3 fw-bold mb-1">Homepage · Client Video Testimonials</h1>
            <p class="text-muted mb-0">Reuses <code>client_reviews</code> for videos and <code>abouts</code> for section settings.</p></div>
        <a href="{{ route('home_Index') }}#client-testimonials" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">View Testimonials ↗</a>
    </div>
    <div class="alert alert-info">Only publish genuine client testimonials with permission. Entries must be <strong>Active + Permission Confirmed</strong>. YouTube/Vimeo links only; thumbnails must be yours or licensed. No videos are published automatically by this patch.</div>
    @if(session('testimonials_success'))<div class="alert alert-success">{{ session('testimonials_success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><strong>Check the form:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="card mb-4"><div class="card-header"><strong>1. Section Settings</strong></div><div class="card-body">
        <form method="POST" action="{{ route('admin.testimonials.settings.update') }}">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8"><label for="testimonials-heading" class="form-label">Main heading</label><input id="testimonials-heading" class="form-control" name="title" maxlength="180" required value="{{ old('title', $settings->title ?: 'Stories From Our Clients') }}"></div>
                <div class="col-md-4"><label for="testimonials-visible" class="form-label">Section visibility</label><select id="testimonials-visible" name="status" class="form-select"><option value="1" {{ (string)old('status', $settings->exists ? $settings->status : '1') === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ (string)old('status', $settings->exists ? $settings->status : '1') === '0' ? 'selected' : '' }}>Hidden</option></select></div>
                <div class="col-md-8"><label for="testimonials-description" class="form-label">Description (optional)</label><textarea id="testimonials-description" name="description" rows="2" maxlength="800" class="form-control">{{ old('description', $settings->description) }}</textarea></div>
                <div class="col-md-2"><label for="testimonials-bg" class="form-label">Background color</label><input type="color" id="testimonials-bg" name="homepage_bg_color" class="form-control form-control-color" value="{{ old('homepage_bg_color', $settings->homepage_bg_color ?: '#0B0F16') }}"></div>
                <div class="col-md-2"><label for="testimonials-accent" class="form-label">Accent color</label><input type="color" id="testimonials-accent" name="homepage_accent_color" class="form-control form-control-color" value="{{ old('homepage_accent_color', $settings->homepage_accent_color ?: '#C74659') }}"></div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Testimonial Settings</button>
        </form>
    </div></div>

    <div class="card mb-4"><div class="card-header"><strong>2. Add a Genuine Video Testimonial</strong></div><div class="card-body">
        <form action="{{ route('admin.testimonials.videos.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label" for="tv-name">Client / speaker name</label><input id="tv-name" name="user_name" class="form-control" required maxlength="160" value="{{ old('user_name') }}" placeholder="Actual client name"></div>
                <div class="col-md-4"><label class="form-label" for="tv-image">Approved video thumbnail (≤4MB)</label><input id="tv-image" name="image" type="file" class="form-control" required accept=".png,.jpg,.jpeg,.webp"></div>
                <div class="col-md-4"><label class="form-label" for="tv-url">Full YouTube/Vimeo HTTPS video URL</label><input id="tv-url" name="video_url" class="form-control" type="url" required maxlength="2048" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url') }}"></div>
                <div class="col-md-7"><label class="form-label" for="tv-description">Short caption (optional)</label><textarea id="tv-description" name="description" class="form-control" rows="2" maxlength="1500" placeholder="Genuine testimonial context">{{ old('description') }}</textarea></div>
                <div class="col-md-2"><label class="form-label" for="tv-order">Sort order</label><input id="tv-order" name="homepage_sort_order" class="form-control" required type="number" min="0" max="100000" value="{{ old('homepage_sort_order', ($reviews->count()+1)*10) }}"></div>
                <div class="col-md-3"><label class="form-label" for="tv-status">Visibility</label><select id="tv-status" name="status" class="form-select"><option value="0" selected>Inactive</option><option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option></select></div>
                <div class="col-12"><label class="form-check-label"><input name="homepage_permission_confirmed" type="checkbox" class="form-check-input me-2" value="1" {{ old('homepage_permission_confirmed') ? 'checked' : '' }} required> I confirm this is a real testimonial and I have permission to publish the video and image.</label></div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Video Testimonial</button>
        </form>
    </div></div>

    <h2 class="h5 mb-3">3. Manage Video Testimonials ({{ $reviews->count() }})</h2>
    @forelse($reviews as $review)
        <div class="card mb-3"><div class="card-body">
            <div class="mb-2 small text-muted">Record #{{ $review->id }} — {{ $review->homepage_permission_confirmed && (string)$review->status === '1' ? 'Approved & Active' : 'Hidden until approved' }}</div>
            <form action="{{ route('admin.testimonials.videos.update', $review->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                <div class="row g-3 align-items-start">
                    <div class="col-md-2">
                        @if($review->image)
                            @php
                                $previewImg = preg_match('/^sda-testimonial-[a-f0-9-]+\.(?:png|jpg|jpeg|webp)$/iD', (string)$review->image) ? \App\Support\WebsiteMedia::url('media/homepage/testimonials/' . basename((string)$review->image)) : null;
                            @endphp
                            @if($previewImg)<img src="{{ $previewImg }}" alt="Thumbnail of {{ $review->user_name }}" class="img-fluid rounded">@else <span class="text-muted small">Legacy thumbnail: {{ $review->image }}</span> @endif
                        @endif
                    </div>
                    <div class="col-md-5"><label class="form-label">Client / speaker name</label><input class="form-control" name="user_name" maxlength="160" required value="{{ $review->user_name }}"><label class="form-label mt-2">Video URL</label><input type="url" class="form-control" name="video_url" required maxlength="2048" value="{{ $review->video_url }}"></div>
                    <div class="col-md-5"><label class="form-label">Replace thumbnail (optional ≤4MB)</label><input name="image" type="file" accept=".png,.jpg,.jpeg,.webp" class="form-control"><label class="form-label mt-2">Caption (optional)</label><textarea class="form-control" name="description" rows="2" maxlength="1500">{{ $review->description }}</textarea></div>
                    <div class="col-md-2"><label class="form-label">Order</label><input name="homepage_sort_order" required type="number" min="0" max="100000" value="{{ $review->homepage_sort_order }}" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Visibility</label><select class="form-select" name="status"><option value="0" {{ (string)$review->status === '0' ? 'selected' : '' }}>Inactive</option><option value="1" {{ (string)$review->status === '1' ? 'selected' : '' }}>Active</option></select></div>
                    <div class="col-md-7 d-flex align-items-end"><label class="form-check-label"><input name="homepage_permission_confirmed" type="checkbox" value="1" class="form-check-input me-2" {{ $review->homepage_permission_confirmed ? 'checked' : '' }}> Permission to publish this real testimonial confirmed</label></div>
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3">Save Video #{{ $review->id }}</button>
            </form>
            <form method="POST" action="{{ route('admin.testimonials.videos.destroy', $review->id) }}" class="mt-2" onsubmit="return confirm('Permanently delete this testimonial?');">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm">Delete Testimonial</button></form>
        </div></div>
    @empty
        <div class="alert alert-secondary">No testimonials yet. Add a real video with a permitted thumbnail to show this section.</div>
    @endforelse
</section>
@endsection
