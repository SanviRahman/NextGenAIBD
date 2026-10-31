@extends('frontend.layouts.master')
@section('title', 'About | Sunnah digital Agency')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    About US
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        About US
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
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-header">
                            <div class="section-header wow fadeInUp" data-wow-delay="0.2s">
                                <h5 class="sub">
                                    About us
                                </h5>
                                <h1 class="section-title">
                                    {{ $about->title }}
                                </h1>
                                <p>
                                    {!! $about->description !!}
                                </p>
                            </div>
                        </div>
                        <div class="about-counter-wrapper">
                            <div class="counter-items odometer-item">
                                <div class="counter-content">
                                    <div class="cont d-flex align-items-center">
                                        <h2 class="odometer" data-odometer-final="24">
                                            0
                                        </h2>
                                    </div>
                                    <p>
                                        Project
                                    </p>
                                </div>
                            </div>
                            <div class="counter-items odometer-item">
                                <div class="counter-content">
                                    <div class="cont d-flex align-items-center">
                                        <h2 class="odometer" data-odometer-final="125">
                                            0
                                        </h2>
                                    </div>
                                    <p>
                                        Headquarters
                                    </p>
                                </div>
                            </div>
                            <div class="counter-items odometer-item">
                                <div class="counter-content">
                                    <div class="cont d-flex align-items-center">
                                        <h2 class="odometer" data-odometer-final="17">
                                            0
                                        </h2>
                                    </div>
                                    <p>
                                        Award Winner
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-thumb">
                        <div class="thumb-main wow fadeInUp" data-wow-duration="2s">
                            <img src="{{ asset('/media/about') . '/' . $about->image }}" alt="s-img">
                        </div>
                        <div class="ab-thumb wow fadeInDown" data-wow-duration="2s">
                            <img src="{{ asset('/media/about') . '/' . $about->image }}" alt="a-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
