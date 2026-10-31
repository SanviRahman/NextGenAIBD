@php
    // The controller already limits this collection to active, permission-confirmed clients.
    $clientsVisible = !$clientsSettings->exists || (string) $clientsSettings->status === '1';
    $clientsHeading = $clientsSettings->title ?: 'Growing Together with Our Clients';
    $clientsBg = $clientsSettings->homepage_bg_color ?: '#101821';
    $clientsAccent = $clientsSettings->homepage_accent_color ?: '#C74659';
    $clientsCount = $homepageClients->count();
@endphp
@if($clientsVisible && $homepageClients->isNotEmpty())
    @once
        @push('homepage_hero_after_main')
            <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-clients.css') }}?v=2.0">
        @endpush
        @push('homepage_hero_scripts')
            <script src="{{ asset('frontend/assets/js/sda-clients.js') }}?v=2.0" defer></script>
        @endpush
    @endonce

    <section id="our-clients" class="sda-clients" data-sda-clients
        style="--sda-clients-bg: {{ $clientsBg }}; --sda-clients-accent: {{ $clientsAccent }}"
        aria-labelledby="sda-clients-title">
        <div class="sda-clients__container">
            <header class="sda-clients__header" data-sda-clients-reveal>
                <div class="sda-clients__heading">
                    <div>
                        <span class="sda-clients__eyebrow">OUR COLLABORATIONS</span>
                        <h2 id="sda-clients-title">{{ $clientsHeading }}</h2>
                    </div>
                    @if($clientsCount > 1)
                        <button type="button" class="sda-clients__motion-toggle"
                            data-sda-clients-toggle aria-pressed="false" aria-label="Pause client logo animations">
                            <span data-sda-clients-toggle-icon aria-hidden="true">Ⅱ</span>
                            <span data-sda-clients-toggle-label>Pause motion</span>
                        </button>
                    @endif
                </div>
                @if($clientsSettings->description)
                    <p>{{ $clientsSettings->description }}</p>
                @endif
            </header>

            <div class="sda-clients__panel" data-sda-clients-reveal>
                {{-- Row 1: exactly four visible slots on desktop. Each card flips 180 degrees
                     to reveal the next approved client; text never appears mirrored. --}}
                <div class="sda-clients__featured" data-sda-clients-featured
                     aria-label="Featured clients" aria-live="off">
                    @foreach($homepageClients->take(4) as $client)
                        <div class="sda-clients__flip-card" data-sda-client-flip
                             style="--sda-clients-flip-offset: {{ $loop->index * 110 }}ms">
                            <div class="sda-clients__flip-inner">
                                <div class="sda-clients__flip-face sda-clients__flip-face--front" data-sda-client-front>
                                    @include('frontend.home.partials.client-logo', ['client' => $client, 'logoIndex' => $loop->index])
                                </div>
                                <div class="sda-clients__flip-face sda-clients__flip-face--back"
                                     data-sda-client-back aria-hidden="true" inert></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Row 2: a seamless, continuous marquee. Both groups have identical width.
                     The duplicate group is presentation-only and excluded from keyboard focus. --}}
                <div class="sda-clients__marquee" data-sda-clients-marquee role="region"
                     aria-label="More client logos, automatically scrolling">
                    <div class="sda-clients__marquee-track">
                        <div class="sda-clients__marquee-group">
                            @foreach($homepageClients as $client)
                                @include('frontend.home.partials.client-logo', ['client' => $client, 'logoIndex' => $loop->index])
                            @endforeach
                        </div>
                        @if($clientsCount > 1)
                            <div class="sda-clients__marquee-group" aria-hidden="true" inert>
                                @foreach($homepageClients as $client)
                                    @include('frontend.home.partials.client-logo', ['client' => $client, 'logoIndex' => $loop->index])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Template elements are invisible to visitors and contain approved logos only.
             JS clones these existing safe Blade-rendered elements; no HTML injection. --}}
        <template data-sda-clients-bank>
            @foreach($homepageClients as $client)
                @include('frontend.home.partials.client-logo', ['client' => $client, 'logoIndex' => $loop->index])
            @endforeach
        </template>
    </section>
@endif
