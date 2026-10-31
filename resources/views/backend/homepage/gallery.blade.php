@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start border-bottom mb-4 pb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Homepage · Memories &amp; Moments</h1>
            <p class="text-muted mb-0">Reuses the existing <code>abouts</code> table; other homepage sections remain unchanged.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#memories-moments" target="_blank" rel="noopener noreferrer">View Gallery ↗</a>
    </div>
    <div class="alert alert-info">Only upload photos that Sunnah Digital Agency owns or is permitted to publish. The public gallery only shows <strong>Active + Permission Confirmed</strong> photos. No photos are published automatically.</div>
    @if(session('gallery_success'))<div class="alert alert-success">{{ session('gallery_success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert"><strong>Fix these fields:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">1. Section Settings</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.settings.update') }}">@csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-lg-8"><label class="form-label">Main heading</label><input class="form-control" name="title" required maxlength="180" value="{{ old('title', $settings->title ?: 'Memories & Moments') }}"></div>
                    <div class="col-lg-4"><label class="form-label">Section visibility</label><select name="status" class="form-select"><option value="1" {{ old('status', $settings->status ?? '1') == '1' ? 'selected' : '' }}>Active</option><option value="0" {{ old('status', $settings->status ?? '1') == '0' ? 'selected' : '' }}>Inactive</option></select></div>
                    <div class="col-lg-8"><label class="form-label">Description (optional)</label><textarea class="form-control" name="description" rows="2" maxlength="800">{{ old('description', $settings->description) }}</textarea></div>
                    <div class="col-lg-2"><label class="form-label">Background color</label><input class="form-control form-control-color" name="homepage_bg_color" type="color" value="{{ old('homepage_bg_color', $settings->homepage_bg_color ?: '#0B0F16') }}"></div>
                    <div class="col-lg-2"><label class="form-label">Accent color</label><input class="form-control form-control-color" name="homepage_accent_color" type="color" value="{{ old('homepage_accent_color', $settings->homepage_accent_color ?: '#C74659') }}"></div>
                </div>
                <button class="btn btn-primary mt-3">Save Gallery Settings</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">2. Add a Gallery Photo</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.items.store') }}" enctype="multipart/form-data">@csrf
                <div class="row g-3">
                    <div class="col-md-5"><label class="form-label">Caption (optional)</label><input class="form-control" name="title" maxlength="180" value="{{ old('title') }}" placeholder="Our team celebrating a milestone"></div>
                    <div class="col-md-4"><label class="form-label">Approved photo (JPG/PNG/WebP ≤3MB)</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" required></div>
                    <div class="col-md-3"><label class="form-label">Accessible image description (alt)</label><input class="form-control" name="homepage_image_alt" maxlength="200" required value="{{ old('homepage_image_alt') }}" placeholder="Agency team working together"></div>
                    <div class="col-md-3"><label class="form-label">Tile size</label><select class="form-select" name="homepage_gallery_size_variant"><option value="regular">Regular</option><option value="wide">Wide</option><option value="tall">Tall</option></select></div>
                    <div class="col-md-3"><label class="form-label">Photo date (optional)</label><input class="form-control" type="date" name="homepage_gallery_taken_on" max="{{ date('Y-m-d') }}" value="{{ old('homepage_gallery_taken_on') }}"></div>
                    <div class="col-md-3"><label class="form-label">Sort order</label><input class="form-control" type="number" name="homepage_sort_order" min="0" max="100000" value="{{ old('homepage_sort_order', 10) }}" required></div>
                    <div class="col-md-3"><label class="form-label">Visibility</label><select class="form-select" name="status"><option value="0">Inactive</option><option value="1">Active</option></select></div>
                    <div class="col-12"><label class="form-check-label"><input type="checkbox" class="form-check-input me-2" name="homepage_gallery_permission_confirmed" value="1" required> I confirm we own this image or have the photographer's and identifiable subjects' permission to publish it.</label></div>
                </div>
                <button class="btn btn-primary mt-3">Add Photo</button>
            </form>
        </div>
    </div>

    <h2 class="h5 mb-3">3. Manage Gallery Photos ({{ $items->count() }})</h2>
    @forelse($items as $item)
        <div class="card shadow-sm mb-3"><div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    @if($item->image && preg_match('/^sda-gallery-[a-f0-9-]+\.(png|jpe?g|webp)$/iD', $item->image))
                        <img src="{{ \App\Support\WebsiteMedia::url('media/homepage/gallery/' . basename((string) $item->image)) }}" alt="{{ $item->homepage_image_alt }}" class="img-fluid rounded" style="max-height:175px;object-fit:contain">
                    @endif
                    <div class="small text-muted mt-2">Photo #{{ $item->id }}</div>
                </div>
                <div class="col-md-10">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.gallery.items.update', $item->id) }}">@csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-5"><label class="form-label">Caption</label><input class="form-control" name="title" maxlength="180" value="{{ $item->title }}"></div>
                            <div class="col-md-4"><label class="form-label">Replace image (optional)</label><input class="form-control" name="image" type="file" accept="image/jpeg,image/png,image/webp"></div>
                            <div class="col-md-3"><label class="form-label">Alt text</label><input class="form-control" name="homepage_image_alt" required maxlength="200" value="{{ $item->homepage_image_alt }}"></div>
                            <div class="col-md-3"><label class="form-label">Tile size</label><select class="form-select" name="homepage_gallery_size_variant">@foreach(['regular'=>'Regular','wide'=>'Wide','tall'=>'Tall'] as $value => $label)<option value="{{ $value }}" {{ ($item->homepage_gallery_size_variant ?: 'regular') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                            <div class="col-md-3"><label class="form-label">Photo date</label><input class="form-control" type="date" name="homepage_gallery_taken_on" max="{{ date('Y-m-d') }}" value="{{ $item->homepage_gallery_taken_on }}"></div>
                            <div class="col-md-3"><label class="form-label">Sort order</label><input class="form-control" type="number" min="0" max="100000" name="homepage_sort_order" required value="{{ $item->homepage_sort_order }}"></div>
                            <div class="col-md-3"><label class="form-label">Visibility</label><select class="form-select" name="status"><option value="1" {{ (string)$item->status === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ (string)$item->status !== '1' ? 'selected' : '' }}>Inactive</option></select></div>
                            <div class="col-12"><label class="form-check-label"><input class="form-check-input me-2" type="checkbox" name="homepage_gallery_permission_confirmed" value="1" {{ $item->homepage_gallery_permission_confirmed ? 'checked' : '' }}> Photo publication permission confirmed</label></div>
                        </div>
                        <button class="btn btn-outline-primary mt-3" type="submit">Save Photo #{{ $item->id }}</button>
                    </form>
                    <form class="mt-2" method="POST" action="{{ route('admin.gallery.items.destroy', $item->id) }}" onsubmit="return confirm('Permanently delete this photo?');">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit">Delete Photo</button></form>
                </div>
            </div>
        </div></div>
    @empty
        <div class="alert alert-secondary">No gallery photos yet. Add your own permission-confirmed moments above.</div>
    @endforelse
</section>
@endsection
