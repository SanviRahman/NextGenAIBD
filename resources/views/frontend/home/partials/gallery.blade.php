@php
    $galleryVisible = !$gallerySettings->exists || (string)$gallerySettings->status === '1';
    $galleryHeading = $gallerySettings->title ?: 'Memories & Moments';
    $galleryBg = $gallerySettings->homepage_bg_color ?: '#0B0F16';
    $galleryAccent = $gallerySettings->homepage_accent_color ?: '#C74659';
@endphp
@if($galleryVisible && $homepageGallery->isNotEmpty())
    @once
        @push('homepage_hero_after_main')
            <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-gallery.css') }}?v=1.1">
        @endpush
        @push('homepage_hero_scripts')
            <script src="{{ asset('frontend/assets/js/sda-gallery.js') }}?v=1.0" defer></script>
        @endpush
    @endonce
    <section id="memories-moments" class="sda-gallery" data-sda-gallery aria-labelledby="sda-gallery-heading"
             style="--sda-gallery-bg:{{ $galleryBg }};--sda-gallery-accent:{{ $galleryAccent }}">
        <div class="sda-gallery__container">
            <header class="sda-gallery__head">
                <div>
                    <span class="sda-gallery__eyebrow">OUR STORY IN PICTURES</span>
                    <h2 id="sda-gallery-heading">{{ $galleryHeading }}</h2>
                    @if($gallerySettings->description)<p>{{ $gallerySettings->description }}</p>@endif
                </div>
                @if($homepageGallery->count() > 18)
                    <button class="sda-gallery__all" type="button" data-sda-gallery-toggle aria-expanded="false" aria-controls="sda-gallery-grid">VIEW ALL <span aria-hidden="true">↗</span></button>
                @endif
            </header>
            <div id="sda-gallery-grid" class="sda-gallery__grid">
                @foreach($homepageGallery as $photo)
                    <figure class="sda-gallery__tile sda-gallery__tile--{{ in_array($photo->homepage_gallery_size_variant, ['wide', 'tall']) ? $photo->homepage_gallery_size_variant : 'regular' }} {{ $loop->index >= 18 ? 'sda-gallery__tile--extra' : '' }}"
                            data-sda-gallery-tile style="--sda-gallery-index: {{ min($loop->index, 6) }}">
                        <button type="button" data-sda-gallery-open class="sda-gallery__open" aria-label="View photo: {{ $photo->homepage_image_alt }}">
                            <img src="{{ \App\Support\WebsiteMedia::url('media/homepage/gallery/' . basename((string) $photo->image)) }}" alt="{{ $photo->homepage_image_alt }}" width="640" height="420" loading="lazy" decoding="async">
                            <span class="sda-gallery__overlay" aria-hidden="true"></span>
                            <span class="sda-gallery__caption">{{ $photo->title ?: $photo->homepage_image_alt }}</span>
                            <span class="sda-gallery__expand" aria-hidden="true">↗</span>
                        </button>
                    </figure>
                @endforeach
            </div>
        </div>
        <div class="sda-gallery__lightbox" data-sda-gallery-lightbox hidden>
            <div class="sda-gallery__scrim" data-sda-gallery-close></div>
            <div class="sda-gallery__dialog" role="dialog" aria-modal="true" aria-label="Photo gallery viewer" tabindex="-1">
                <button class="sda-gallery__close" type="button" aria-label="Close photo viewer" data-sda-gallery-close>×</button>
                <button class="sda-gallery__previous" type="button" aria-label="Previous photo" data-sda-gallery-prev>‹</button>
                <div class="sda-gallery__stage"><img data-sda-gallery-image src="" alt=""><p data-sda-gallery-caption></p><span class="sda-gallery__counter" data-sda-gallery-count></span></div>
                <button class="sda-gallery__next" type="button" aria-label="Next photo" data-sda-gallery-next>›</button>
            </div>
        </div>
    </section>
@endif
