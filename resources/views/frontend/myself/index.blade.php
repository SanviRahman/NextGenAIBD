@extends('frontend.layouts.master')
@section('title', 'About | Sunnah digital Agency')
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    About MySelf
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        About MySelf
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="team-details-section bg-two pt-120 pb-120">
        <div class="shape">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="shape2">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6">
                    <div class="team-details-thumb">
                        <img src="{{ asset('/media/banner') . '/' . $personalInfo->image }}" alt="about-img">
                    </div>
                </div>
                <div class="col-lg-1">

                </div>
                <div class="col-lg-5">
                    <div class="team-details-content">
                        <div class="head">
                            <h4 class="name">
                                {{ $personalInfo->name }}
                            </h4>
                            <span>Cheif Expert</span>
                        </div>
                        <p>
                            {{ $personalInfo->sort_description }}
                        </p>
                        <ul class="contact-list">
                            <li>
                                <ul>
                                    <li>
                                        <span>Experience</span>
                                        <h6>More Than 10 Years</h6>
                                    </li>
                                    <li>
                                        <span>Phone</span>
                                        <a href="#0">{{ $personalInfo->number }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <span>Email</span>
                                        <h6>{{ $personalInfo->email }}</h6>
                                    </li>
                                    <li>
                                        <span>Address</span>
                                        <a href="#0">{{ $personalInfo->address }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <div class="content-area">
                            <ul class="footer-social d-flex m-0">
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->facebook }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </li>

                                <!-- YouTube -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->youtube }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>

                                <!-- Instagram -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->instagram }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>

                                <!-- Twitter -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->twitter }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>

                                <!-- LinkedIn -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->linkdin }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </li>

                                <!-- Reddit -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->radit }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-reddit"></i>
                                    </a>
                                </li>

                                <!-- TikTok -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->tiktok }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </li>

                                <!-- Pinterest -->
                                <li>
                                    <a target="_blank" href="{{ $personalInfo->pintarest }}" class="cmn--btn p-2 px-3 m-1">
                                        <i class="fab fa-pinterest"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a href="contact.html" class="cmn--btn">Get In Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-skill-section bg-white">
        <div class="shape">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="shape2">
            <img src="{{ asset('frontend') }}/assets/images/vector/s-vector.png" alt="shape">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="section-header wow fadeInUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <h5 class="sub">
                            <span>Personal Details</span>
                        </h5>
                        <h1 class="section-title">
                            Personal Biography
                        </h1>
                        <p>
                            {!! $personalInfo->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
