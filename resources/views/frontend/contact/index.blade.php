@extends('frontend.layouts.master')
@section('title', 'Contact | Sunnah digital Agency')
@section('content')
    <section class="banner-section breadcumd-banner">
        <div class="container">
            <div class="breadcumd-wrapper">
                <h1 class="left-content">
                    Contact US
                </h1>
                <ul class="right-content">
                    <li>
                        <a href="{{ route('home_Index') }}">
                            Home
                        </a>
                    </li>
                    <li>
                        Contact US
                    </li>
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
                        <div class="items">
                            <h4 class="title">
                                Office Address
                            </h4>
                            <ul class="address">
                                <li>#28, 10th Mai***n, 100 Feet Rd, </li>
                                <li>HRBR Layout 1st*****, HRBR </li>
                                <li>Layout,Kalyan Nagar, </li>
                                <li>Mancity, England</li>
                            </ul>
                        </div>
                        <div class="items">
                            <h4 class="title">
                                Contact Info
                            </h4>
                            <ul class="info">
                                <li>Phone:<a href="#0"> (+91) 636359***</a></li>
                                <li>Email:<a href="#0"> example***t.com</a></li>
                            </ul>
                        </div>
                        <div class="items">
                            <h4 class="title">
                                Follow Us
                            </h4>
                            <ul class="follow">
                                <li><a href="#0"><i class="fab fa-facebook"></i></a></li>
                                <li><a href="#0"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#0"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#0"><i class="fab fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow bounceInUp" data-wow-duration="2.5s"
                    style="visibility: visible; animation-duration: 2.5s; animation-name: bounceInUp;">
                    <div class="form_area">
                        <h3 class="title">
                            Get a quote
                        </h3>
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
                                <span>
                                    Submit Now
                                </span>
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
