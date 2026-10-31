@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1460px">
    <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4">
        <div><h1 class="h2 fw-bold">Homepage · Spotlight Manager</h1>
            <p class="text-muted mb-0">Manage logo carousel, muted video preview and click-to-play full video. Hero and other sections are unchanged.</p></div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#spotlight" target="_blank" rel="noopener noreferrer">View Spotlight ↗</a>
    </div>
    @if(session('spotlight_success'))<div class="alert alert-success">{{ session('spotlight_success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><strong>Check these fields:</strong><ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>@endif

    <div class="card mb-4"><div class="card-header"><h2 class="h5 mb-0">1. Section heading, colors and autoplay</h2></div>
        <div class="card-body"><form method="POST" action="{{ route('admin.spotlight.settings.update') }}">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-lg-4"><label class="form-label">Section heading</label><input class="form-control" name="heading" maxlength="180" required value="{{ old('heading', $settings->heading ?: 'In the Spotlight') }}"></div>
                <div class="col-lg-4"><label class="form-label">Small heading (optional)</label><input class="form-control" name="eyebrow" maxlength="100" value="{{ old('eyebrow', $settings->eyebrow) }}"></div>
                <div class="col-lg-4"><label class="form-label">Logo scroll duration in seconds</label><input class="form-control" type="number" min="18" max="90" required name="logo_scroll_seconds" value="{{ old('logo_scroll_seconds', $settings->logo_scroll_seconds ?: 32) }}"></div>
                <div class="col-12"><label class="form-label">Description (optional)</label><textarea class="form-control" name="description" rows="2" maxlength="1000">{{ old('description', $settings->description) }}</textarea></div>
                <div class="col-md-2"><label class="form-label">Background color</label><input class="form-control form-control-color" type="color" name="background_color" value="{{ old('background_color', $settings->background_color ?: '#0E1724') }}"></div>
                <div class="col-md-2"><label class="form-label">Accent color</label><input class="form-control form-control-color" type="color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?: '#F15B45') }}"></div>
                @foreach(['logo_autoplay'=>'Logo autoplay','video_autoplay'=>'Muted preview autoplay','is_active'=>'Show Spotlight section'] as $field => $label)
                <div class="col-md-2"><label class="form-label">{{ $label }}</label><select name="{{ $field }}" class="form-select">
                    <option value="1" {{ (int)old($field, $settings->exists ? $settings->$field : true) === 1 ? 'selected' : '' }}>Enabled</option>
                    <option value="0" {{ (int)old($field, $settings->exists ? $settings->$field : true) === 0 ? 'selected' : '' }}>Disabled</option>
                </select></div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Spotlight settings</button>
        </form></div></div>

    <div class="card mb-4"><div class="card-header"><h2 class="h5 mb-0">2. Add Spotlight media logo</h2></div>
        <div class="card-body"><p class="text-muted">Only upload logos of real media mentions or approved partners. Never copy IMBD's media logos or imply endorsements that do not exist.</p>
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.spotlight.mentions.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Name</label><input name="name" required maxlength="160" class="form-control" placeholder="e.g. Approved media name"></div>
                <div class="col-md-3"><label class="form-label">Logo image (≤4MB)</label><input type="file" name="logo" required accept="image/jpeg,image/png,image/webp" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Alt text (optional)</label><input name="alt_text" maxlength="200" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Verified external HTTPS link (optional)</label><input name="website_url" type="url" class="form-control" placeholder="https://..."></div>
                <div class="col-md-3"><label class="form-label">Sort order</label><input name="sort_order" type="number" min="0" value="{{ $mentions->count()*10 }}" required class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Hidden</option></select></div>
            </div><button class="btn btn-primary mt-3">Add logo</button>
        </form></div></div>

    <h2 class="h5 fw-bold mb-3">3. Manage logos ({{ $mentions->count() }})</h2>
    @forelse($mentions as $mention)
        <div class="card mb-3"><div class="card-body"><div class="row g-3 align-items-start">
            <div class="col-md-2"><img class="img-fluid rounded p-2" src="{{ \App\Support\WebsiteMedia::url($mention->logo_path) }}" alt="{{ $mention->alt_text ?: $mention->name }}" style="max-height:90px;background:#182536;width:100%;object-fit:contain"></div>
            <div class="col-md-10"><form enctype="multipart/form-data" method="POST" action="{{ route('admin.spotlight.mentions.update', $mention) }}">@csrf @method('PUT')
                <div class="row g-2">
                    <div class="col-md-3"><label class="form-label">Name</label><input required name="name" maxlength="160" class="form-control" value="{{ $mention->name }}"></div>
                    <div class="col-md-3"><label class="form-label">Replace logo</label><input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp"></div>
                    <div class="col-md-3"><label class="form-label">Alt</label><input name="alt_text" class="form-control" maxlength="200" value="{{ $mention->alt_text }}"></div>
                    <div class="col-md-3"><label class="form-label">HTTPS link</label><input name="website_url" class="form-control" type="url" value="{{ $mention->website_url }}"></div>
                    <div class="col-md-3"><label class="form-label">Order</label><input required type="number" min="0" name="sort_order" class="form-control" value="{{ $mention->sort_order }}"></div>
                    <div class="col-md-3"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1" {{ $mention->is_active ? 'selected':'' }}>Active</option><option value="0" {{ !$mention->is_active ? 'selected':'' }}>Hidden</option></select></div>
                </div><button type="submit" class="btn btn-outline-primary mt-2">Save logo</button>
            </form>
            <form method="POST" action="{{ route('admin.spotlight.mentions.destroy', $mention) }}" class="mt-2" onsubmit="return confirm('Remove this logo record? Uploaded file is retained.')">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm">Delete logo</button></form>
            </div>
        </div></div></div>
    @empty <p class="alert alert-info">No logos added. Carousel will be hidden until you add active logos.</p> @endforelse

    <div class="card mt-4 mb-4"><div class="card-header"><h2 class="h5 mb-0">4. Add Featured Spotlight Video</h2></div><div class="card-body">
        <p class="text-muted">Poster required. Optional muted MP4/WebM preview (8–20s recommended). If preview is omitted and provider is YouTube/Vimeo, a muted preview embed will be attempted. Full playback opens only after click.</p>
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.spotlight.videos.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Video title</label><input class="form-control" name="title" maxlength="180" required></div>
                <div class="col-md-4"><label class="form-label">Poster JPG/PNG/WebP ≤6MB</label><input name="poster" type="file" accept="image/jpeg,image/png,image/webp" required class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Background preview MP4/WebM ≤30MB (optional)</label><input name="preview_video" type="file" accept="video/mp4,video/webm" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Full-video provider</label><select name="full_video_provider" class="form-select"><option value="youtube">YouTube</option><option value="vimeo">Vimeo</option><option value="upload">Own uploaded video</option></select></div>
                <div class="col-md-4"><label class="form-label">Full YouTube/Vimeo video link (if provider uses link)</label><input name="full_video_url" maxlength="2048" placeholder="https://www.youtube.com/watch?v=..." class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Full video upload ≤64MB (if provider=upload)</label><input type="file" name="full_video_upload" accept="video/mp4,video/webm" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Caption (optional)</label><textarea name="caption" maxlength="2000" class="form-control" rows="2"></textarea></div>
                <div class="col-md-3"><label class="form-label">Sort order</label><input name="sort_order" type="number" min="0" value="{{ $videos->count()*10 }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Hidden</option></select></div>
            </div><button type="submit" class="btn btn-primary mt-3">Add video</button>
        </form></div></div>

    <h2 class="h5 fw-bold mb-3">5. Manage videos ({{ $videos->count() }})</h2>
    <p class="text-muted">The first active video ordered by Sort Order/ID is shown on Homepage. Others remain available in the Admin Manager.</p>
    @forelse($videos as $video)
    <div class="card mb-4"><div class="card-header"><strong>#{{ $video->id }} — {{ $video->title }}</strong> <span class="text-muted">({{ $video->full_video_provider }})</span></div>
    <div class="card-body"><div class="row g-3"><div class="col-md-3"><img src="{{ \App\Support\WebsiteMedia::url($video->poster_path) }}" alt="{{ $video->title }}" class="img-fluid rounded" style="max-height:180px;width:100%;object-fit:cover"></div>
        <div class="col-md-9"><form method="POST" enctype="multipart/form-data" action="{{ route('admin.spotlight.videos.update', $video) }}">@csrf @method('PUT')
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label">Title</label><input required class="form-control" name="title" maxlength="180" value="{{ $video->title }}"></div>
                <div class="col-md-4"><label class="form-label">Replace poster</label><input type="file" name="poster" accept="image/jpeg,image/png,image/webp" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Replace muted preview</label><input type="file" name="preview_video" accept="video/mp4,video/webm" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Full-video provider</label><select name="full_video_provider" class="form-select">@foreach(['youtube'=>'YouTube','vimeo'=>'Vimeo','upload'=>'Own upload'] as $k=>$text)<option value="{{ $k }}" {{ $video->full_video_provider===$k?'selected':'' }}>{{ $text }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Full video URL (YouTube/Vimeo)</label><input name="full_video_url" class="form-control" maxlength="2048" value="{{ $video->full_video_provider==='upload' ? '' : $video->full_video_url }}"></div>
                <div class="col-md-4"><label class="form-label">Replace full upload (if upload)</label><input type="file" name="full_video_upload" accept="video/mp4,video/webm" class="form-control"></div>
                <div class="col-md-5"><label class="form-label">Caption</label><textarea class="form-control" name="caption" rows="2" maxlength="2000">{{ $video->caption }}</textarea></div>
                <div class="col-md-2"><label class="form-label">Order</label><input name="sort_order" type="number" min="0" class="form-control" required value="{{ $video->sort_order }}"></div>
                <div class="col-md-2"><label class="form-label">Visibility</label><select name="is_active" class="form-select"><option value="1" {{ $video->is_active?'selected':'' }}>Active</option><option value="0" {{ !$video->is_active?'selected':'' }}>Hidden</option></select></div>
                <div class="col-md-3 pt-4"><label class="form-check-label"><input type="checkbox" name="remove_preview" value="1"> Remove preview file</label></div>
            </div><button type="submit" class="btn btn-outline-primary mt-3">Save video</button>
        </form><form method="POST" action="{{ route('admin.spotlight.videos.destroy', $video) }}" class="mt-2" onsubmit="return confirm('Remove this video record? Files will be retained.')">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm">Delete video</button></form>
        </div></div></div>
    </div>
    @empty <p class="alert alert-info">No Spotlight video added. The video stage stays hidden until a valid active video is created.</p> @endforelse
</section>
@endsection
