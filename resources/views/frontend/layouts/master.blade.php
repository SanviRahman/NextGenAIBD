
<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Early capture listener covers legacy Blade image tags that do not use WebsiteMedia yet. --}}
    <script src="{{ asset('frontend/assets/js/sda-media-fallback.js') }}?v=1.0"></script>

    @php
        $seo = App\Models\Seo::first();
        $logo = App\Models\Logo::first();
        $admin = App\Models\Admin::first();
        $websiteInfo = App\Models\WebsiteInfo::first();

        // Only public Pixel IDs are rendered. CAPI tokens never leave the server.
        $activeMetaPixelIds = App\Models\MetaPixelIntegration::query()->active()->orderBy('id')->pluck('pixel_id')->values()->all();
        $metaConsentState = (string) request()->cookie('meta_marketing_consent', 'unknown');
        if (!in_array($metaConsentState, ['granted', 'denied'], true)) {
            $metaConsentState = 'unknown';
        }
        $metaPageEvents = [];
        if (request()->routeIs('frontend_Service_Single')) {
            $metaPageEvents[] = ['event_name' => 'ViewContent', 'custom_data' => ['content_category' => 'Service']];
        } elseif (request()->routeIs('frontend_Project_Single')) {
            $metaPageEvents[] = ['event_name' => 'ViewContent', 'custom_data' => ['content_category' => 'Project']];
        } elseif (request()->routeIs('frontend_Blog_Single')) {
            $metaPageEvents[] = ['event_name' => 'ViewContent', 'custom_data' => ['content_category' => 'Blog']];
        } elseif (request()->routeIs('frontend_Course_Single')) {
            $metaPageEvents[] = ['event_name' => 'ViewContent', 'custom_data' => ['content_category' => 'Course']];
        } elseif (request()->routeIs('frontend_Contact_Index')) {
            $metaPageEvents[] = ['event_name' => 'Contact', 'custom_data' => ['content_name' => 'Contact page']];
        }
        $metaServerConfirmedEvents = session('meta_pixel_events', []);
    @endphp

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- NextGenAIBD SEO Metadata --}}
    <meta name="keywords" content="@yield('meta_keywords', optional($seo)->keywords ?? 'NextGenAIBD')" />
    <meta name="description" content="@yield('meta_description', optional($seo)->description ?? 'NextGenAIBD')" />
    <meta name="author" content="NextGenAIBD">

    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- GTM-style dataLayer lifecycle starts early; no GTM container ID is required for DataLayer Checker. --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.__NEXTGEN_META_GTM_JS_ID = (window.crypto && typeof window.crypto.randomUUID === 'function')
            ? window.crypto.randomUUID()
            : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0;
                return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
            });
        window.dataLayer.push({ 'gtm.start': Date.now(), event: 'gtm.js', event_id: window.__NEXTGEN_META_GTM_JS_ID });
        window.NEXTGEN_META = {
            pixelIds: @json($activeMetaPixelIds),
            consent: @json($metaConsentState),
            eventUrl: @json(route('tracking.meta.events')),
            consentUrl: @json(route('tracking.meta.consent')),
            csrfToken: @json(csrf_token()),
            pageEvents: @json($metaPageEvents),
            serverConfirmedEvents: @json(is_array($metaServerConfirmedEvents) ? $metaServerConfirmedEvents : []),
            gtmJsEventId: window.__NEXTGEN_META_GTM_JS_ID
        };
    </script>

    {{-- Open Graph Metadata --}}
    <meta property="og:title" content="@yield('title', 'NextGenAIBD')" />
    <meta property="og:description" content="@yield('meta_description', optional($seo)->description ?? 'NextGenAIBD')" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ \App\Support\WebsiteMedia::url('media/logo/' . ($logo->frontlogo ?? 'frontlogo.png')) }}" />

    {{-- Twitter Metadata --}}
    <meta name="twitter:site" content="@NextGenAIBD" />
    <meta name="twitter:title" content="@yield('meta_title', optional($seo)->title ?? 'NextGenAIBD')" />
    <meta name="twitter:description" content="@yield('meta_description', optional($seo)->description ?? 'NextGenAIBD')" />
    <meta name="twitter:keywords" content="@yield('meta_keywords', optional($seo)->keywords ?? 'NextGenAIBD')" />
    <meta name="twitter:image" content="{{ \App\Support\WebsiteMedia::url('media/logo/' . ($logo->frontlogo ?? 'frontlogo.png')) }}" />

    {{-- Browser Title --}}
    <title>@yield('title', 'NextGenAIBD')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ \App\Support\WebsiteMedia::url('media/logo/' . ($logo->favicon ?? 'favicon.png')) }}">

    <!-- Bootstrap min css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/bootstrap.min.css">

    <!-- Odometer css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/odometer.css">

    <!-- Animate css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/animate.css">

    <!-- Magnific Popup css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/magnific-popup.css">

    <!-- All min css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/all.min.css">

    <!-- Owl Carousel min css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/owl.carousel.min.css">

    @stack('homepage_hero_styles')

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/main.css">

    @stack('homepage_hero_after_main')

    {{-- Footer theme for all frontend pages --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-footer-dark.css') }}?v=1.1">

    {{-- Independent dark navbar skin --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-navbar-dark.css') }}?v=1.0">

    {{-- Page-only dark skin --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-inner-pages-dark.css') }}?v=1.0">
    {{-- Homepage portfolio cards and the project live-site action. --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-projects.css') }}?v=1.2">
    {{-- Meta consent controls (tracking scripts are loaded only after marketing consent). --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/meta-consent.css') }}?v=1.0">

</head>

<body class="{{ request()->routeIs('home_Index') ? 'sda-homepage' : 'sda-inner-dark' }}">

    <!-- Scroll Top Bottom -->
    <div id="progress">
        <span id="valiu">
            <img src="{{ asset('frontend') }}/assets/images/logo/top.png" alt="img-top">
        </span>
    </div>
    <!-- Scroll Top Bottom -->

    <!-- Preloader -->
    {{-- <div id="preloader">
        <div id="loader"></div>
    </div> --}}
    <!-- Preloader -->

    <!-- Header -->
    @include('frontend.layouts.header')
    <!-- Header -->

    <!-- CONTENT AREA -->
    @yield('content')
    <!-- CONTENT AREA -->

    <!-- Footer -->
    @include('frontend.layouts.footer')
    <!-- Footer -->

    <!-- Jquery 3 6 0 Min Js -->
    <script src="{{ asset('frontend') }}/assets/js/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap bundle Js -->
    <script src="{{ asset('frontend') }}/assets/js/bootstrap.bundle.js"></script>

    <!-- Viewport Jquery Js -->
    <script src="{{ asset('frontend') }}/assets/js/viewport.jquery.js"></script>

    <!-- Odometer min Js -->
    <script src="{{ asset('frontend') }}/assets/js/odometer.min.js"></script>

    <!-- Isotope min pkgd Js -->
    <script src="{{ asset('frontend') }}/assets/js/isotope.pkgd.min.js"></script>

    <!-- Wow min Js -->
    <script src="{{ asset('frontend') }}/assets/js/wow.min.js"></script>

    <!-- Magnific Popup Js -->
    <script src="{{ asset('frontend') }}/assets/js/jquery.magnific-popup.min.js"></script>

    <!-- Owl carousel Js -->
    <script src="{{ asset('frontend') }}/assets/js/owl.carousel.js"></script>

    <!-- Owl carousel min Js -->
    <script src="{{ asset('frontend') }}/assets/js/owl.carousel.min.js"></script>

    <!-- Main Js -->
    <script src="{{ asset('frontend') }}/assets/js/main.js"></script>

    @stack('homepage_hero_scripts')

    {{-- Meta Pixel + CAPI + GTM-style dataLayer lifecycle events. --}}
    @include('frontend.partials.meta-pixel-tracking')

</body>

</html>
