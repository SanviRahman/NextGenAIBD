@php
    $spotlightBg = $spotlightSettings->background_color ?: '#0E1724';
    $spotlightAccent = $spotlightSettings->accent_color ?: '#F15B45';
    $spotlightPreviewEmbed = $spotlightVideo && !$spotlightVideo->preview_video_path
        ? \App\Support\HomepageSpotlightUrl::mutedPreviewUrl($spotlightVideo->full_video_provider, $spotlightVideo->full_video_url)
        : null;
    $spotlightFull = $spotlightVideo
        ? ($spotlightVideo->full_video_provider === 'upload'
            ? (\App\Support\WebsiteMedia::exists($spotlightVideo->full_video_url) ? asset($spotlightVideo->full_video_url) : null)
            : \App\Support\HomepageSpotlightUrl::fullEmbedUrl($spotlightVideo->full_video_provider, $spotlightVideo->full_video_url))
        : null;
@endphp
@push('homepage_hero_after_main')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-spotlight.css') }}?v=1.3">
@endpush
@push('homepage_hero_scripts')
    <script defer src="{{ asset('frontend/assets/js/sda-spotlight.js') }}?v=1.2"></script>
@endpush
@if(($spotlightSettings->exists ? $spotlightSettings->is_active : true) && ($spotlightMentions->isNotEmpty() || $spotlightVideo))
<section class="sda-spotlight" id="spotlight" aria-labelledby="sda-spotlight-heading"
         style="--sda-spotlight-bg: {{ $spotlightBg }}; --sda-spotlight-accent: {{ $spotlightAccent }};"
         data-sda-spotlight>
    <div class="sda-spotlight__container">
        <div class="sda-spotlight__intro">
            @if($spotlightSettings->eyebrow)<span class="sda-spotlight__eyebrow">{{ $spotlightSettings->eyebrow }}</span>@endif
            <h2 id="sda-spotlight-heading" class="sda-spotlight__title">{{ $spotlightSettings->heading ?: 'In the Spotlight' }}<span aria-hidden="true"></span></h2>
            @if($spotlightSettings->description)<p class="sda-spotlight__description">{{ $spotlightSettings->description }}</p>@endif
        </div>
        @if($spotlightMentions->isNotEmpty())
            <div class="sda-spotlight__marquee" data-sda-marquee
                 data-scroll-seconds="{{ (int) ($spotlightSettings->logo_scroll_seconds ?: 32) }}"
                 data-autoplay="{{ (!$spotlightSettings->exists || $spotlightSettings->logo_autoplay) ? 'true' : 'false' }}">
                <button class="sda-spotlight__arrow" data-sda-marquee-prev type="button" aria-label="Previous spotlight logo">&#8592;</button>
                <div class="sda-spotlight__viewport" data-sda-marquee-viewport role="region" aria-label="Featured press mentions and partnerships">
                    <div class="sda-spotlight__track" data-sda-marquee-track>
                        @foreach($spotlightMentions as $mention)
                            <div class="sda-spotlight__logo-item">
                                @if($mention->website_url)
                                    <a href="{{ $mention->website_url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $mention->name }} (opens in new tab)">
                                @else
                                    <span class="sda-spotlight__logo-static">
                                @endif
                                    <img src="{{ \App\Support\WebsiteMedia::url($mention->logo_path) }}" alt="{{ $mention->alt_text ?: $mention->name }}" loading="lazy" decoding="async">
                                @if($mention->website_url)</a>@else</span>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="sda-spotlight__arrow" data-sda-marquee-next type="button" aria-label="Next spotlight logo">&#8594;</button>
            </div>
        @endif
    </div>
    @if($spotlightVideo && $spotlightFull)
    <div class="sda-spotlight__video-container" data-sda-preview
         data-autoplay="{{ (!$spotlightSettings->exists || $spotlightSettings->video_autoplay) ? 'true' : 'false' }}"
         data-preview-embed="{{ $spotlightPreviewEmbed ?: '' }}"
         data-full-provider="{{ $spotlightVideo->full_video_provider }}"
         data-full-url="{{ $spotlightFull }}">
        <div class="sda-spotlight__video-stage">
            <img class="sda-spotlight__poster" src="{{ \App\Support\WebsiteMedia::url($spotlightVideo->poster_path) }}"
                 alt="{{ $spotlightVideo->title }}" loading="lazy" decoding="async">
            @if(\App\Support\WebsiteMedia::exists($spotlightVideo->preview_video_path))
                <video class="sda-spotlight__preview-video" muted loop playsinline preload="none"
                       poster="{{ \App\Support\WebsiteMedia::url($spotlightVideo->poster_path) }}" aria-hidden="true" data-preview-file>
                    <source src="{{ asset($spotlightVideo->preview_video_path) }}" type="{{ str_ends_with(strtolower($spotlightVideo->preview_video_path), '.webm') ? 'video/webm' : 'video/mp4' }}">
                </video>
            @endif
            <div class="sda-spotlight__scrim" aria-hidden="true"></div>
            <div class="sda-spotlight__video-label">
                <span class="sda-spotlight__video-label-mini">Sunnah Digital Agency</span>
                <strong>{{ $spotlightVideo->title }}</strong>
                @if($spotlightVideo->caption)<span>{{ $spotlightVideo->caption }}</span>@endif
            </div>
            <button type="button" class="sda-spotlight__play" data-spotlight-play aria-label="Play full video: {{ $spotlightVideo->title }}">
                <span class="sda-spotlight__play-icon" aria-hidden="true">&#9654;</span>
                <span class="sda-spotlight__play-text">Play Full Video</span>
            </button>
        </div>
    </div>
    <div class="sda-spotlight__modal" data-spotlight-modal role="dialog" aria-modal="true" aria-label="Spotlight full video" hidden>
        <div class="sda-spotlight__modal-backdrop" data-spotlight-close></div>
        <div class="sda-spotlight__modal-content" tabindex="-1">
            <button type="button" class="sda-spotlight__modal-close" data-spotlight-close aria-label="Close video">&#215;</button>
            <div class="sda-spotlight__modal-player" data-spotlight-player></div>
        </div>
    </div>
    @endif
</section>
@endif
