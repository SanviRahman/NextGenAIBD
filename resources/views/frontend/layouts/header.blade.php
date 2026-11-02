<!--Header Section Start-->
<header class="header-section">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo-menu">
                <a href="{{ route('home_Index') }}" class="logo">
                    <img src="{{ asset('/media/logo') . '/' . ($logo->frontlogo ?? 'frontlogo.png') }}" alt="NextGenAIBD logo" style="height: 45px">
                </a>
            </div>
            <div class="header-bar d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="menu-btn-wrapper"></div>
            <ul class="main-menu">
                <li class="{{ request()->routeIs('home_Index') ? 'active' : '' }}">
                    <a href="{{ route('home_Index') }}">Home</a>
                </li>
                <li
                    class="{{ request()->routeIs('frontend_Service_Index', 'frontend_Service_Single') ? 'active' : '' }}">
                    <a href="{{ route('frontend_Service_Index') }}">Service</a>
                </li>
                <li class="{{ request()->routeIs('frontend_Project_Index', 'frontend_Project_Single') ? 'active' : '' }}">
                    <a href="{{ route('frontend_Project_Index') }}">Project</a>
                </li>
                <li class="{{ request()->routeIs('frontend_Blog_Index', 'frontend_Blog_Single') ? 'active' : '' }}">
                    <a href="{{ route('frontend_Blog_Index') }}">Blog</a>
                </li>
                <!-- {{-- <li class="{{ request()->routeIs('frontend_Team_Index') ? 'active' : '' }}">
                    <a href="{{ route('frontend_Team_Index') }}">Team</a>
                </li> --}} -->
                <li class="{{ request()->routeIs('frontend_About_Index') ? 'active' : '' }}">
                    <a href="{{ route('frontend_About_Index') }}">About</a>
                </li>
                <!-- <li class="{{ request()->routeIs('frontend_Apply_Index') ? 'active' : '' }}">
                    <a href="{{ route('frontend_Apply_Index') }}">Apply</a>
                </li> -->
                <li class="{{ request()->routeIs('frontend_Course_Index', 'frontend_Course_Single', 'frontend_MySelf_Index', 'frontend_Team_Index', 'frontend_Team_Single', 'frontend_Success_Story_Index', 'frontend_Contact_Index') ? 'active' : '' }}">
                    <a href="#" aria-haspopup="true">More<i class="fa fa-angle-down"></i></a>
                    <ul class="sub-menu">
                        <!-- <li>
                            <a href="{{ route('frontend_Course_Index') }}">Course</a>
                        </li> -->
                        <li>
                            <a href="{{ route('frontend_MySelf_Index') }}">About Me</a>
                        </li>
                        <li>
                            <a href="{{ route('frontend_Team_Index') }}">Team</a>
                        </li>
                        <li>
                            <a href="{{ route('frontend_Success_Story_Index') }}">Successful Story</a>
                        </li>
                        <!-- {{-- Academy uses the existing course catalog; no invented route or controller. --}}
                        <li>
                            <a href="{{ route('frontend_Course_Index') }}">Academy</a>
                        </li> -->
                        {{-- <li>
                            <a href="{{ route('home_Index') }}">Membership</a>
                        </li>
                        --}}
                        <li>
                            <a href="{{ route('frontend_Contact_Index') }}">Contact Us</a>
                        </li>
                    </ul>
                </li>
                @auth
                    <li class="btn-wrapper me-auto d-flex align-items-center">
                        <a href="{{ route('frontend_Contact_Index') }}" class="cmn--btn-3">
                            <span>Let's Talk</span>
                        </a>
                    </li>
                @endauth
                @guest
                    <li class="btn-wrapper me-auto d-flex align-items-center">
                        <a href="{{ route('login') }}" class="cmn--btn-3">
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="cmn--btn-3">
                            <span>Register</span>
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</header>
<!--Header Section End-->
