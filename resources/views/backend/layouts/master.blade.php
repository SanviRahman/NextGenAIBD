<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Early capture listener covers legacy Blade image tags that do not use WebsiteMedia yet. --}}
    <script src="{{ asset('frontend/assets/js/sda-media-fallback.js') }}?v=1.0"></script> <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Codescandy">
    <!-- Favicon icon-->
    @php
        $logo = App\Models\Logo::first();
        // Show the actual authenticated admin, not whichever admin was created first.
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
    @endphp
    <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Support\WebsiteMedia::url('media/logo/' . ($logo->favicon ?? 'favicon.png')) }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- Libs CSS -->
    <link href="{{ asset('backend') }}/assets/fonts/feather/feather.css" rel="stylesheet">
    <link href="{{ asset('backend') }}/assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('backend') }}/assets/libs/%40mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="{{ asset('backend') }}/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/css/theme.min.css">
    <!-- CUSTOM CSS -->
    @include('backend.layouts.css')

    <title>Dashboard </title>

    <style>
        ::-webkit-scrollbar {
            width: 6px !important;
            height: 5px !important;
            background: rgb(194, 194, 194);
        }

        ::-webkit-scrollbar-thumb {
            background-color: #593CC1 !important;
            border-radius: 2px !important;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #7355dd !important;
        }
    </style>
</head>

<body>
    <!-- Wrapper -->
    <div id="db-wrapper">
        <!-- navbar vertical -->

        <!-- Sidebar -->
        @include('backend.layouts.side-bar')
        <!-- Sidebar -->

        <!-- Page Content -->
        <main id="page-content">


            <!-- Page Top Bar -->
            @include('backend.layouts.top-bar')
            <!-- Page Top Bar -->


            <!-- ---------------PAGE CONTENT -->
            @yield('content')
            <!-- ---------------PAGE CONTENT -->


        </main>
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
