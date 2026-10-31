@extends('frontend.layouts.master')
@section('title', 'Not Found | 402 | Sunnah digital Agency')
@section('content')
    @php
        $logo = App\Models\Logo::first();
        $admin = App\Models\Admin::first();
    @endphp
    <section class="container d-flex flex-column">
        <div class="py-10 row align-items-center justify-content-center g-0 py-lg-10">
            <!-- Docs -->
            <div class="text-center offset-xl-1 col-xl-4 col-lg-6 col-md-12 col-12 text-lg-start">
                <h1 class="mb-3 display-1">402</h1>

                <p class="px-4 mb-5 lead px-md-0">Oops! Sorry, we couldn’t find the page you were looking for. If you think
                    this is a
                    problem with us, please
                    <a href="{{ route('frontend_Contact_Index') }}" class="btn-link">Contact us</a>
                </p>
                <a href="{{ route('home_Index') }}" class="btn btn-primary me-2">Back to Safety</a>
            </div>
            <!-- img -->
            <div class="mt-8 offset-xl-1 col-xl-6 col-lg-6 col-md-12 col-12 mt-lg-0">
                <img src="{{ asset('backend') }}/assets/images/error/404-error-img.svg" alt="error" class="w-100">
            </div>
        </div>
    </section>
@endsection
