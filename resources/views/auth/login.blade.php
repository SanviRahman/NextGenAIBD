@extends('frontend.layouts.master')
@section('content')
    @php
        $logo = App\Models\Logo::first();
    @endphp
    <section class="container d-flex flex-column py-5">
        <div class="mt-5 mb-5 row align-items-center justify-content-center g-0 min-vh-50">
            <div class="py- col-lg-5 col-md-8 py-xl-0">
                <!-- Card -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="background: #DA1D25; color:#ffffff">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ session::get('success') }}
                    </div>
                @endif
                <div class="shadow card ">
                    <!-- Card body -->
                    <div class="p-6 card-body">
                        <div class="mb-4">
                            {{-- <a href="{{ route('home_Index') }}">
                                <img style="height: 45px" src="{{ asset('/media/logo') . '/' . $logo->frontlogo }}"
                                    class="mb-4" alt="logo-icon">
                            </a> --}}
                            <h1 class="mb-1 fw-bold">Sign in</h1>
                            <span>Don’t have an account?
                                <a href="{{ route('register') }}" class="ms-1">Sign up</a>
                            </span>
                        </div>
                        <!-- Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Username or email</label>
                                <input type="email" id="email" class="form-control" name="email" placeholder=" "
                                    required="">
                            </div>
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="" required="">
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-4 d-lg-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberme">
                                    <label class="form-check-label " for="rememberme">Remember me</label>
                                </div>
                                <div>
                                    <a href="#">Forgot your password?</a>
                                </div>
                            </div>
                            <div>
                                <!-- Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary ">Sign in</button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <p class="mb-3 asking">Do You Have Account ?</p>
                                <div class="d-grid">
                                    <a href="{{ route('register') }}" class="btn btn-danger">CREATE NEW ACCOUNT</a>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="mt-4 text-center">
                                <!--Facebook-->
                                <a href="#" class="btn-social btn-social-outline btn-facebook">
                                    <i class="mdi mdi-facebook fs-4"></i>
                                </a>
                                <!--Twitter-->
                                <a href="#" class="btn-social btn-social-outline btn-twitter">
                                    <i class="mdi mdi-twitter fs-4"></i>
                                </a>
                                <!--LinkedIn-->
                                <a href="#" class="btn-social btn-social-outline btn-linkedin">
                                    <i class="mdi mdi-linkedin"></i>
                                </a>
                                <!--GitHub-->
                                <a href="#" class="btn-social btn-social-outline btn-github">
                                    <i class="mdi mdi-github"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
