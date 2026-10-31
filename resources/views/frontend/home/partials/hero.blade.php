@php
    // Keep current Banner and Hero Settings models. Nothing in the existing database is replaced.
    $heroTitle = trim((string) optional($banner)->sort_title);
    $titleParts = preg_split('/\s+/u', $heroTitle, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $accentWord = count($titleParts) > 1 ? array_pop($titleParts) : '';
    $baseTitle = count($titleParts) ? implode(' ', $titleParts) : $heroTitle;
    $fallbackImage = optional($banner)->image ? 'media/banner/' . basename($banner->image) : null;
    $heroBg = optional($heroSettings)->background_color ?: '#080A0E';
    $heroAccent = optional($heroSettings)->accent_color ?: '#F2273E';
    $heroTextColor = optional($heroSettings)->text_color ?: '#FFFFFF';
    $primaryCtaLabel = optional($heroSettings)->primary_cta_label ?: 'Book Free Consultation';
    $secondaryCtaLabel = optional($heroSettings)->secondary_cta_label ?: 'Explore Our Services';
    $primaryCtaUrl = optional($heroSettings)->primary_cta_url ?: route('frontend_About_Index');
    $secondaryCtaUrl = optional($heroSettings)->secondary_cta_url ?: route('frontend_Contact_Index');
@endphp
@push('homepage_hero_after_main')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-hero.css') }}?v=6.3">
@endpush
@push('homepage_hero_scripts')
    <script defer src="{{ asset('frontend/assets/js/sda-hero.js') }}?v=6.5"></script>
@endpush
<section class="sda-hero" aria-label="Sunnah Digital Agency introduction"
    style="--sda-hero-bg: {{ $heroBg }}; --sda-hero-accent: {{ $heroAccent }}; --sda-hero-text: {{ $heroTextColor }};">
    <div class="container sda-hero__container">
        <div class="sda-hero__content">
            {{-- Static/animated decorative dot pattern intentionally removed. Slider pagination dots remain. --}}
            @if(!empty(optional($banner)->title))
                <p class="sda-hero__eyebrow">{{ $banner->title }}</p>
            @endif
            <h1 class="sda-hero__title">{{ $baseTitle ?: 'Grow your digital presence' }}@if($accentWord !== '') <span>{{ $accentWord }}</span>@endif</h1>
            @if(!empty(optional($banner)->description))
                <p class="sda-hero__description">{{ $banner->description }}</p>
            @endif
            <div class="sda-hero__actions">
                <a class="sda-hero__button sda-hero__button--primary" href="{{ $primaryCtaUrl }}">
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="1"></rect><path d="M7 3v4M17 3v4M3 10h18M8 14h2M14 14h2M8 18h2"></path>
                    </svg>
                    <span>{{ $primaryCtaLabel }}</span>
                </a>
                <a class="sda-hero__button sda-hero__button--secondary" href="{{ $secondaryCtaUrl }}">
                    <span>{{ $secondaryCtaLabel }}</span>
                </a>
            </div>
        </div>
        <div class="sda-hero__right">
            <div class="sda-hero__carousel" data-sda-hero-carousel
                 data-autoplay="{{ optional($heroSettings)->autoplay_enabled === false ? 'false' : 'true' }}"
                 data-direction="right-to-left" data-single-replay="true"
                 role="region" aria-roledescription="carousel" aria-label="Hero artwork slideshow">
                <div class="sda-hero__stage">
                    @forelse($heroSlides as $slide)
                        <div class="sda-hero__slide {{ $loop->first ? 'is-active' : '' }}"
                             data-sda-hero-slide data-duration="{{ $slide->display_duration_ms }}"
                             aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                            <div class="sda-hero__blob" aria-hidden="true"></div>
                            @if(\App\Support\WebsiteMedia::exists($slide->background_shape_path))
                                <img class="sda-hero__shape" src="{{ \App\Support\WebsiteMedia::url($slide->background_shape_path) }}" alt="" loading="lazy">
                            @endif
                            <img class="sda-hero__image" src="{{ \App\Support\WebsiteMedia::url($slide->main_image_path) }}"
                                 alt="{{ $slide->main_image_alt }}"
                                 {{ $loop->first ? 'fetchpriority=high' : 'loading=lazy' }}>
                            <div class="sda-hero__broken" aria-hidden="true">Visual coming soon</div>
                            <div class="sda-hero__spark" aria-hidden="true">
                                <svg class="sda-hero__spark-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                    <path d="M50 0 L54.2 38.4 L75.5 19.2 L61.7 43.2 L100 50 L61.7 55.8 L78.5 80 L54.2 61.6 L50 100 L45.8 61.6 L21.5 80 L38.3 55.8 L0 50 L38.3 43.2 L24.5 19.2 L45.8 38.4 Z" fill="#FFAE98"/>
                                </svg>
                            </div>
                            @if($slide->caption_text)
                                <div class="sda-hero__caption" data-caption-animation="{{ $slide->caption_animation }}">
                                    <span class="sda-hero__caption-kicker">Sunnah Digital Agency</span>
                                    <span class="sda-hero__caption-text">{{ $slide->caption_text }}</span>
                                </div>
                            @endif
                            @foreach($slide->highlights->take(3) as $highlight)
                                <div class="sda-hero__highlight sda-hero__highlight--{{ ($loop->index % 3) + 1 }}">
                                    @if($highlight->link_url)<a href="{{ $highlight->link_url }}" class="sda-hero__highlight-inner">@else<div class="sda-hero__highlight-inner">@endif
                                        @if($highlight->icon_path)
                                            <span class="sda-hero__highlight-icon"><img src="{{ \App\Support\WebsiteMedia::url($highlight->icon_path) }}" alt="" loading="lazy"></span>
                                        @endif
                                        <div class="sda-hero__highlight-copy">
                                            <strong>{{ $highlight->title }}</strong>
                                            @if($highlight->subtitle)<small class="sda-hero__highlight-subtitle" data-sda-card-description aria-hidden="true">{{ $highlight->subtitle }}</small><span class="sda-hero__sr-only">{{ $highlight->subtitle }}</span>@endif
                                            @if($highlight->display_value)<span class="sda-hero__value">{{ $highlight->display_value }}</span>@endif
                                        </div>
                                    @if($highlight->link_url)</a>@else</div>@endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="sda-hero__slide sda-hero__slide--fallback is-active" data-sda-hero-slide
                             data-duration="7000" aria-hidden="false">
                            <div class="sda-hero__blob" aria-hidden="true"></div>
                            @if($fallbackImage)
                                <img class="sda-hero__image" src="{{ asset($fallbackImage) }}" alt="Sunnah Digital Agency" fetchpriority="high">
                            @else
                                <div class="sda-hero__empty-visual">Strategy · Creativity · Growth</div>
                            @endif
                            <div class="sda-hero__broken" aria-hidden="true">Visual coming soon</div>
                            <div class="sda-hero__spark" aria-hidden="true">
                                <svg class="sda-hero__spark-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                    <path d="M50 0 L54.2 38.4 L75.5 19.2 L61.7 43.2 L100 50 L61.7 55.8 L78.5 80 L54.2 61.6 L50 100 L45.8 61.6 L21.5 80 L38.3 55.8 L0 50 L38.3 43.2 L24.5 19.2 L45.8 38.4 Z" fill="#FFAE98"/>
                                </svg>
                            </div>
                        </div>
                    @endforelse
                </div>
                @if($heroSlides->count() > 1)
                    <div class="sda-hero__nav" aria-label="Hero slide navigation">
                        <button type="button" class="sda-hero__arrow" data-sda-hero-prev aria-label="Previous slide">←</button>
                        <div class="sda-hero__dots" aria-label="Select hero slide">
                            @foreach($heroSlides as $slide)
                                <button type="button" class="sda-hero__dot {{ $loop->first ? 'is-active' : '' }}"
                                        data-sda-hero-dot="{{ $loop->index }}" aria-label="Go to slide {{ $loop->iteration }}"
                                        aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>
                        <button type="button" class="sda-hero__arrow" data-sda-hero-next aria-label="Next slide">→</button>
                    </div>
                @endif
                <span class="visually-hidden" aria-live="polite" data-sda-hero-status></span>
            </div>
        </div>
    </div>
</section>
