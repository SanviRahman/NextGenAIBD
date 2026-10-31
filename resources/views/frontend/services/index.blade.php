@extends('frontend.layouts.master')
@section('title', 'Service | Sunnah')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Service
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#0">
                            Page
                        </a>
                    </li>
                    <li>
                        Service
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!--Prtfolio Filter Section Start-->
    <section class="blog-section pt-120 pb-120">
        <div class="container">
            <div class="section-header section-center wow fadeInUp" data-wow-delay="0.2s"
                style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                <h5 class="sub-center">
                    <span>Service</span>
                </h5>
                <h1 class="section-title">
                    Letest Service
                </h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius elementum tristique. Duis
                    cursus, mi quis viverra ornare, eros d
                </p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6 col-sm-9">
                        <div class="blog-items">
                            <div class="thumb">
                                <a href="{{ route('frontend_Service_Single', $service->slug) }}">
                                    <img src="{{ asset('/media/service') . '/' . $service->image }}" alt="blog-img">
                                </a>
                            </div>
                            <div class="content">
                                <a href="{{ route('frontend_Service_Single', $service->slug) }}" class="title">
                                    {{ $service->title }}
                                </a>
                                <p>
                                    {{ $service->sort_description }}
                                </p>
                                <a href="{{ route('frontend_Service_Single', $service->slug) }}" class="link-btn">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $services->links('vendor.pagination.bootstrap-5') }}
        </div>
    </section>
    <!--Prtfolio Filter Section END-->
@endsection
