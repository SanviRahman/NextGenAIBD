<!DOCTYPE html>
<html lang="en">

<head> <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Codescandy">
    <!-- Favicon icon-->
    @php
        $logo = App\Models\Logo::first();
        $admin = App\Models\Admin::first();
    @endphp
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/media/logo') . '/' . $logo->favicon }}">
    <!-- Libs CSS -->
    <link href="{{ asset('backend') }}/assets/fonts/feather/feather.css" rel="stylesheet">
    <link href="{{ asset('backend') }}/assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('backend') }}/assets/libs/%40mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="{{ asset('backend') }}/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/css/theme.min.css">
    <title>Dashboard Login</title>
</head>

<body>
    <!-- Wrapper -->
    <div id="db-wrapper">

        @php
            $logo = App\Models\Logo::first();
        @endphp
        <section class="container  mt-5 pt-5">
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
                    @if (Session::get('error_message'))
                        <div class="alert alert-danger">
                            {{ session::get('error_message') }}
                        </div>
                    @endif
                    <div class="shadow card">
                        <!-- Card body -->
                        <div class="p-6 card-body">
                            <div class="mb-4">
                                <a href="{{ route('home_Index') }}">
                                    <img style="height: 45px" src="{{ asset('/media/logo') . '/' . $logo->frontlogo }}"
                                        class="mb-4" alt="logo-icon">
                                </a>
                                <h1 class="mb-1 fw-bold">Sign in</h1>
                                <span>Don’t have an account?
                                    <a href="{{ route('admin_Register') }}" class="ms-1">Sign up</a>
                                </span>
                            </div>
                            <!-- Form -->
                            <form method="POST" action="{{ route('admin_Login') }}">
                                @csrf
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Username or email</label>
                                    <input type="text" id="email" class="form-control" name="login"
                                        placeholder="Email address here" required="">
                                </div>
                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="**************" required="">
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

    </div>
    <!-- Script -->

    <!-- Libs JS -->
    <script src="{{ asset('backend') }}/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('backend') }}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('backend') }}/assets/libs/simplebar/dist/simplebar.min.js"></script>
    <!-- Theme JS -->
    <script src="{{ asset('backend') }}/assets/js/theme.min.js"></script>
    <script src="{{ asset('backend') }}/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('backend') }}/assets/js/vendors/chart.js"></script>
    <script src="{{ asset('backend') }}/assets/libs/flatpickr/dist/flatpickr.min.js"></script>
    <script src="{{ asset('backend') }}/assets/js/vendors/flatpickr.js"></script>

</body>

</html>
