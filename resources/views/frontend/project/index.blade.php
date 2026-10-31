@extends('frontend.layouts.master')
@section('title', 'Project | Sunnah')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Project
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        Project
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
                    <span>Project</span>
                </h5>
                <h1 class="section-title">
                    Letest Project
                </h1>
                {{-- <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius elementum tristique. Duis
                    cursus, mi quis viverra ornare, eros d
                </p> --}}
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($projects as $project)
                    <div class="col-lg-4 col-md-6 col-sm-9">
                        <div class="blog-items">
                            <div class="thumb">
                                <a href="{{ route('frontend_Project_Single', $project->slug) }}">
                                    <img src="{{ asset('/media/project') . '/' . $project->image }}" alt="blog-img">
                                </a>
                            </div>
                            <div class="content">
                                <a href="{{ route('frontend_Project_Single', $project->slug) }}" class="title">
                                    {{ $project->title }}
                                </a>
                                <p>
                                    {{ $project->sort_description }}
                                </p>
                                <a href="{{ route('frontend_Project_Single', $project->slug) }}" class="link-btn">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $projects->links('vendor.pagination.bootstrap-5') }}
        </div>
    </section>
    <!--Prtfolio Filter Section END-->
@endsection
