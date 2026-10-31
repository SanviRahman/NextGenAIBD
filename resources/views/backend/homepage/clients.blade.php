@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start border-bottom mb-4 pb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Homepage · Our Clients Showcase</h1>
            <p class="text-muted mb-0">Reuses existing <code>abouts</code> records. Original About Us, Benefits and Projects remain unchanged.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#our-clients" target="_blank" rel="noopener noreferrer">View Clients ↗</a>
    </div>

    <div class="alert alert-info">Only publish actual clients whose logos you are authorized to show. A logo is public only when <strong>Active</strong> and <strong>Permission Confirmed</strong>. Without approved logos, this section is hidden on the homepage.</div>
    @if(session('clients_success'))<div class="alert alert-success">{{ session('clients_success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Please check the form:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><strong>1. Section Settings</strong></div>
        <div class="card-body">
            <form action="{{ route('admin.clients.settings.update') }}" method="POST">@csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8"><label for="sda-clients-title" class="form-label">Main heading</label><input id="sda-clients-title" name="title" maxlength="180" required class="form-control" value="{{ old('title', $settings->title ?: 'Growing Together with Our Clients') }}"></div>
                    <div class="col-md-4"><label for="sda-clients-visible" class="form-label">Section visibility</label><select id="sda-clients-visible" name="status" class="form-select"><option value="1" {{ (string)old('status', $settings->exists ? $settings->status : '1') === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ (string)old('status', $settings->exists ? $settings->status : '1') === '0' ? 'selected' : '' }}>Hidden</option></select></div>
                    <div class="col-md-8"><label for="sda-clients-intro" class="form-label">Description (optional)</label><textarea id="sda-clients-intro" name="description" rows="2" maxlength="800" class="form-control">{{ old('description', $settings->description) }}</textarea></div>
                    <div class="col-md-2"><label for="sda-clients-bg" class="form-label">Background color</label><input id="sda-clients-bg" type="color" name="homepage_bg_color" class="form-control form-control-color" value="{{ old('homepage_bg_color', $settings->homepage_bg_color ?: '#101821') }}"></div>
                    <div class="col-md-2"><label for="sda-clients-accent" class="form-label">Accent color</label><input id="sda-clients-accent" type="color" name="homepage_accent_color" class="form-control form-control-color" value="{{ old('homepage_accent_color', $settings->homepage_accent_color ?: '#C74659') }}"></div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Save Client Section Settings</button>
            </form>
        </div>
    </div>

    <div class="card mb-4"><div class="card-header"><strong>2. Add an Approved Client Logo</strong></div><div class="card-body">
        <form action="{{ route('admin.clients.logos.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label" for="client-name">Client / brand name</label><input id="client-name" name="title" class="form-control" maxlength="180" value="{{ old('title') }}" placeholder="Actual client company" required></div>
                <div class="col-md-4"><label class="form-label" for="client-logo">Approved client logo (PNG/JPG/WebP ≤3MB)</label><input id="client-logo" name="image" type="file" class="form-control" accept=".png,.jpg,.jpeg,.webp" required></div>
                <div class="col-md-4"><label class="form-label" for="client-alt">Logo alt text (optional)</label><input id="client-alt" name="homepage_image_alt" class="form-control" maxlength="200" placeholder="Client company logo" value="{{ old('homepage_image_alt') }}"></div>
                <div class="col-md-5"><label class="form-label" for="client-url">Client's public HTTPS website (optional)</label><input id="client-url" type="url" name="homepage_client_url" class="form-control" maxlength="2048" placeholder="https://..." value="{{ old('homepage_client_url') }}"></div>
                <div class="col-md-3"><label class="form-label" for="client-project">Associated existing project (optional)</label><select id="client-project" name="homepage_client_project_id" class="form-select"><option value="">No project link</option>@foreach($projects as $project)<option value="{{ $project->id }}" {{ (string)old('homepage_client_project_id') === (string)$project->id ? 'selected' : '' }}>{{ $project->title }} (#{{ $project->id }})</option>@endforeach</select><small class="text-muted">Project link takes priority over external URL.</small></div>
                <div class="col-md-2"><label class="form-label" for="client-order">Sort order</label><input id="client-order" type="number" min="0" max="100000" name="homepage_sort_order" value="{{ old('homepage_sort_order', ($clients->count() + 1) * 10) }}" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label" for="client-status">Visibility</label><select id="client-status" name="status" class="form-select"><option value="0" selected>Inactive</option><option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option></select></div>
                <div class="col-12"><label class="form-check-label"><input type="checkbox" name="homepage_client_permission_confirmed" value="1" class="form-check-input me-2" required {{ old('homepage_client_permission_confirmed') ? 'checked' : '' }}> I confirm this is a genuine client and we have permission to display this logo publicly.</label></div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Client Logo</button>
        </form>
        <p class="text-muted small mt-3 mb-0">The first four approved logos appear in a larger top row; additional logos appear in a compact responsive grid. Order controls placement.</p>
    </div></div>

    <h2 class="h5 mb-3">3. Manage Client Logos ({{ $clients->count() }})</h2>
    @forelse($clients as $client)
    <div class="card mb-3"><div class="card-body">
        <form action="{{ route('admin.clients.logos.update', $client->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="row g-3 align-items-start">
                <div class="col-md-2">
                    @if($client->image)<img src="{{ \App\Support\WebsiteMedia::url('media/homepage/clients/' . basename((string) $client->image)) }}" alt="{{ $client->homepage_image_alt ?: $client->title }}" style="width:100%;max-width:190px;max-height:120px;object-fit:contain;background:#f1f4f8;border:1px solid #e5eaf0;padding:12px;border-radius:8px;">@endif
                    <div class="small text-muted mt-2">Logo #{{ $client->id }}</div>
                </div>
                <div class="col-md-5"><label class="form-label">Client name</label><input name="title" required maxlength="180" class="form-control" value="{{ $client->title }}"><label class="form-label mt-2">External HTTPS Link (optional)</label><input type="url" name="homepage_client_url" maxlength="2048" class="form-control" value="{{ $client->homepage_client_url }}" placeholder="https://..."></div>
                <div class="col-md-5"><label class="form-label">Replace logo (optional, ≤3MB)</label><input type="file" accept=".png,.jpg,.jpeg,.webp" name="image" class="form-control"><label class="form-label mt-2">Alt text</label><input name="homepage_image_alt" maxlength="200" class="form-control" value="{{ $client->homepage_image_alt }}"></div>
                <div class="col-md-4"><label class="form-label">Related project</label><select name="homepage_client_project_id" class="form-select"><option value="">No project link</option>@foreach($projects as $project)<option value="{{ $project->id }}" {{ (string)$client->homepage_client_project_id === (string)$project->id ? 'selected' : '' }}>{{ $project->title }} (#{{ $project->id }})</option>@endforeach</select></div>
                <div class="col-md-2"><label class="form-label">Order</label><input type="number" min="0" max="100000" name="homepage_sort_order" value="{{ $client->homepage_sort_order }}" required class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Visibility</label><select name="status" class="form-select"><option value="0" {{ (string)$client->status === '0' ? 'selected' : '' }}>Inactive</option><option value="1" {{ (string)$client->status === '1' ? 'selected' : '' }}>Active</option></select></div>
                <div class="col-md-4 d-flex align-items-end"><label class="form-check-label"><input name="homepage_client_permission_confirmed" type="checkbox" value="1" class="form-check-input me-2" {{ $client->homepage_client_permission_confirmed ? 'checked' : '' }}> Permission confirmed</label></div>
            </div>
            <button class="btn btn-outline-primary mt-3" type="submit">Save Client #{{ $client->id }}</button>
        </form>
        <form method="POST" class="mt-2" action="{{ route('admin.clients.logos.destroy', $client->id) }}" onsubmit="return confirm('Delete this client logo?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete Logo</button></form>
    </div></div>
    @empty
    <div class="alert alert-secondary">No logos yet. Add your first genuine, permission-confirmed client logo above.</div>
    @endforelse
</section>
@endsection
