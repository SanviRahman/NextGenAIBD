@php
    $benefitsVisible = !$benefitsSettings->exists || (string) $benefitsSettings->status === '1';
    $benefitsImage = $benefitsSettings->image ?: ($about->image ?? null);
    $benefitsBg = $benefitsSettings->homepage_bg_color ?: '#17191d';
    $benefitsAccent = $benefitsSettings->homepage_accent_color ?: '#C74659';
@endphp
@if($benefitsVisible && $homepageBenefits->isNotEmpty())
@once
    @push('homepage_hero_after_main')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-benefits.css') }}?v=1.0">
    @endpush
    @push('homepage_hero_scripts')
        <script src="{{ asset('frontend/assets/js/sda-benefits.js') }}?v=1.0" defer></script>
    @endpush
@endonce
<section id="why-choose-us" class="sda-benefits" aria-labelledby="sda-benefits-title" style="--sda-benefits-bg: {{ $benefitsBg }}; --sda-benefits-accent: {{ $benefitsAccent }}" data-sda-benefits>
    <div class="sda-benefits__container">
        <header class="sda-benefits__header" data-benefits-reveal>
            <h2 id="sda-benefits-title" class="sda-benefits__title">{{ $benefitsSettings->title ?: 'Discover the Benefits with Sunnah' }}</h2>
            @if($benefitsSettings->description)
                <p class="sda-benefits__intro">{{ $benefitsSettings->description }}</p>
            @endif
        </header>
        <div class="sda-benefits__grid">
            <div class="sda-benefits__list" aria-label="Reasons to choose Sunnah Digital Agency">
                @foreach($homepageBenefits as $benefit)
                    <article class="sda-benefits__item" style="--benefit-seq: {{ min($loop->index, 6) }};" data-benefits-reveal>
                        <span class="sda-benefits__check" aria-hidden="true">
                            <svg viewBox="0 0 48 48" width="48" height="48" fill="none" aria-hidden="true" focusable="false"><circle cx="24" cy="24" r="22" fill="currentColor"/><path d="m13.5 24 7.2 7.3L35 17.5" stroke="var(--sda-benefits-bg)" stroke-width="5.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <div class="sda-benefits__copy">
                            <h3>{{ $benefit->title }}</h3>
                            <p>{{ $benefit->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="sda-benefits__image-column" data-benefits-reveal>
                @if($benefitsImage && \App\Support\WebsiteMedia::exists('media/about/' . basename((string) $benefitsImage)))
                    <figure class="sda-benefits__image-frame">
                        <img src="{{ asset('media/about/' . $benefitsImage) }}" alt="{{ $benefitsSettings->homepage_image_alt ?: 'Sunnah Digital Agency workspace and team' }}" loading="lazy" decoding="async" width="720" height="620">
                    </figure>
                @else
                    <div class="sda-benefits__image-placeholder" role="img" aria-label="Workspace photo can be uploaded from the Benefits Manager"><span aria-hidden="true">✦</span><p>Building digital growth together</p></div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
