@extends('frontend.layouts.master')
@section('title', 'Blog | ' . $blog->slug . ' | Sunnah digital Agency')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Blog Details
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#0">
                            Blog
                        </a>
                    </li>
                    <li>
                        Blog Details
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="blog-section pt-120 pb-120">
        <div class="container">
            <div class="blog-list-wrapper blog-details-wrapper">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="datails-left-shadow">
                            <div class="blog-items">
                                <div class="thumb">
                                    <a href="#0">
                                        <img src="{{ asset('/media/blog') . '/' . $blog->image }}" alt="blog-img">
                                    </a>
                                </div>
                                <div class="content">
                                    <ul class="timing-wrapper d-flex align-items-center">
                                        {{-- <li>
                                            <img src="{{ asset('frontend') }}/assets/images/blog/v1.png" alt="c-img">
                                            <span> 153 Views</span>
                                        </li> --}}
                                        <li>
                                            <img src="{{ asset('frontend') }}/assets/images/blog/co1.png" alt="c-img">
                                            <span> Comments (0)</span>
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend') }}/assets/images/blog/c1.png" alt="c-img">
                                            <span> {{ $blog->created_at }}</span>
                                        </li>
                                    </ul>
                                    <a href="#0" class="title">
                                        {{ $blog->title }}
                                    </a>
                                    <p>
                                        {!! $blog->description !!}
                                    </p>
                                </div>
                            </div>
                            {{-- <div class="blog-items">
                                <div class="thumb">
                                    <div class="thumb-overlay">
                                        <img src="{{ asset('frontend') }}/assets/images/blog/details-v.png" alt="blog-img">
                                    </div>
                                    <a href="https://www.youtube.com/watch?v=wShXvvLCat8" class="video-btn">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div> --}}
                            {{-- <div class="blog-items">
                                <div class="content">
                                    <div class="replay-wrapper">
                                        <form action="#">
                                            <h4 class="reply-head">
                                                Leave a Reply
                                            </h4>
                                            <div class="row g-4">
                                                <div class="col-lg-12">
                                                    <div class="form-clt form-clt-big">
                                                        <input type="text" placeholder="Type your comment..">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-clt">
                                                        <input type="text" placeholder="Type you name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-clt">
                                                        <input type="text" placeholder="Type you email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                        <input type="text" placeholder="Type you website">
                                                    </div>
                                                </div>
                                                <button type="submit" class="cmn--btn-2">
                                                    Post Comment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="blog-list-right">
                            {{-- <div class="items-box">
                                <h3 class="box-head">
                                    Search Here
                                </h3>
                                <form>
                                    <input type="text" placeholder="Search...">
                                    <i class="fas fa-search"></i>
                                </form>
                            </div> --}}
                            {{-- <div class="items-box">
                                <h3 class="box-head">
                                    Categories
                                </h3>
                                <ul class="categoris">
                                    <li>
                                        <a href="#0">
                                            UI/UX Design
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Font &amp; Back End
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Digital marketing
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Networking Server
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Travels Agency
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                            <div class="items-box">
                                <h3 class="box-head">
                                    Recent Posts
                                </h3>
                                <ul class="post-recent">
                                    @foreach ($blogs as $blog)
                                        <li>
                                            <div class="thumb">
                                                <img src="{{ asset('/media/blog') . '/' . $blog->image }}"
                                                    style="object-fit: cover" alt="b-img">
                                            </div>
                                            <div class="cont">
                                                <a href="#0" class="title">
                                                    {{ $blog->title }}
                                                </a>
                                                <span class="date">
                                                    <img src="{{ asset('frontend') }}/assets/images/blog/c1.png"
                                                        alt="c-img">
                                                    {{ $blog->created_at->format('F d, Y') }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- <div class="items-box">
                                <h3 class="box-head">
                                    Popular Tag
                                </h3>
                                <ul class="tag">
                                    <li>
                                        <a href="#0">
                                            Advice
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Author
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Judge
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            TechBiz
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Family
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Solution
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Health
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Consulting
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            Family
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
