@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start border-bottom mb-4 pb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Homepage · Why Choose Us / Benefits</h1>
            <p class="text-muted mb-0">Reuses the existing <code>abouts</code> table. Original About Us content is preserved.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#why-choose-us" target="_blank" rel="noopener noreferrer">View Benefits ↗</a>
    </div>
    @if(session('benefits_success'))<div class="alert alert-success">{{ session('benefits_success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Please check the form:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="card mb-4">
        <div class="card-header"><strong>1. Section Settings</strong></div>
        <div class="card-body">
            <form action="{{ route('admin.benefits.settings.update') }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8"><label for="benefits-heading" class="form-label">Main heading</label><input id="benefits-heading" name="title" maxlength="180" required class="form-control" value="{{ old('title', $settings->title ?: 'Discover the Benefits with Sunnah') }}"></div>
                    <div class="col-md-4"><label for="benefits-enabled" class="form-label">Section visibility</label><select id="benefits-enabled" class="form-select" name="status"><option value="1" {{ old('status', $settings->exists ? $settings->status : '1') === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ old('status', $settings->exists ? $settings->status : '1') === '0' ? 'selected' : '' }}>Hidden</option></select></div>
                    <div class="col-12"><label class="form-label" for="benefits-intro">Small description (optional)</label><textarea id="benefits-intro" name="description" maxlength="1000" rows="2" class="form-control">{{ old('description', $settings->description) }}</textarea></div>
                    <div class="col-md-5"><label class="form-label" for="benefits-image">Agency image (JPG/PNG/WebP, max 6 MB)</label><input id="benefits-image" name="image" type="file" accept=".png,.jpg,.jpeg,.webp" class="form-control">
                        <small class="text-muted d-block mt-1">If empty, the current About Us photo will be used (when available).</small>
                        @if($settings->image)<div class="mt-2"><img src="{{ \App\Support\WebsiteMedia::url('media/about/' . basename((string)$settings->image)) }}" style="max-width:220px;max-height:140px;object-fit:contain" alt="Current Benefits photo"><label class="d-block mt-2"><input type="checkbox" name="remove_image" value="1"> Remove custom image</label></div>@endif
                    </div>
                    <div class="col-md-3"><label for="benefits-alt" class="form-label">Image alt text</label><input id="benefits-alt" name="homepage_image_alt" class="form-control" maxlength="200" value="{{ old('homepage_image_alt', $settings->homepage_image_alt ?: 'Sunnah Digital Agency team and workspace') }}"></div>
                    <div class="col-md-2"><label for="benefits-bg" class="form-label">Background color</label><input id="benefits-bg" name="homepage_bg_color" type="color" class="form-control form-control-color" value="{{ old('homepage_bg_color', $settings->homepage_bg_color ?: '#17191d') }}"></div>
                    <div class="col-md-2"><label for="benefits-accent" class="form-label">Accent color</label><input id="benefits-accent" name="homepage_accent_color" type="color" class="form-control form-control-color" value="{{ old('homepage_accent_color', $settings->homepage_accent_color ?: '#C74659') }}"></div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Benefits Settings</button>
            </form>
        </div>
    </div>

    <div class="card mb-4"><div class="card-header"><strong>2. Add a Benefit</strong></div><div class="card-body">
        <form action="{{ route('admin.benefits.items.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Benefit title</label><input name="title" maxlength="140" required class="form-control" placeholder="e.g. Personalized Strategy" value="{{ old('title') }}"></div>
                <div class="col-md-5"><label class="form-label">Short description</label><textarea name="description" rows="2" maxlength="1000" required class="form-control" placeholder="Describe a real benefit of working with your agency.">{{ old('description') }}</textarea></div>
                <div class="col-md-1"><label class="form-label">Order</label><input name="homepage_sort_order" type="number" min="0" max="100000" value="{{ old('homepage_sort_order', ($benefits->count() + 1) * 10) }}" required class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Visibility</label><select name="status" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
            </div><button class="btn btn-primary mt-3" type="submit">Add Benefit</button>
        </form>
        <p class="text-muted small mt-3 mb-0">Suggested: 4–6 genuine benefits. Only active benefits are shown. No competitor photo or invented achievements.</p>
    </div></div>

    <h2 class="h5 mb-3">3. Manage Benefits ({{ $benefits->count() }})</h2>
    @forelse($benefits as $benefit)
        <div class="card mb-3"><div class="card-body">
            <form method="POST" action="{{ route('admin.benefits.items.update', $benefit->id) }}">@csrf @method('PUT')
                <div class="row g-3 align-items-start">
                    <div class="col-md-4"><label class="form-label">Title #{{ $benefit->id }}</label><input name="title" maxlength="140" required class="form-control" value="{{ $benefit->title }}"></div>
                    <div class="col-md-5"><label class="form-label">Description</label><textarea name="description" maxlength="1000" rows="2" required class="form-control">{{ $benefit->description }}</textarea></div>
                    <div class="col-md-1"><label class="form-label">Order</label><input name="homepage_sort_order" class="form-control" type="number" min="0" max="100000" required value="{{ $benefit->homepage_sort_order }}"></div>
                    <div class="col-md-2"><label class="form-label">Visibility</label><select name="status" class="form-select"><option value="1" {{ (string)$benefit->status === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ (string)$benefit->status === '0' ? 'selected' : '' }}>Inactive</option></select></div>
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3">Save Benefit</button>
            </form>
            <form class="mt-2" method="POST" action="{{ route('admin.benefits.items.destroy', $benefit->id) }}" onsubmit="return confirm('Delete this benefit?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete Benefit</button></form>
        </div></div>
    @empty
        <div class="alert alert-info">No benefits added yet. Add your agency’s verified selling points above to display this section publicly.</div>
    @endforelse
</section>
@endsection
