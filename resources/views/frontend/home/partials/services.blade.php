{{-- Step-03: scoped, database-backed home services grid. Core service CRUD is unchanged. --}}
@if(($homepageServicesSettings->is_active ?? true) && $homepageServices->isNotEmpty())
<section id="home-services" class="sda-services" aria-labelledby="sda-services-title"
    style="--sda-services-bg: {{ $homepageServicesSettings->background_color ?: '#090C13' }};
           --sda-services-card: {{ $homepageServicesSettings->card_color ?: '#1A1D22' }};
           --sda-services-accent: {{ $homepageServicesSettings->accent_color ?: '#C74659' }};">
    <div class="sda-services__container">
        <div class="sda-services__header" data-sda-service-reveal>
            <div class="sda-services__heading-block">
                @if($homepageServicesSettings->eyebrow)
                    <span class="sda-services__eyebrow">{{ $homepageServicesSettings->eyebrow }}</span>
                @endif
                <h2 id="sda-services-title" class="sda-services__title">{{ $homepageServicesSettings->heading ?: 'Our Services' }}</h2>
                @if($homepageServicesSettings->description)
                    <p class="sda-services__intro">{{ $homepageServicesSettings->description }}</p>
                @endif
            </div>
            <a class="sda-services__view-all" href="{{ route('frontend_Service_Index') }}">
                <span>{{ $homepageServicesSettings->button_label ?: 'View All' }}</span>
                <svg viewBox="0 0 24 24" width="17" height="17" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </a>
        </div>
        <div class="sda-services__grid">
            @foreach($homepageServices as $service)
                <article class="sda-services__card" data-sda-service-reveal style="--sda-services-index:{{ $loop->index % 3 }}">
                    <a class="sda-services__card-link" href="{{ route('frontend_Service_Single', $service->slug) }}"
                        aria-label="{{ $service->title }} — View service details">
                        <span class="sda-services__icon-wrap" aria-hidden="true">
                            @if($service->image)
                                <img class="sda-services__icon" loading="lazy" decoding="async"
                                    src="{{ \App\Support\WebsiteMedia::url('media/service/' . basename((string) $service->image)) }}" alt=""
                                    data-sda-service-icon>
                            @endif
                            <svg class="sda-services__icon-fallback" width="42" height="42" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 38V14l17-8 19 8v24L23 44 6 38Z"/><path d="m6 14 17 9 19-9M23 23v21M15 32l5 3M28 33l7-3"/></svg>
                        </span>
                        <h3 class="sda-services__card-title">{{ $service->title }}</h3>
                        <p class="sda-services__card-description">{{ \Illuminate\Support\Str::limit(strip_tags((string) $service->sort_description), 215) }}</p>
                        <span class="sda-services__read-more" aria-hidden="true">Explore Service <span>↗</span></span>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@once
    @push('homepage_hero_after_main')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-services.css') }}?v=1.0">
    @endpush
    @push('homepage_hero_scripts')
        <script src="{{ asset('frontend/assets/js/sda-services.js') }}?v=1.0" defer></script>
    @endpush
@endonce
