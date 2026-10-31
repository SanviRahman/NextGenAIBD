@php
    $ratingsBg = $ratingSettings->background_color ?: '#090D14';
    $ratingsAccent = $ratingSettings->accent_color ?: '#C74659';
@endphp
@push('homepage_hero_after_main')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-ratings.css') }}?v=1.1">
@endpush
@push('homepage_hero_scripts')
    <script defer src="{{ asset('frontend/assets/js/sda-ratings.js') }}?v=1.0"></script>
@endpush
@if(($ratingSettings->exists ? $ratingSettings->is_active : true) && $ratingPlatforms->isNotEmpty())
<section class="sda-ratings" id="client-ratings" aria-labelledby="sda-ratings-heading"
         style="--sda-ratings-bg: {{ $ratingsBg }}; --sda-ratings-accent: {{ $ratingsAccent }}; --sda-ratings-seconds: {{ (int) ($ratingSettings->scroll_seconds ?: 36) }}s;"
         data-sda-ratings data-autoplay="{{ (!$ratingSettings->exists || $ratingSettings->autoplay) ? 'true' : 'false' }}">
    <div class="sda-ratings__container">
        <div class="sda-ratings__header" data-ratings-reveal>
            @if($ratingSettings->eyebrow)<span class="sda-ratings__eyebrow">{{ $ratingSettings->eyebrow }}</span>@endif
            <h2 id="sda-ratings-heading" class="sda-ratings__title">{{ $ratingSettings->heading ?: 'Where Clients Find Our Reviews' }}</h2>
            @if($ratingSettings->description)<p class="sda-ratings__description">{{ $ratingSettings->description }}</p>@endif
        </div>
        <div class="sda-ratings__showcase" data-ratings-reveal>
            <img class="sda-ratings__laurel sda-ratings__laurel--left"
                 src="{{ asset('frontend/assets/images/ratings/laurel-left.png') }}?v=1.1"
                 alt="" role="presentation" aria-hidden="true" width="332" height="380" loading="lazy" decoding="async">
            <div class="sda-ratings__viewport" role="region" aria-label="Verified third-party review profiles" data-ratings-viewport>
                <div class="sda-ratings__rail" data-ratings-rail>
                    <div class="sda-ratings__group" data-ratings-original>
                        @foreach($ratingPlatforms as $platform)
                            <a class="sda-ratings__item" href="{{ $platform->profile_url }}" target="_blank" rel="noopener noreferrer"
                               title="{{ $platform->platform_name }} — View actual reviews (opens new tab)" aria-label="{{ $platform->platform_name }}: view our public reviews (opens new tab)"
                               style="--sda-ratings-order: {{ min($loop->index, 8) }};">
                                <img loading="lazy" decoding="async" src="{{ \App\Support\WebsiteMedia::url($platform->logo_path) }}" alt="{{ $platform->platform_name }}" width="160" height="65">
                                @if($platform->rating_value !== null)
                                    <span class="sda-ratings__score">{{ number_format((float)$platform->rating_value, 1) }} / 5
                                        @if($platform->review_count !== null)<span>({{ number_format((int)$platform->review_count) }} reviews)</span>@endif
                                    </span>
                                @endif
                                <span class="sda-ratings__tooltip" aria-hidden="true">View real reviews ↗</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <img class="sda-ratings__laurel sda-ratings__laurel--right"
                 src="{{ asset('frontend/assets/images/ratings/laurel-right.png') }}?v=1.1"
                 alt="" role="presentation" aria-hidden="true" width="340" height="402" loading="lazy" decoding="async">
        </div>
    </div>
</section>
@endif
