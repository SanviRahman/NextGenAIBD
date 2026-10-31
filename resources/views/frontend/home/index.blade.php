@extends('frontend.layouts.master')
{{-- @section('title', 'Course | Sunnah digital Agency') --}}
@section('content')

    {{-- Hero only: isolated component. Other homepage sections stay unchanged. --}}
    @include('frontend.home.partials.hero')

    {{-- Step-02 Spotlight — isolated component; everything below remains intact. --}}
    @include('frontend.home.partials.spotlight')

    {{-- Step-03: services grid; original Course/About/Team blocks retained below. --}}
    @include('frontend.home.partials.services')

    {{-- Step-04: 3-step consultation form. Core sections remain untouched. --}}
    @include('frontend.home.partials.consultation')

    {{-- Step-05: verified client review profiles only; hide if none exist. --}}
    @include('frontend.home.partials.ratings')

    {{-- Step-06: Benefits; original Course/About/Team blocks remain below. --}}
    @include('frontend.home.partials.benefits')



    {{-- Step-07: permission-confirmed client logo wall, using existing About records. --}}
    @include('frontend.home.partials.clients')


    {{-- Step-08: genuine client video testimonials; uses existing client_reviews table. --}}
    @include('frontend.home.partials.testimonials')

    {{-- Step-09: gallery photo mosaic & lightbox; uses isolated abouts gallery roles. --}}
    @include('frontend.home.partials.gallery')


    {{-- Step-10: Existing published Blog records; no new table or duplicate blog CRUD. --}}
    @include('frontend.home.partials.latest-blogs')

    {{-- Keep global layout Footer. Remove ONLY the legacy homepage Course/About/Team/Testimonial/Solution blocks. --}}
    {{-- Homepage typography scale loaded after section CSS. --}}
    @push('homepage_hero_after_main')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-homepage-scale.css') }}?v=1.0">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-homepage-final.css') }}?v=1.0">
        {{-- Mobile hero image-first + sticky-navbar clearance. Loaded LAST; homepage only. --}}
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-hero-responsive.css') }}?v=2.2">
    @endpush
@endsection
