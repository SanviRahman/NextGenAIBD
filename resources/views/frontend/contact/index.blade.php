@extends('frontend.layouts.master')
@section('title', 'Contact | NextGenAIBD')
@section('content')
    @php
        $contactAddress = trim((string) data_get($websiteInfo, 'address'));
        $contactEmail = trim((string) data_get($websiteInfo, 'email'));
        $contactPhone = trim((string) data_get($websiteInfo, 'number'));
        $contactWhatsapp = trim((string) data_get($websiteInfo, 'wattsup_number'));
        $contactPhoneHref = preg_replace('/[^+0-9]/', '', $contactPhone);
        $contactWhatsappUrl = $websiteInfo?->whatsappUrl();
        $contactSocialLinks = $websiteInfo?->socialLinks() ?? [];
        $contactDescription = trim((string) data_get($websiteInfo, 'sort_descroption'));
        $contactImage = trim((string) data_get($websiteInfo, 'image'));
        $contactImageUrl = $contactImage !== ''
            ? \App\Support\WebsiteMedia::url('media/banner/' . basename($contactImage))
            : null;
    @endphp

    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">Contact Us</h1>
                <ul class="right-content">
                    <li><a href="{{ route('home_Index') }}">Home</a></li>
                    <li>Contact Us</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="contact-section pt-80 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 wow bounceInUp" data-wow-duration="1.5s"
                    style="visibility: visible; animation-duration: 1.5s; animation-name: bounceInUp;">
                    <div class="contact-left">
                        @if ($contactImageUrl)
                            <div class="items">
                                <img src="{{ $contactImageUrl }}" alt="NextGenAIBD company image"
                                    class="img-fluid rounded" style="max-width: 320px; max-height: 150px; object-fit: contain;"
                                    loading="lazy" decoding="async">
                            </div>
                        @endif

                        @if ($contactDescription !== '')
                            <div class="items">
                                <h4 class="title">NextGenAIBD</h4>
                                <p>{{ $contactDescription }}</p>
                            </div>
                        @endif

                        @if ($contactAddress !== '')
                            <div class="items">
                                <h4 class="title">Office Address</h4>
                                <ul class="address">
                                    <li>{!! nl2br(e($contactAddress)) !!}</li>
                                </ul>
                            </div>
                        @endif

                        @if ($contactPhone !== '' || $contactEmail !== '' || ($contactWhatsapp !== '' && $contactWhatsappUrl))
                            <div class="items">
                                <h4 class="title">Contact Info</h4>
                                <ul class="info">
                                    @if ($contactPhone !== '' && $contactPhoneHref !== '')
                                        <li>Phone: <a href="tel:{{ $contactPhoneHref }}">{{ $contactPhone }}</a></li>
                                    @endif
                                    @if ($contactWhatsapp !== '' && $contactWhatsappUrl)
                                        <li>WhatsApp: <a href="{{ $contactWhatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $contactWhatsapp }}</a></li>
                                    @endif
                                    @if ($contactEmail !== '')
                                        <li>Email: <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                        @if (!empty($contactSocialLinks))
                            <div class="items">
                                <h4 class="title">Follow Us</h4>
                                @include('frontend.layouts.partials.social-links', [
                                    'socialLinks' => $contactSocialLinks,
                                    'listClass' => 'follow',
                                ])
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 wow bounceInUp" data-wow-duration="2.5s"
                    style="visibility: visible; animation-duration: 2.5s; animation-name: bounceInUp;">
                    <div class="form_area">
                        <h3 class="title">Get a quote</h3>
                        <form id="form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-control">
                                        <input type="text" id="Name" placeholder="Your Name...">
                                        <small>Error message</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-control">
                                        <input type="text" id="semail" placeholder="Your Email...">
                                        <small>Error message</small>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-control">
                                        <input type="number" id="budget" placeholder="Phone Number...">
                                        <small>Error message</small>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-control">
                                        <textarea name="message" id="message" cols="10" rows="5" placeholder="Project Details..."></textarea>
                                        <small>Error message</small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="cmn--btn">
                                <span>Submit Now</span>
                            </button>
                            <div class="thank_you">
                                <p>Your Message is successfully sent !</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
