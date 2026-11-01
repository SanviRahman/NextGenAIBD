@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1480px">
    <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4 flex-wrap">
        <div>
            <h1 class="h2 fw-bold mb-1">Meta Pixel &amp; Conversions API</h1>
            <p class="text-muted mb-0">Manage multiple Meta Pixels, securely store CAPI tokens, and inspect browser/server event delivery.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('home_Index') }}" target="_blank" rel="noopener noreferrer">View website ↗</a>
    </div>

    @if(session('meta_pixel_success'))
        <div class="alert alert-success" role="status">{{ session('meta_pixel_success') }}</div>
    @endif
    @if(session('meta_pixel_error'))
        <div class="alert alert-danger" role="alert">{{ session('meta_pixel_error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="alert alert-info" role="note">
        <strong>Safe script handling:</strong> Paste the complete official Meta Pixel base code as plain text. The application extracts its Pixel ID and renders a clean, canonical Pixel snippet. The pasted JavaScript is never executed directly, preventing arbitrary script injection.
        <br><strong>Privacy:</strong> Pixel and CAPI events are held until a visitor accepts marketing cookies. CAPI access tokens are encrypted at rest using your Laravel APP_KEY.
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h5 mb-0">1. Add Meta Pixel scripts</h2>
            <span class="badge bg-primary">Add multiple pixels</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.meta-pixel.store') }}" id="meta-pixel-create-form">
                @csrf
                <div id="meta-pixel-new-rows">
                    <div class="meta-pixel-new-row border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h6 mb-0">New Pixel <span class="meta-pixel-row-number">1</span></h3>
                            <button type="button" class="btn btn-sm btn-outline-danger meta-pixel-remove-row" hidden>Remove row</button>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label class="form-label">Integration name</label>
                                <input class="form-control" name="integrations[0][name]" maxlength="120" required placeholder="e.g. NextGenAIBD Main Website" value="{{ old('integrations.0.name') }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">CAPI access token</label>
                                <input class="form-control" type="password" name="integrations[0][access_token]" minlength="20" maxlength="5000" autocomplete="new-password" required placeholder="Paste the Pixel's Conversions API token">
                                <small class="text-muted">Stored encrypted. Never shown again after saving.</small>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Test Event Code (optional)</label>
                                <input class="form-control" name="integrations[0][test_event_code]" maxlength="100" placeholder="From Events Manager → Test Events">
                                <small class="text-muted">Used only by Send Test Event; live conversions remain production events.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Full Meta Pixel script</label>
                                <textarea class="form-control font-monospace" name="integrations[0][pixel_script]" rows="7" maxlength="20000" required spellcheck="false" placeholder="Paste the complete official Meta Pixel base code here...">{{ old('integrations.0.pixel_script') }}</textarea>
                                <small class="text-muted">One official fbq('init', 'PIXEL_ID') per field. Use Add More for another Pixel. Maximum 20 KB per script.</small>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="integrations[0][is_active]" value="0">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="integrations[0][is_active]" value="1" id="meta-pixel-active-new-0" checked>
                                    <label class="form-check-label" for="meta-pixel-active-new-0">Enable this Pixel after saving</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-primary" id="meta-pixel-add-row"><i class="fa-solid fa-plus me-1"></i> Add More</button>
                    <button type="submit" class="btn btn-primary">Save Pixel Integrations</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h5 mb-0">2. Configured Pixels ({{ $integrations->count() }})</h2>
            <span class="small text-muted">Only active integrations load on the public website.</span>
        </div>
        <div class="card-body">
            @forelse($integrations as $integration)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h3 class="h6 mb-1">{{ $integration->name }}</h3>
                            <div class="small text-muted">Pixel ID: <code>{{ $integration->pixel_id }}</code></div>
                        </div>
                        @if($integration->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.meta-pixel.update', $integration) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label class="form-label">Integration name</label>
                                <input class="form-control" name="name" maxlength="120" required value="{{ $integration->name }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Replace CAPI access token (optional)</label>
                                <input class="form-control" type="password" name="access_token" minlength="20" maxlength="5000" autocomplete="new-password" placeholder="Leave blank to keep current token">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Test Event Code (optional)</label>
                                <input class="form-control" name="test_event_code" maxlength="100" value="{{ $integration->test_event_code }}" placeholder="Events Manager test code">
                                <small class="text-muted">Only used by the Send Test Event button.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Full Meta Pixel script</label>
                                <textarea class="form-control font-monospace" name="pixel_script" rows="6" maxlength="20000" required spellcheck="false">{{ $integration->pixel_script }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="is_active" value="0">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="meta-pixel-active-{{ $integration->id }}" {{ $integration->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="meta-pixel-active-{{ $integration->id }}">Active on website</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">Update Integration</button>
                        </div>
                    </form>

                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <form method="POST" action="{{ route('admin.meta-pixel.test', $integration) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm" {{ blank($integration->test_event_code) ? 'disabled' : '' }}>Send Test Event</button>
                        </form>
                        <form method="POST" action="{{ route('admin.meta-pixel.destroy', $integration) }}" onsubmit="return confirm('Delete this Meta Pixel integration?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary mb-0">No Meta Pixel integrations configured yet. Add your first official Pixel script above.</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="h5 mb-0">3. Events tracked automatically</h2></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><strong>GTM lifecycle</strong><p class="small text-muted mb-0"><code>gtm.js</code>, <code>gtm.dom</code>, <code>gtm.load</code> are pushed into <code>dataLayer</code>. Meta receives a lifecycle event only if consent is already granted when it occurs.</p></div>
                <div class="col-md-4"><strong>Website events</strong><p class="small text-muted mb-0">PageView, ViewContent, Contact, CTA_Click, OutboundClick, FormStart, FormSubmit and ScrollDepth milestones.</p></div>
                <div class="col-md-4"><strong>Confirmed conversions</strong><p class="small text-muted mb-0">A successfully saved consultation sends a server-side Lead. Browser and server share the same event ID for deduplication.</p></div>
            </div>
            <hr>
            <p class="small text-muted mb-0">For any custom interaction, add <code>data-meta-event="YourEventName"</code> to its HTML element. The tracking helper is also available as <code>window.nextGenTrack('YourEventName', { content_name: '...' })</code>.</p>
        </div>
    </div>
</section>

<template id="meta-pixel-row-template">
    <div class="meta-pixel-new-row border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h6 mb-0">New Pixel <span class="meta-pixel-row-number">__INDEX_LABEL__</span></h3>
            <button type="button" class="btn btn-sm btn-outline-danger meta-pixel-remove-row">Remove row</button>
        </div>
        <div class="row g-3">
            <div class="col-lg-4"><label class="form-label">Integration name</label><input class="form-control" name="integrations[__INDEX__][name]" maxlength="120" required placeholder="e.g. NextGenAIBD Campaign Pixel"></div>
            <div class="col-lg-4"><label class="form-label">CAPI access token</label><input class="form-control" type="password" name="integrations[__INDEX__][access_token]" minlength="20" maxlength="5000" autocomplete="new-password" required placeholder="Paste the CAPI access token"><small class="text-muted">Encrypted at rest.</small></div>
            <div class="col-lg-4"><label class="form-label">Test Event Code (optional)</label><input class="form-control" name="integrations[__INDEX__][test_event_code]" maxlength="100" placeholder="Events Manager test code"><small class="text-muted">Only used by the Send Test Event button.</small></div>
            <div class="col-12"><label class="form-label">Full Meta Pixel script</label><textarea class="form-control font-monospace" name="integrations[__INDEX__][pixel_script]" rows="7" maxlength="20000" required spellcheck="false" placeholder="Paste complete official Meta Pixel base code..."></textarea><small class="text-muted">Exactly one fbq init Pixel ID in this field.</small></div>
            <div class="col-md-4"><input type="hidden" name="integrations[__INDEX__][is_active]" value="0"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="integrations[__INDEX__][is_active]" value="1" id="meta-pixel-active-__INDEX__" checked><label class="form-check-label" for="meta-pixel-active-__INDEX__">Enable this Pixel after saving</label></div></div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('meta-pixel-new-rows');
    const template = document.getElementById('meta-pixel-row-template');
    const addButton = document.getElementById('meta-pixel-add-row');
    if (!container || !template || !addButton) return;

    let nextIndex = 1;
    const maxRows = 10;

    function refreshRows() {
        const rows = Array.from(container.querySelectorAll('.meta-pixel-new-row'));
        rows.forEach((row, index) => {
            const remove = row.querySelector('.meta-pixel-remove-row');
            const label = row.querySelector('.meta-pixel-row-number');
            if (label) label.textContent = String(index + 1);
            if (remove) remove.hidden = rows.length === 1;
        });
        addButton.disabled = rows.length >= maxRows;
    }

    addButton.addEventListener('click', function () {
        const count = container.querySelectorAll('.meta-pixel-new-row').length;
        if (count >= maxRows) return;
        const html = template.innerHTML
            .replaceAll('__INDEX__', String(nextIndex))
            .replaceAll('__INDEX_LABEL__', String(count + 1));
        container.insertAdjacentHTML('beforeend', html);
        nextIndex += 1;
        refreshRows();
    });

    container.addEventListener('click', function (event) {
        const remove = event.target.closest('.meta-pixel-remove-row');
        if (!remove) return;
        const rows = container.querySelectorAll('.meta-pixel-new-row');
        if (rows.length <= 1) return;
        remove.closest('.meta-pixel-new-row').remove();
        refreshRows();
    });

    refreshRows();
});
</script>
@endsection
