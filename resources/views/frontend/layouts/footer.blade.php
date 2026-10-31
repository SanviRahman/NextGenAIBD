<!--Footer Section Start-->
<section class="footer-section sda-footer-dark">
    {{-- Decorative dotted corner graphics intentionally removed. --}}
    <div class="container">
        <div class="footer-top pt-120 pb-80">
            <div class="row g-4">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <div class="widget-items">
                        <div class="footer-head">
                            <a href="{{ route('home_Index') }}" class="footer-logo1">
                                <img src="{{ asset('/media/logo') . '/' . $logo->frontlogo }}" alt="logo-img"
                                    style="height: 60px;">
                            </a>
                        </div>
                        <div class="content-area">
                            <p>
                                {{ $websiteInfo->sort_descroption }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 ">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">
                                Quick Links
                            </h4>
                        </div>
                        <div class="content-area">
                            <ul class="quick-link">
                                <li>
                                    <a href="protfolio.html">
                                        Project
                                    </a>
                                </li>
                                <li>
                                    <a href="about.html">
                                        About us
                                    </a>
                                </li>
                                <li>
                                    <a href="blog-grid.html">
                                        Our Blog
                                    </a>
                                </li>
                                <li>
                                    <a href="team.html">
                                        Our Team
                                    </a>
                                </li>
                                <li>
                                    <a href="contact.html">
                                        Contact
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 ">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">
                                Company
                            </h4>
                        </div>
                        <div class="content-area">
                            <ul class="footer-contact-list">
                                <li>
                                    <a href="about.html">
                                        About us
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        How I Work
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        Careers
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        Customer Reviews
                                    </a>
                                </li>
                                <li>
                                    <a href="about.html">
                                        FeedBack
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">
                                Say Hello
                            </h4>
                        </div>
                        <ul class="hellow">
                            <li>
                                <a href="#0">
                                    {{ $websiteInfo->address }}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    {{ $websiteInfo->email }}
                                </a>
                            </li>
                            <li>
                                <a href="#0">
                                    {{ $websiteInfo->number }}
                                </a>
                            </li>
                        </ul>
                        <div class="content-area">
                            <ul class="footer-social">
                                <li>
                                    <a href="#0">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#0">
                                        <i class="fab fa-vimeo"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright &copy;2023
                <a href="http://appupnext.com/" target="_blank" class="text-base">AppUpNext.</a> All Rights Reserved
            </p>
            <ul class="footer-bottom-link">
                <li>
                    <a href="#0">
                        Support
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend_Service_Index') }}">
                        Services
                    </a>
                </li>
                <li>
                    <a href="#0">
                        Terms & Condition
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>
<!--Footer Section End-->
