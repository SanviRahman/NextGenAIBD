@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1460px">
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
        <div>
            <h1 class="h2 fw-bold">Homepage · Services Manager</h1>
            <p class="text-muted mb-0">Edit homepage section design and choose featured Services. Existing Services CRUD stays unchanged.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}#home-services" target="_blank" rel="noopener noreferrer">View Services ↗</a>
    </div>
    @if(session('services_success')) <div class="alert alert-success">{{ session('services_success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger"><strong>Check these fields:</strong>
            <ul class="mb-0">@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header"><h2 class="h5 mb-0">1. Section heading, colors and visibility</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.services.settings.update') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Small heading (optional)</label><input name="eyebrow" maxlength="100" class="form-control" value="{{ old('eyebrow', $settings->eyebrow) }}" placeholder="Our Expertise"></div>
                    <div class="col-md-4"><label class="form-label">Main heading</label><input name="heading" required maxlength="180" class="form-control" value="{{ old('heading', $settings->heading ?: 'Our Services') }}"></div>
                    <div class="col-md-4"><label class="form-label">View All button label</label><input name="button_label" required maxlength="80" class="form-control" value="{{ old('button_label', $settings->button_label ?: 'View All') }}"></div>
                    <div class="col-12"><label class="form-label">Section description</label><textarea name="description" maxlength="1200" rows="3" class="form-control">{{ old('description', $settings->description) }}</textarea></div>
                    <div class="col-md-3"><label class="form-label">Background color</label><input type="color" class="form-control form-control-color" name="background_color" value="{{ old('background_color', $settings->background_color ?: '#090C13') }}"></div>
                    <div class="col-md-3"><label class="form-label">Card background</label><input type="color" class="form-control form-control-color" name="card_color" value="{{ old('card_color', $settings->card_color ?: '#1A1D22') }}"></div>
                    <div class="col-md-2"><label class="form-label">Accent / hover color</label><input type="color" class="form-control form-control-color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?: '#C74659') }}"></div>
                    <div class="col-md-2"><label class="form-label">Max cards</label><select name="cards_limit" class="form-select">@foreach([3,6,9,12] as $n)<option value="{{ $n }}" {{ (int)old('cards_limit', $settings->cards_limit ?: 9) === $n ? 'selected':'' }}>{{ $n }} Cards</option>@endforeach</select></div>
                    <div class="col-md-2"><label class="form-label">Show section</label><select name="is_active" class="form-select"><option value="1" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 1 ? 'selected':'' }}>Enabled</option><option value="0" {{ (int)old('is_active', $settings->exists ? $settings->is_active : 1) === 0 ? 'selected':'' }}>Hidden</option></select></div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Save Section Settings</button>
            </form>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header"><h2 class="h5 mb-0">2. Featured Services &amp; card order</h2></div>
        <div class="card-body">
            <p class="text-muted mb-3">Choose active Services to appear on the homepage and assign the order (smallest first). When nothing is selected, the latest active Services will be shown automatically.</p>
            <form method="POST" action="{{ route('admin.services.featured.update') }}">
                @csrf @method('PUT')
                <div class="table-responsive"><table class="table table-hover align-middle">
                    <thead><tr><th>Show</th><th>Service</th><th>Image</th><th>Order</th><th>Manage</th></tr></thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td><input type="checkbox" name="selected[]" value="{{ $service->id }}" class="form-check-input" aria-label="Feature {{ $service->title }}" {{ $featured->has($service->id) ? 'checked':'' }}></td>
                            <td><strong>{{ $service->title }}</strong><div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags((string)$service->sort_description), 95) }}</div></td>
                            <td>@if($service->image)<img src="{{ \App\Support\WebsiteMedia::url('media/service/' . basename((string)$service->image)) }}" alt="" style="max-width:65px;max-height:65px;object-fit:contain">@endif</td>
                            <td><input type="number" min="0" max="100000" name="order[{{ $service->id }}]" value="{{ $featured->has($service->id) ? $featured->get($service->id)->sort_order : $loop->iteration * 10 }}" class="form-control" style="width:110px" aria-label="Sort order for {{ $service->title }}"></td>
                            <td><a href="{{ route('backend_Services_Index') }}" class="btn btn-sm btn-outline-secondary">Edit in Services</a></td>
                        </tr>
                        @empty<tr><td colspan="5">No active Services yet. Create them in the existing Services admin menu first.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
                <button type="submit" class="btn btn-primary mt-3">Save Featured Services</button>
            </form>
        </div>
    </div>
    <p class="text-muted small">The homepage grid uses the existing Service title, short description, image and detail URL. To change those fields, use the original <a href="{{ route('backend_Services_Index') }}">Services Manager</a>. Only active Services can be featured. Other homepage features are unaffected.</p>
</section>
@endsection
