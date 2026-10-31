@extends('frontend.layouts.master')
@section('title', 'Service | ' . $service->slug . ' | Sunnah digital Agency')
@section('content') <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    {{ $service->title }}
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend_Service_Index') }}">
                            Service
                        </a>
                    </li>
                    <li>
                        Details
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Page header -->

    <section class="py-5 py-lg-7">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row ">
                <!-- col -->
                <div class="mb-6 col-lg-8 mb-lg-0">
                    <div class="">
                        <!-- image -->
                        <img src="{{ asset('/media/service') . '/' . $service->image }}" style=""
                            alt="{{ $service->image }}" class="mb-3 rounded img-fluid">
                        <!-- heading -->
                        <h1 class="mb-3">
                            {{ $service->title }}
                        </h1>
                        <!-- para -->
                        <div class="mb-5 pe-lg-10">
                            {!! $service->description !!}
                        </div>
                        <!-- btn -->
                        {{-- <a href="#" class="btn btn-primary">Join Free Now</a>
                        <a href="#" class="popup-youtube fs-4 text-inherit ms-3"><img
                                src="{{ asset('backend') }}/assets/images/svg/play-btn.svg" alt="play"
                                class="me-2">Watch Demo
                        </a> --}}
                    </div>
                </div>
                <!-- col -->
                <div class="col-lg-4">
                    @foreach ($services as $service)
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                            <!-- Card -->
                            <div class="mb-1 card card-hover">
                                <div class="p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex">
                                        <a href="{{ route('frontend_Service_Single', $service->slug) }}" class="bg-info">
                                            <!-- Img -->
                                            <img src="{{ asset('/media/service') . '/' . $service->image }}"
                                                style="height: auto;width: 6.5rem;object-fit: cover" alt="bootstrap"
                                                class="avatar-md1 img-fluid1">
                                        </a>
                                        <div class="ms-3">
                                            <h6 class="mb-1">
                                                <a href="{{ route('frontend_Service_Single', $service->slug) }}"
                                                    class="text-dark">
                                                    {{ $service->title }}
                                                </a>
                                            </h6>
                                            <p class="mb-0 fs-6">
                                                <a href="{{ route('frontend_Service_Single', $service->slug) }}"
                                                    class="text-primary">
                                                    Read More
                                                    <span class="">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Content -->
@endsection
