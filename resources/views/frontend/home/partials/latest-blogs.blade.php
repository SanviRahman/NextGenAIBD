{{-- Step-10: Published articles come from the existing blogs table/Admin Blogs CRUD. --}}
@if(isset($latestBlogs) && $latestBlogs->isNotEmpty())
    @once
        @push('homepage_hero_after_main')
            <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-latest-blogs.css') }}?v=1.0">
        @endpush
        @push('homepage_hero_scripts')
            <script defer src="{{ asset('frontend/assets/js/sda-latest-blogs.js') }}?v=1.0"></script>
        @endpush
    @endonce
    <section class="sda-latest-blogs" id="latest-blogs" aria-labelledby="sda-latest-blogs-title" data-sda-latest-blogs>
        <div class="sda-latest-blogs__container">
            <div class="sda-latest-blogs__header">
                <div class="sda-latest-blogs__heading">
                    <span class="sda-latest-blogs__eyebrow">INSIGHTS & IDEAS</span>
                    <h2 id="sda-latest-blogs-title">Latest <span>Blogs</span></h2>
                    <p>Explore practical insights, fresh ideas and expert perspectives from Sunnah Digital Agency.</p>
                </div>
                <a class="sda-latest-blogs__view-all" href="{{ route('frontend_Blog_Index') }}">VIEW ALL <span aria-hidden="true">↗</span></a>
            </div>
            <div class="sda-latest-blogs__grid">
                @foreach($latestBlogs as $blog)
                    @php
                        $excerpt = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode((string) ($blog->sort_description ?: $blog->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
                        $blogImage = trim((string) $blog->image);
                    @endphp
                    <article class="sda-latest-blogs__card" data-sda-blog-card style="--sda-blog-delay:{{ $loop->index * 100 }}ms">
                        <a class="sda-latest-blogs__image-link" href="{{ route('frontend_Blog_Single', $blog->slug) }}" aria-label="Read {{ $blog->title }}">
                            @if($blogImage !== '' && \App\Support\WebsiteMedia::exists('media/blog/' . basename($blogImage)))
                                <img src="{{ asset('media/blog/' . basename($blogImage)) }}" alt="{{ $blog->title }}" loading="lazy" decoding="async" width="640" height="380">
                            @else
                                <span class="sda-latest-blogs__image-fallback" aria-hidden="true">SDA <span>Journal</span></span>
                            @endif
                            <span class="sda-latest-blogs__image-overlay" aria-hidden="true"></span>
                            <span class="sda-latest-blogs__corner" aria-hidden="true">↗</span>
                        </a>
                        <div class="sda-latest-blogs__card-body">
                            <div class="sda-latest-blogs__meta">
                                <time datetime="{{ optional($blog->created_at)->toDateString() }}">{{ optional($blog->created_at)->format('F d, Y') }}</time>
                                @if(trim((string)$blog->categories) !== '')
                                    <span class="sda-latest-blogs__category">{{ \Illuminate\Support\Str::limit(strip_tags($blog->categories), 25) }}</span>
                                @endif
                            </div>
                            <h3><a href="{{ route('frontend_Blog_Single', $blog->slug) }}">{{ $blog->title }}</a></h3>
                            @if($excerpt !== '')<p>{{ \Illuminate\Support\Str::limit($excerpt, 136) }}</p>@endif
                            <a class="sda-latest-blogs__read-more" href="{{ route('frontend_Blog_Single', $blog->slug) }}">Read Article <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
