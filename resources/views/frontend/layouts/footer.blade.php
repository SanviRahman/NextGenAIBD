<!--Footer Section Start-->
{{-- Full-width 5px accent divider above the global footer. --}}
<section class="footer-section sda-footer-dark" style="border-top: 5px solid #ea1d22;">
    @php
        $footerAddress = trim((string) data_get($websiteInfo, 'address'));
        $footerEmail = trim((string) data_get($websiteInfo, 'email'));
        $footerPhone = trim((string) data_get($websiteInfo, 'number'));
        $footerWhatsapp = trim((string) data_get($websiteInfo, 'wattsup_number'));
        $footerPhoneHref = preg_replace('/[^+0-9]/', '', $footerPhone);
        $footerWhatsappUrl = $websiteInfo?->whatsappUrl();
        $footerSocialLinks = $websiteInfo?->socialLinks() ?? [];
        $footerDescription = trim((string) data_get($websiteInfo, 'sort_descroption'));
    @endphp

    <div class="container">
        <div class="footer-top pt-120 pb-80">
            <div class="row g-4">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="widget-items">
                        <div class="footer-head">
                            <a href="{{ route('home_Index') }}" class="footer-logo1">
                                <img src="{{ asset('/media/logo') . '/' . ($logo->frontlogo ?? 'frontlogo.png') }}"
                                    alt="NextGenAIBD logo" class="sda-footer-brand-image">
                            </a>
                        </div>
                        @if ($footerDescription !== '')
                            <div class="content-area">
                                <p>{{ $footerDescription }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">Quick Links</h4>
                        </div>
                        <div class="content-area">
                            <ul class="quick-link">
                                <li><a href="{{ route('frontend_Project_Index') }}">Project</a></li>
                                <li><a href="{{ route('frontend_About_Index') }}">About us</a></li>
                                <li><a href="{{ route('frontend_Blog_Index') }}">Our Blog</a></li>
                                <li><a href="{{ route('frontend_Team_Index') }}">Our Team</a></li>
                                <li><a href="{{ route('frontend_Contact_Index') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">Company</h4>
                        </div>
                        <div class="content-area">
                            <ul class="footer-contact-list">
                                <li><a href="{{ route('frontend_About_Index') }}">About us</a></li>
                                <li><a href="#0">How I Work</a></li>
                                <li><a href="#0">Careers</a></li>
                                <li><a href="{{ route('home_Index') }}#client-testimonials">Customer Reviews</a></li>
                                <li><a href="{{ route('frontend_Contact_Index') }}">Feedback</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="widget-items">
                        <div class="footer-head">
                            <h4 class="title">Say Hello</h4>
                        </div>

                        <ul class="hellow">
                            @if ($footerAddress !== '')
                                <li>
                                    <a href="{{ route('frontend_Contact_Index') }}">{{ $footerAddress }}</a>
                                </li>
                            @endif

                            @if ($footerEmail !== '')
                                <li><a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></li>
                            @endif

                            @if ($footerPhone !== '' && $footerPhoneHref !== '')
                                <li><a href="tel:{{ $footerPhoneHref }}">{{ $footerPhone }}</a></li>
                            @endif

                            @if ($footerWhatsapp !== '' && $footerWhatsappUrl)
                                <li><a href="{{ $footerWhatsappUrl }}" target="_blank" rel="noopener noreferrer">WhatsApp: {{ $footerWhatsapp }}</a></li>
                            @endif
                        </ul>

                        <div class="content-area">
                            @include('frontend.layouts.partials.social-links', [
                                'socialLinks' => $footerSocialLinks,
                                'listClass' => 'footer-social',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Copyright &copy;{{ now()->year }}
                <a href="{{ route('home_Index') }}" class="text-base">NextGenAIBD</a>. All Rights Reserved
            </p>
            <ul class="footer-bottom-link">
                <li><a href="{{ route('frontend_Contact_Index') }}">Support</a></li>
                <li><a href="{{ route('frontend_Service_Index') }}">Services</a></li>
                <li><a href="#0">Terms &amp; Condition</a></li>
                @if(count($activeMetaPixelIds) > 0)
                    <li><button type="button" id="nextgen-meta-consent-manage" class="sda-footer-privacy-button" aria-controls="nextgen-meta-consent">Privacy choices</button></li>
                @endif
            </ul>
        </div>
    </div>
</section>
<!--Footer Section End-->
