@php
    $testimonialsVisible = !$testimonialsSettings->exists || (string) $testimonialsSettings->status === '1';
    $testimonialsBg = $testimonialsSettings->homepage_bg_color ?: '#0B0F16';
    $testimonialsAccent = $testimonialsSettings->homepage_accent_color ?: '#C74659';
    $testimonialsHeading = $testimonialsSettings->title ?: 'Stories From Our Clients';
@endphp
@if($testimonialsVisible && $homepageTestimonials->isNotEmpty())
    @once
        @push('homepage_hero_after_main')
            <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-testimonials.css') }}?v=1.0">
        @endpush
        @push('homepage_hero_scripts')
            <script src="{{ asset('frontend/assets/js/sda-testimonials.js') }}?v=1.0" defer></script>
        @endpush
    @endonce
    <section class="sda-testimonials" id="client-testimonials" data-sda-testimonials
             aria-labelledby="sda-testimonials-heading"
             style="--sda-tv-bg:{{ $testimonialsBg }};--sda-tv-accent:{{ $testimonialsAccent }};">
        <div class="sda-testimonials__wrap">
            <div class="sda-testimonials__header">
                <div>
                    <span class="sda-testimonials__eyebrow">CLIENT STORIES</span>
                    <h2 id="sda-testimonials-heading">{{ $testimonialsHeading }}</h2>
                    @if($testimonialsSettings->description)
                        <p>{{ $testimonialsSettings->description }}</p>
                    @endif
                </div>
                @if($homepageTestimonials->count() > 3)
                    <button type="button" data-sda-testimonials-more
                        class="sda-testimonials__all" aria-expanded="false"
                        aria-controls="sda-testimonials-grid">View All <span aria-hidden="true">↗</span></button>
                @endif
            </div>
            <div id="sda-testimonials-grid" class="sda-testimonials__grid">
                @foreach($homepageTestimonials as $review)
                    @php
                        $videoEmbed = \App\Support\TestimonialVideoUrl::embed($review->video_url);
                        $thumbnail = preg_match('/^sda-testimonial-[a-f0-9-]+\.(?:png|jpg|jpeg|webp)$/iD', (string)$review->image)
                            ? \App\Support\WebsiteMedia::url('media/homepage/testimonials/' . basename((string) $review->image))
                            : \App\Support\WebsiteMedia::url('media/client_review/' . basename((string)$review->image));
                    @endphp
                    @if($videoEmbed)
                        <article class="sda-testimonials__item {{ $loop->index >= 3 ? 'sda-testimonials__item--extra' : '' }}"
                                 data-sda-testimonials-card style="--tv-i: {{ $loop->index }}">
                            <button type="button" class="sda-testimonials__play-card" data-sda-testimonials-play
                                    data-embed="{{ $videoEmbed }}" data-title="{{ $review->user_name }}"
                                    aria-label="Play testimonial by {{ $review->user_name }}">
                                <span class="sda-testimonials__thumb">
                                    <img src="{{ $thumbnail }}" alt="Video testimonial thumbnail: {{ $review->user_name }}"
                                        loading="lazy" width="600" height="338">
                                </span>
                                <span class="sda-testimonials__play-icon" aria-hidden="true">
                                    <svg width="27" height="30" viewBox="0 0 27 30" focusable="false"><path d="M1 1 L26 15 L1 29Z" fill="currentColor"/></svg>
                                </span>
                                <span class="sda-testimonials__caption">{{ $review->user_name }}</span>
                            </button>
                        </article>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="sda-testimonials__modal" data-sda-testimonials-modal hidden>
            <div class="sda-testimonials__backdrop" data-sda-testimonials-dismiss></div>
            <div class="sda-testimonials__dialog" role="dialog" aria-modal="true"
                 aria-labelledby="sda-testimonials-modal-title" tabindex="-1">
                <div class="sda-testimonials__dialog-head">
                    <h3 id="sda-testimonials-modal-title" data-sda-testimonials-modal-title>Client video</h3>
                    <button class="sda-testimonials__close" type="button" data-sda-testimonials-dismiss aria-label="Close video">×</button>
                </div>
                <div class="sda-testimonials__iframe-box" data-sda-testimonials-frame></div>
            </div>
        </div>
    </section>
@endif
