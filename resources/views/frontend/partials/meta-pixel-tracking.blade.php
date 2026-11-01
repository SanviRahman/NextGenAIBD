@if(count($activeMetaPixelIds) > 0)
    <div id="nextgen-meta-consent" class="nextgen-meta-consent" role="dialog" aria-live="polite" aria-label="Marketing cookie preferences" {{ $metaConsentState === 'unknown' ? '' : 'hidden' }}>
        <div class="nextgen-meta-consent__copy">
            <strong>Help us improve your experience</strong>
            <p>We use marketing cookies to measure campaigns and improve relevant ads. You can accept or reject Meta marketing tracking.</p>
        </div>
        <div class="nextgen-meta-consent__actions">
            <button type="button" id="nextgen-meta-reject" class="nextgen-meta-consent__button nextgen-meta-consent__button--secondary">Reject</button>
            <button type="button" id="nextgen-meta-accept" class="nextgen-meta-consent__button nextgen-meta-consent__button--primary">Accept</button>
        </div>
    </div>
    <button type="button" id="nextgen-meta-consent-manage" class="nextgen-meta-consent-manage">Privacy choices</button>
@endif

<script src="{{ asset('frontend/assets/js/meta-tracking.js') }}?v=1.1" defer></script>
