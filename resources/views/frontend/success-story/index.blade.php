@extends('frontend.layouts.master')
@section('title', 'About | Sunnah digital Agency')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Success Story
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        Success Story
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="about-section bg-white pt-120 pb-120">
        <div class="shape">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="shape2">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="about-thumb">
                        <div class="thumb-main wow fadeInUp" data-wow-duration="2s">
                            <img src="{{ asset('/media/successStory') . '/' . $successStory->image }}" alt="s-img">
                        </div>
                        <div class="ab-thumb wow fadeInDown" data-wow-duration="2s">
                            <img src="{{ asset('/media/successStory') . '/' . $successStory->image }}" alt="a-img">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="about-content">
                        <div class="section-header">
                            <div class="section-header wow fadeInUp" data-wow-delay="0.2s">
                                <h5 class="sub">
                                    Success Story
                                </h5>
                                <h1 class="section-title">
                                    {{ $successStory->title }}
                                </h1>
                                <p>
                                    {!! $successStory->description !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
