@extends('frontend.layouts.master')
@section('content')
    @php
        $logo = App\Models\Logo::first();
    @endphp
    <section class="container d-flex flex-column">
        <div class="mt-4 mb-4 row align-items-center justify-content-center g-0 min-vh-100">
            <div class="py- col-lg-8 col-md-8 py-xl-0">
                <!-- Card -->
                <div class="shadow card">
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
                    <!-- Card body -->
                    <div class="p-6 card-body">
                        <div class="mb-4">
                            {{-- <a href="{{ route('home_Index') }}">
                                <img style="height: 45px" src="{{ asset('/media/logo') . '/' . $logo->frontlogo }}"
                                    class="mb-4" alt="logo-icon">
                            </a> --}}
                            <h1 class="mb-1 fw-bold">Sign up</h1>
                            {{-- <span>Already have an account?
                                <a href="{{ route('login') }}" class="ms-1">Sign in</a>
                            </span> --}}
                        </div>
                        <!-- Form -->
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- Name -->
                                <div class="mb-3 col-lg-12">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" class="form-control" name="name" placeholder=""
                                        required="">
                                </div>
                                <!-- Username -->
                                {{-- <div class="mb-3 col-lg-6">
                                    <label for="username" class="form-label">User Name</label>
                                    <input type="text" id="username" class="form-control" name="username"
                                        placeholder="" required="">
                                </div> --}}
                                <!-- Email -->
                                <div class="mb-3 col-lg-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" placeholder=""
                                        required="">
                                </div>
                                <!-- country -->
                                <div class="mb-3 col-lg-6">
                                    <label for="countrySelect" class="form-label">country</label>
                                    <select name="country" class="form-select" id="countrySelect">
                                        <option selected disabled value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->country_name }}"
                                                data-phonecode="{{ $country->phonecode }}">
                                                {{ $country->country_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script>
                                        $(document).ready(function() {
                                            $('#countrySelect').change(function() {
                                                var selectedPhoneCode = $('option:selected', this).data('phonecode');
                                                $('#phoneCodeDisplay').text(selectedPhoneCode);
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- Number -->
                                <div class="mb-3 col-lg-6">
                                    <label for="number" class="form-label">Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="phoneCodeDisplay">00</span>
                                        <input type="number" id="number" class="form-control" name="number"
                                            placeholder="" required="">
                                    </div>
                                </div>
                                <hr style="border: none" class="m-0">
                                <!-- Password -->
                                <div class="mb-3 col-lg-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="" required="">
                                </div>
                                <!-- Password -->
                                <div class="mb-3 col-lg-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" placeholder="" required="">
                                </div>
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="agreeCheck">
                                    <label class="form-check-label" for="agreeCheck">
                                        <span>I agree to the
                                            <a class="text-dark" href="#"> Terms of Service </a> and
                                            <a class="text-dark" href="#">Privacy Policy.</a>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <!-- Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Create Free Account
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <p class="mb-3 asking">Do You Have Account ?</p>
                                <div class="d-grid">
                                    <a href="{{ route('login') }}" class="btn btn-danger">SING IN</a>
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
