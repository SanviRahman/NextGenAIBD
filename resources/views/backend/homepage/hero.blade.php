@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div><h1 class="h2 fw-bold mb-1">Homepage · Hero Manager</h1>
            <p class="text-muted mb-0">Only the homepage hero is managed here. Header, navbar and other sections are unchanged.</p></div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}" target="_blank" rel="noopener noreferrer">View homepage ↗</a>
    </div>
    @if(session('hero_success'))<div class="alert alert-success">{{ session('hero_success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Please correct the form:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="card mb-4">
        <div class="card-header"><h2 class="h4 mb-0">1. Hero content, buttons & colors</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.hero.settings.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-lg-6"><label class="form-label" for="hero-title">Eyebrow / Small title</label>
                        <input class="form-control" id="hero-title" name="title" maxlength="1000" required value="{{ old('title', optional($banner)->title ?: 'Sunnah Digital Agency') }}"></div>
                    <div class="col-lg-6"><label class="form-label" for="hero-heading">Large hero headline</label>
                        <input class="form-control" id="hero-heading" name="sort_title" maxlength="1000" required value="{{ old('sort_title', optional($banner)->sort_title ?: 'Grow your digital presence') }}"></div>
                    <div class="col-12"><label class="form-label" for="hero-description">Description (plain text)</label>
                        <textarea class="form-control" id="hero-description" name="description" rows="3" maxlength="5000">{{ old('description', optional($banner)->description) }}</textarea></div>
                    <div class="col-lg-3"><label class="form-label">Primary button label</label><input class="form-control" required maxlength="70" name="primary_cta_label" value="{{ old('primary_cta_label', $settings->primary_cta_label ?: 'About Us') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Primary button URL</label><input class="form-control" required name="primary_cta_url" value="{{ old('primary_cta_url', $settings->primary_cta_url ?: '/about') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Secondary button label</label><input class="form-control" required maxlength="70" name="secondary_cta_label" value="{{ old('secondary_cta_label', $settings->secondary_cta_label ?: 'Contact Us') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Secondary button URL</label><input class="form-control" required name="secondary_cta_url" value="{{ old('secondary_cta_url', $settings->secondary_cta_url ?: '/contact') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Background color</label><input class="form-control form-control-color" type="color" name="background_color" value="{{ old('background_color', $settings->background_color ?: '#111B2B') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Accent color</label><input class="form-control form-control-color" type="color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?: '#E6A468') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Heading color</label><input class="form-control form-control-color" type="color" name="text_color" value="{{ old('text_color', $settings->text_color ?: '#F8FAFC') }}"></div>
                    <div class="col-lg-3"><label class="form-label">Autoplay</label><select class="form-select" name="autoplay_enabled"><option value="1" {{ old('autoplay_enabled', $settings->exists ? (int)$settings->autoplay_enabled : 1) == 1 ? 'selected' : '' }}>Enabled</option><option value="0" {{ old('autoplay_enabled', $settings->exists ? (int)$settings->autoplay_enabled : 1) == 0 ? 'selected' : '' }}>Disabled</option></select></div>
                </div>
                <div class="mt-3"><button class="btn btn-primary" type="submit">Save hero content</button></div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h2 class="h4 mb-0">2. Add a new visual slide</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.hero.slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-lg-4"><label class="form-label">Admin name</label><input name="internal_name" required maxlength="140" class="form-control" placeholder="e.g., Digital services visual"></div>
                    <div class="col-lg-4"><label class="form-label">Main image (JPG/PNG/WebP ≤ 6 MB)</label><input type="file" accept="image/png,image/jpeg,image/webp" name="main_image" required class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Accessible image description (alt)</label><input name="main_image_alt" maxlength="220" required class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Background decorative image (optional)</label><input type="file" accept="image/png,image/jpeg,image/webp" name="background_shape" class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Caption text</label><input name="caption_text" maxlength="255" class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Caption animation</label><select name="caption_animation" class="form-select"><option value="typewriter">Typewriter — 48ms per character</option><option value="reveal">Smooth typing — 30ms per character</option><option value="none">None</option></select></div>
                    <div class="col-lg-4"><label class="form-label">Display duration (ms)</label><input type="number" name="display_duration_ms" required min="3000" max="15000" value="7000" class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Sort order</label><input type="number" name="sort_order" required min="0" value="{{ $slides->count() * 10 }}" class="form-control"></div>
                    <div class="col-lg-4"><label class="form-label">Visible</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Hidden</option></select></div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add visual slide</button>
            </form>
        </div>
    </div>

    <h2 class="h4 fw-bold mb-3">3. Edit slides and per-slide floating cards ({{ $slides->count() }})</h2>
    @forelse($slides as $slide)
        <div class="card mb-4" id="hero-slide-{{ $slide->id }}">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>Slide #{{ $slide->id }} · {{ $slide->internal_name }}</strong>
                <span class="badge bg-{{ $slide->is_active ? 'success' : 'secondary' }}">{{ $slide->is_active ? 'Active' : 'Hidden' }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3"><img src="{{ \App\Support\WebsiteMedia::url($slide->main_image_path) }}" alt="{{ $slide->main_image_alt }}" class="img-fluid rounded" style="max-height:220px;object-fit:contain;background:#19263a;width:100%"></div>
                    <div class="col-md-9">
                        <form action="{{ route('admin.hero.slides.update', $slide) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row g-3">
                                <div class="col-lg-6"><label class="form-label">Admin name</label><input name="internal_name" required maxlength="140" class="form-control" value="{{ $slide->internal_name }}"></div>
                                <div class="col-lg-6"><label class="form-label">Image alt</label><input name="main_image_alt" required maxlength="220" class="form-control" value="{{ $slide->main_image_alt }}"></div>
                                <div class="col-lg-6"><label class="form-label">Replace image (optional)</label><input type="file" name="main_image" accept="image/png,image/jpeg,image/webp" class="form-control"></div>
                                <div class="col-lg-6"><label class="form-label">Replace background (optional)</label><input type="file" name="background_shape" accept="image/png,image/jpeg,image/webp" class="form-control"></div>
                                <div class="col-lg-6"><label class="form-label">Caption</label><input name="caption_text" maxlength="255" class="form-control" value="{{ $slide->caption_text }}"></div>
                                <div class="col-lg-3"><label class="form-label">Caption effect</label><select name="caption_animation" class="form-select">@foreach(['typewriter'=>'Typewriter (48ms)','reveal'=>'Smooth typing (30ms)','none'=>'None'] as $val=>$label)<option value="{{ $val }}" {{ $slide->caption_animation === $val ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                                <div class="col-lg-3"><label class="form-label">Duration (ms)</label><input type="number" min="3000" max="15000" name="display_duration_ms" required class="form-control" value="{{ $slide->display_duration_ms }}"></div>
                                <div class="col-lg-3"><label class="form-label">Sort order</label><input type="number" min="0" name="sort_order" class="form-control" required value="{{ $slide->sort_order }}"></div>
                                <div class="col-lg-3"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1" {{ $slide->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$slide->is_active ? 'selected' : '' }}>Hidden</option></select></div>
                                <div class="col-lg-6 pt-4"><label><input type="checkbox" name="remove_background" value="1"> Remove decorative background</label></div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit">Save slide #{{ $slide->id }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.hero.slides.destroy', $slide) }}" class="mt-2" onsubmit="return confirm('Remove this slide? Its uploaded files will be retained.')">
                            @csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Delete slide</button>
                        </form>
                    </div>
                </div>
                <hr>
                <h3 class="h5">Floating cards for this slide (maximum 3 shown)</h3>
                @foreach($slide->highlights as $highlight)
                    <div class="border rounded p-3 mb-3">
                        <form method="POST" action="{{ route('admin.hero.highlights.update', $highlight) }}" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="hero_slide_id" value="{{ $slide->id }}">
                            <div class="row g-2">
                                <div class="col-md-3"><label class="form-label">Title</label><input name="title" class="form-control" required maxlength="120" value="{{ $highlight->title }}"></div>
                                <div class="col-md-3"><label class="form-label">Subtitle</label><input name="subtitle" class="form-control" maxlength="255" value="{{ $highlight->subtitle }}"></div>
                                <div class="col-md-2"><label class="form-label">Verified value</label><input name="display_value" class="form-control" maxlength="60" value="{{ $highlight->display_value }}"></div>
                                <div class="col-md-4"><label class="form-label">Optional link</label><input name="link_url" class="form-control" value="{{ $highlight->link_url }}"></div>
                                <div class="col-md-4"><label class="form-label">Replace icon image</label><input type="file" accept="image/png,image/jpeg,image/webp" name="icon_image" class="form-control"></div>
                                <div class="col-md-3"><label class="form-label">Order</label><input type="number" min="0" name="sort_order" required class="form-control" value="{{ $highlight->sort_order }}"></div>
                                <div class="col-md-3"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1" {{ $highlight->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$highlight->is_active ? 'selected' : '' }}>Hidden</option></select></div>
                                <div class="col-md-2 d-flex align-items-end"><button class="btn btn-outline-primary" type="submit">Save card</button></div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.hero.highlights.destroy', $highlight) }}" class="mt-2" onsubmit="return confirm('Delete this floating card?')">
                            @csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Delete card</button>
                        </form>
                    </div>
                @endforeach
                <div class="p-3 rounded" style="background:#f2f5fa">
                    <h4 class="h6">Add floating card to this slide</h4>
                    <form method="POST" action="{{ route('admin.hero.highlights.store') }}" enctype="multipart/form-data">
                        @csrf<input type="hidden" name="hero_slide_id" value="{{ $slide->id }}">
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label">Title</label><input name="title" required maxlength="120" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Subtitle</label><input name="subtitle" maxlength="255" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Verified value (optional)</label><input name="display_value" maxlength="60" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Icon image</label><input type="file" accept="image/png,image/jpeg,image/webp" name="icon_image" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Link (optional)</label><input name="link_url" class="form-control"></div>
                            <div class="col-md-2"><label class="form-label">Sort order</label><input type="number" name="sort_order" value="{{ $slide->highlights->count() * 10 }}" min="0" required class="form-control"></div>
                            <div class="col-md-2"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Hidden</option></select></div>
                        </div>
                        <button class="btn btn-secondary mt-2" type="submit">Add card</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No slides yet. The homepage shows its existing banner image until you add the first hero slide.</div>
    @endforelse
    <p class="text-muted">Recommended: 2–4 original visual slides, 7000–9000ms visible per slide, 1800ms smooth scene transition, and 0–3 floating cards per slide. The main image + its floating cards move together as one scene. With only one visual slide, the same scene will replay automatically; add more slides when you want different main artwork/content.</p>
</section>
@endsection
