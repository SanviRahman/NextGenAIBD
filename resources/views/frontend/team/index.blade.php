@extends('frontend.layouts.master')
@section('content')
@section('title', 'Team | Sunnah digital Agency')
<!-- Page header -->
<section class="banner-section breadcumd-banner">
    <div class="container">
        <div class="breadcumd-wrapper">
            <h1 class="left-content">
                Team
            </h1>
            <ul class="right-content">
                <li>
                    <a href="{{ route('home_Index') }}">
                        Home
                    </a>
                </li>
                <li>
                    Team
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
                <span>Team</span>
            </h5>
            {{-- <h1 class="section-title">
                Letest News
            </h1> --}}
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($teams as $team)
                <div class="col-lg-3 col-md-3 col-sm-9">
                    <div class="blog-items">
                        <div class="thumb">
                            <a href="{{ route('frontend_Team_Single', $team->slug) }}">
                                <img src="{{ asset('/media/team') . '/' . $team->image }}" alt="team-img">
                            </a>
                        </div>
                        <div class="content">
                            <a href="{{ route('frontend_Team_Single', $team->slug) }}" class="title">
                                {{ $team->name }}
                            </a>
                            {{-- <p>
                                {{ $team->sort_description }}
                            </p> --}}
                            <a href="{{ route('frontend_Team_Single', $team->slug) }}" class="link-btn">
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
