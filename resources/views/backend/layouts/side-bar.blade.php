<nav class="navbar-vertical navbar">
    <div class="vh-100" data-simplebar>
        <!-- Brand logo -->
        <a class="navbar-brand" href="{{ route('backend_Home_Index') }}">
            <img style="height: 40px" src="{{ \App\Support\WebsiteMedia::url('media/logo/' . ($logo->frontlogo ?? 'frontlogo.png')) }}" alt="">
        </a>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column" id="sideNavbar">
            <li class="nav-item bg-dark">
                <div style="font-size: 10px" class="text-white navbar-heading">ADMIN PANEL</div>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs('backend_Home_Index') ? 'active_menu' : '' }}"
                    href="{{ route('backend_Home_Index') }}">
                    <i class="fa-solid fa-house-signal me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item bg-dark">
                <div style="font-size: 10px" class="text-white navbar-heading">WEBSITE INFO</div>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Abouts_Index', 'backend_Abouts_Search', 'backend_Abouts_Create', 'backend_Abouts_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Abouts_Index') }}">
                    <i class="fa-solid fa-lock me-2"></i>
                    Abouts
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Admins_Index', 'backend_Admins_Search', 'backend_Admins_Create', 'backend_Admins_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Admins_Index') }}">
                    <i class="fa-solid fa-user me-2"></i>
                    Admins
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Website_Info_Index', 'backend_Website_Info_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Website_Info_Index') }}">
                    <i class="fa-solid fa-lock me-2"></i>
                    Website InFo
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Personal_Info_Index', 'backend_Personal_Info_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Personal_Info_Index') }}">
                    <i class="fa-solid fa-user-gear me-2"></i>
                    Personal InFo
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_ApplyCourse_Index', 'backend_ApplyCourse_Active', 'backend_ApplyCourse_Pending', 'backend_ApplyCourse_Drafts', 'backend_ApplyCourse_Search', 'backend_ApplyCourse_Create', 'backend_ApplyCourse_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_ApplyCourse_Index') }}">
                    <i class="fa-solid fa-user-gear me-2"></i>
                    Course Apply
                </a>
            </li>
            {{-- Homepage-only management pages: grouped without changing their existing routes. --}}
            @php
                $isHomeManagementPage = request()->routeIs(
                    'admin.hero.*',
                    'admin.spotlight.*',
                    'admin.services.*',
                    'admin.consultation.*',
                    'admin.ratings.*',
                    'admin.benefits.*',
                    'admin.clients.*',
                    'admin.testimonials.*',
                    'admin.gallery.*'
                );
            @endphp
            <li class="nav-item hm-sidebar-group">
                <a class="nav-link text-dark hm-sidebar-toggle {{ $isHomeManagementPage ? 'hm-sidebar-current' : 'collapsed' }}"
                   href="#homeManagementSubmenu"
                   role="button"
                   data-bs-toggle="collapse"
                   aria-expanded="{{ $isHomeManagementPage ? 'true' : 'false' }}"
                   aria-controls="homeManagementSubmenu">
                    <i class="fa-solid fa-house-laptop me-2" aria-hidden="true"></i>
                    <span>Home Management</span>
                    <i class="fa-solid fa-chevron-down hm-sidebar-arrow ms-auto" aria-hidden="true"></i>
                </a>
                <div id="homeManagementSubmenu"
                     class="collapse {{ $isHomeManagementPage ? 'show' : '' }} hm-sidebar-panel">
                    <ul class="nav flex-column" aria-label="Home Management Pages">
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.hero.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.hero.index') }}">
                                <i class="fa-solid fa-images me-2"></i> Hero Slides
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.spotlight.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.spotlight.index') }}">
                                <i class="fa-solid fa-film me-2"></i> Spotlight
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.services.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.services.index') }}">
                                <i class="fa-solid fa-layer-group me-2"></i> Services
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.consultation.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.consultation.index') }}">
                                <i class="fa-solid fa-comments me-2"></i> Free Consultation
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.ratings.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.ratings.index') }}">
                                <i class="fa-solid fa-star me-2"></i> Client Ratings
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.benefits.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.benefits.index') }}">
                                <i class="fa-solid fa-circle-check me-2"></i> Why Choose Us / Benefits
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.clients.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.clients.index') }}">
                                <i class="fa-solid fa-handshake me-2"></i> Our Clients
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.testimonials.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.testimonials.index') }}">
                                <i class="fa-solid fa-video me-2"></i> Video Testimonials
                            </a>
                        </li>
                        <li class="border nav-item">
                            <a class="nav-link text-dark {{ request()->routeIs('admin.gallery.*') ? 'active_menu' : '' }}"
                            href="{{ route('admin.gallery.index') }}">
                                <i class="fa-solid fa-camera-retro me-2"></i> Memories &amp; Moments
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item bg-dark">
                <div style="font-size: 10px" class="text-white navbar-heading">Service INFO</div>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Banner_Index', 'backend_Banner_Search', 'backend_Banner_Create', 'backend_Banner_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Banner_Index') }}">
                    <i class="fa-solid fa-lock me-2"></i>
                    Banner
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Blogs_Index', 'backend_Blogs_Active', 'backend_Blogs_Pending', 'backend_Blogs_Drafts', 'backend_Blogs_Search', 'backend_Blogs_Create', 'backend_Blogs_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Blogs_Index') }}">
                    <i class="fa-solid fa-blog me-2"></i>
                    Blogs
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Courses_Index', 'backend_Courses_Active', 'backend_Courses_Pending', 'backend_Courses_Drafts', 'backend_Courses_Search', 'backend_Courses_Create', 'backend_Courses_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Courses_Index') }}">
                    <i class="fa-regular fa-paper-plane me-2"></i>
                    Courses
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Country_Index', 'backend_Country_Active', 'backend_Country_Pending', 'backend_Country_Drafts', 'backend_Country_Search', 'backend_Country_Create', 'backend_Country_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Country_Index') }}">
                    <i class="fa-solid fa-flag-usa me-2"></i>
                    Country
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Membership_Index', 'backend_Membership_Active', 'backend_Membership_Pending', 'backend_Membership_Drafts', 'backend_Membership_Search', 'backend_Membership_Create', 'backend_Membership_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Membership_Index') }}">
                    <i class="fa-solid fa-user-gear me-2"></i>
                    Memberships
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Projects_Index', 'backend_Projects_Active', 'backend_Projects_Pending', 'backend_Projects_Drafts', 'backend_Projects_Search', 'backend_Projects_Create', 'backend_Projects_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Projects_Index') }}">
                    <i class="fa-solid fa-arrow-up-from-bracket me-2"></i>
                    Projects
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Services_Index', 'backend_Services_Active', 'backend_Services_Pending', 'backend_Services_Drafts', 'backend_Services_Search', 'backend_Services_Create', 'backend_Services_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Services_Index') }}">
                    <i class="fa-solid fa-arrow-up-from-bracket me-2"></i>
                    Services
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Success_Story_Index', 'backend_Success_Story_Search', 'backend_Success_Story_Create', 'backend_Success_Story_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Success_Story_Index') }}">
                    <i class="fa-solid fa-lock me-2"></i>
                    Success Story
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Teams_Index', 'backend_Teams_Active', 'backend_Teams_Pending', 'backend_Teams_Drafts', 'backend_Teams_Search', 'backend_Teams_Create', 'backend_Teams_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Teams_Index') }}">
                    <i class="fa-solid fa-user-plus me-2"></i>
                    Teams
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Users_Index', 'backend_Users_Search', 'backend_Users_Create', 'backend_Users_Edit']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Users_Index') }}">
                    <i class="fa-solid fa-users me-2"></i>
                    Users
                </a>
            </li>
            <li class="nav-item bg-dark">
                <div style="font-size: 10px" class="text-white navbar-heading">SETTINGS</div>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Settings_Logo']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Settings_Logo') }}">
                    <i class="fa-solid fa-wrench me-2"></i>
                    Appreance
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Website_Visitor']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Website_Visitor') }}">
                    <i class="fa-solid fa-street-view me-2"></i>
                    Visitors
                </a>
            </li>
            <li class="border nav-item">
                <a class="nav-link text-dark {{ request()->routeIs(['backend_Seo_Index']) ? 'active_menu' : '' }}"
                    href="{{ route('backend_Seo_Index') }}">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    SEO
                </a>
            </li>
            <br><br><br>
            {{-- <li class="nav-item">
                <a class="nav-link collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navCourses"
                    aria-expanded="false" aria-controls="navCourses">
                    <i class="nav-icon fe fe-book me-2"></i> Courses
                </a>
                <div id="navCourses" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                All Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Courses Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Category Single
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}
        </ul>
    </div>
</nav>
