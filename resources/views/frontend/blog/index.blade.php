@extends('frontend.layouts.master')
@section('content')
@section('title', 'Blog | Sunnah digital Agency')
<!-- Page header -->
<section class="banner-section breadcumd-banner">
    <div class="container">
        <div class="breadcumd-wrapper">
            <h1 class="left-content">
                Blog
            </h1>
            <ul class="right-content">
                <li>
                    <a href="{{ route('home_Index') }}">
                        Home
                    </a>
                </li>
                <li>
                    Blog
                </li>
            </ul>
        </div>
    </div>
</section>
<!-- Page Content -->
<section class="blog-section pt-120 pb-120">
    <div class="container">
        <div class="section-header section-center wow fadeInUp" data-wow-delay="0.2s"
            style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
            <h5 class="sub-center">
                <span>Blog</span>
            </h5>
            <h1 class="section-title">
                Letest News
            </h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius elementum tristique. Duis
                cursus, mi quis viverra ornare, eros d
            </p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($blogs as $blog)
                <div class="col-lg-6 col-md-6 col-sm-9">
                    <div class="blog-items">
                        <div class="thumb">
                            <a href="{{ route('frontend_Blog_Single', $blog->slug) }}">
                                <img src="{{ asset('/media/blog') . '/' . $blog->image }}" alt="blog-img">
                            </a>
                        </div>
                        <div class="content">
                            <ul class="timing-wrapper d-flex align-items-center justify-content-between">
                                <li>
                                    <img src="{{ asset('frontend') }}/assets/images/blog/calender.png" alt="c-img">
                                    <span>{{ $blog->created_at->format('F d, Y') }} </span>
                                </li>
                                <li>
                                    <img src="{{ asset('frontend') }}/assets/images/blog/clock.png" alt="c-img">
                                    <span> 5 min read</span>
                                </li>
                            </ul>
                            <a href="{{ route('frontend_Blog_Single', $blog->slug) }}" class="title">
                                {{ $blog->title }}
                            </a>
                            <p>
                                {{ $blog->sort_description }}
                            </p>
                            <a href="{{ route('frontend_Blog_Single', $blog->slug) }}" class="link-btn">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
