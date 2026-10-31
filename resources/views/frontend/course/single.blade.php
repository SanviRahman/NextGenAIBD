@extends('frontend.layouts.master')
@section('title', 'Course | ' . $course->slug . ' | Sunnah digital Agency')
@section('content') <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Course
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        Course
                    </li>
                    <li>
                        Details
                    </li>
                </ul>
            </div>
        </div>
    </section>

    @php
        $logo = App\Models\Logo::first();
        $admin = App\Models\Admin::first();
    @endphp
    <!-- Page header -->
    <!-- Page content -->
    <section class="pb-10">
        <div class="container">
            <div class="row pt-5">
                <div class="mb-4 col-lg-8 col-md-12 col-12 mt-n8 mb-lg-0">
                    <div class="datails-left-shadow">
                        <div class="blog-items">
                            <div class="thumb">
                                <a href="#0">
                                    <img src="{{ asset('/media/course') . '/' . $course->image }}" alt="blog-img">
                                </a>
                            </div>
                            <div class="content">
                                <a href="#0" class="title">
                                    {{ $course->title }}
                                </a>
                                <p>
                                    @php
                                        $mainValue = $course->price;
                                        $totalDiscount = 0;
                                        # Calculate the total discount by summing up all course discounts
                                        $totalDiscount += isset($course->discount) ? $course->discount : 0;
                                        # Calculate the result
                                        $courseResult = $mainValue - $mainValue * ($totalDiscount / 100);
                                        $courseResults = $mainValue - $mainValue * ($totalDiscount / 100);
                                        $courseResult = number_format($courseResult, 2, '.', ',');
                                    @endphp
                                    <span class="text-dark fw-bold h2">৳{{ $courseResult }}</span>
                                    <del class=" text-muted">৳{{ $course->price }}</del>
                                </p>
                                <p>
                                    {!! $course->description !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    {{-- <div class="card rounded-3">
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Description -->
                            <img src="{{ asset('/media/course') . '/' . $course->image }}" alt="course"
                                class="card-img-top rounded-top-md">

                            <h4 class="text-dark pt-2">{{ $course->title }}</h4>

                            <div class="mb-3">
                                @php
                                    $mainValue = $course->price;
                                    $totalDiscount = 0;
                                    # Calculate the total discount by summing up all course discounts
                                    $totalDiscount += isset($course->discount) ? $course->discount : 0;
                                    # Calculate the result
                                    $courseResult = $mainValue - $mainValue * ($totalDiscount / 100);
                                    $courseResults = $mainValue - $mainValue * ($totalDiscount / 100);
                                    $courseResult = number_format($courseResult, 2, '.', ',');
                                @endphp
                                <span class="text-dark fw-bold h2">৳{{ $courseResult }}</span>
                                <del class=" text-muted">৳{{ $course->price }}</del>
                            </div>

                            <div class="mb-4">
                                {{ $course->description }}
                                {!! nl2br(e($course->description)) !!}
                                {{ htmlspecialchars($course->description) }}
                                {!! $course->description !!}
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-lg-4 col-md-12 col-12 mt-lg-n22">
                    <!-- Card -->
                    <div class="mb-4 card">
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- Price single page -->
                            <div class="d-grid">
                                {{-- @if ($course->status == 1) --}}
                                {{-- <form action="" method="POST"> --}}
                                {{-- @csrf --}}
                                <input type="hidden" value="{{ $courseResults }}" name="course_price" id="">

                                <button type="submit" class="mb-2 btn btn-primary form-control">
                                    Pay ৳{{ $courseResults }}
                                </button>
                                <button type="submit" class="btn btn-outline-primary form-control">
                                    Get Full Access
                                </button>
                                {{-- </form> --}}
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="mb-4 card">
                        <div>
                            <!-- Card header -->
                            <ul class="list-group list-group-flush">
                                <li class="bg-transparent list-group-item">
                                    <b>Course Duration</b> : {{ $course->duration_class }}
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <b>Weekly Class</b> : {{ $course->weekly_class }}
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <b>Skill requirements</b> : {{ $course->skill_requirements }}
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <b>Language</b> : {{ $course->language }}
                                </li>
                                <li class="bg-transparent list-group-item">
                                    <b>Assignment</b> : {{ $course->assignment }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card -->


            <div class="py-5">
                <div class="mb-4 row d-md-flex align-items-center">
                    <div class="col-12">
                        <h2 class="mb-0 text-dark">Related Courses</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach ($courses as $course)
                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Card -->
                            <div class="mb-4 card card-hover">
                                <a href="{{ route('frontend_Course_Single', $course->slug) }}"
                                    class="card-img-top-container">
                                    <img src="{{ asset('/media/course') . '/' . $course->image }}" alt="course"
                                        class="card-img-top rounded-top-md">
                                    @if ($course->skill_requirements == 'Beginner')
                                        <span style="background: #FF5733; color: #FFFFFF" class="badge badge-top-left">
                                            {{ $course->skill_requirements }}
                                        </span>
                                    @elseif($course->skill_requirements == 'Intermediate')
                                        <span style="background: #FEF9C3; color: #D48A04" class="badge badge-top-left">
                                            {{ $course->skill_requirements }}
                                        </span>
                                    @elseif($course->skill_requirements == 'Advanced')
                                        <span style="background: #DCFCE7; color: #22C586" class="badge badge-top-left">
                                            {{ $course->skill_requirements }}
                                        </span>
                                    @elseif($course->skill_requirements == 'Expert')
                                        <span style="background: #F3E8FF; color: #A85AF8" class="badge badge-top-left">
                                            {{ $course->skill_requirements }}
                                        </span>
                                    @else
                                        <span style="background: #FF5733; color: #000" class="badge badge-top-left">
                                            {{ $course->skill_requirements }}
                                        </span>
                                    @endif
                                    <span style="background: #8A12FD; color: #FFFFFF" class="badge badge-top-right">
                                        @if ($course->discount == 100)
                                            FREE
                                        @else
                                            {{ isset($course->discount) ? $course->discount : 0 }}% OFF
                                        @endif
                                    </span>
                                </a>
                                <!-- Card body -->
                                <div class="card-body">
                                    <h4 class="mb-2 text-truncate-line-3">
                                        <a href="{{ route('frontend_Course_Single', $course->slug) }}" class="text-dark">
                                            {{ $course->title }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
