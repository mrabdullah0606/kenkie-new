@extends('website.layouts.storefront')

@section('title', 'Contact Us - Kenkie')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        .contact-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            height: 100%;
        }
        .contact-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #f0fdf4;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        .contact-icon-box.whatsapp-box {
            background: #ecfdf5;
            color: #25d366;
        }
        .contact-icon-box.location-box {
            background: #eff6ff;
            color: #2563eb;
        }
        .contact-item:hover .contact-icon-box {
            transform: scale(1.08);
        }
        .contact-map-iframe {
            width: 100%;
            min-height: 480px;
            height: 100%;
            border: 0;
            border-radius: 16px;
        }
        .social-circle-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .social-circle-btn:hover {
            background: #22c55e;
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('body')
    <!-- Loader Start -->
    <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- Loader End -->

    <!-- Header Start -->
    @include('website.includes.home-header')
    <!-- Header End -->

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Contact Us</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Start -->
    <section class="section-b-space">
        <div class="container-fluid-lg">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-8 col-lg-10">
                    <h2 class="fw-bold mb-2 text-uppercase text-dark">CONTACT US</h2>
                    <p class="text-content fs-5">For any Inquiries please contact us at</p>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Left Details Box -->
                <div class="col-lg-5">
                    <div class="contact-card d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-4 text-dark">Get In Touch</h4>

                            <!-- Email -->
                            <div class="contact-item d-flex align-items-start gap-3 mb-4">
                                <div class="contact-icon-box">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Official Inquiries Email:</span>
                                    <a href="mailto:info@kenkie.com" class="fw-bold text-dark fs-5 text-decoration-none">
                                        info@kenkie.com
                                    </a>
                                </div>
                            </div>

                            <!-- Phone / WhatsApp -->
                            <div class="contact-item d-flex align-items-start gap-3 mb-4">
                                <div class="contact-icon-box whatsapp-box">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">WhatsApp & Direct Phone:</span>
                                    <a href="https://wa.me/447898346397" target="_blank" rel="noopener noreferrer" class="fw-bold text-dark fs-5 text-decoration-none">
                                        07898346397
                                    </a>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="contact-item d-flex align-items-start gap-3 mb-4">
                                <div class="contact-icon-box location-box">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Registered Headquarters:</span>
                                    <span class="fw-semibold text-dark fs-6 d-block">
                                        Flat A, 51 Bescot Road, Walsall, WS2 9AD, United Kingdom
                                    </span>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Official External Links -->
                            <h6 class="fw-bold mb-3 text-dark">Our Official Links & Partner Platforms:</h6>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <a href="https://www.ebay.co.uk/usr/kenkie-official" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                                    <i class="fa-brands fa-ebay"></i> Official eBay Store
                                </a>
                                <a href="https://step4humanity.com/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid fa-heart"></i> Step 4 Humanity Charity
                                </a>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div>
                            <span class="text-muted small d-block mb-2">Connect with us on social media:</span>
                            <div class="d-flex align-items-center gap-2">
                                <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="Facebook">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="Instagram">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                                <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="YouTube">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                                <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="TikTok">
                                    <i class="fa-brands fa-tiktok"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Map Embed -->
                <div class="col-lg-7">
                    <div class="overflow-hidden rounded-4 border shadow-sm h-100" style="min-height: 480px;">
                        <iframe class="contact-map-iframe"
                                src="https://maps.google.com/maps?q=51+Bescot+Road+Walsall+WS2+9AD+UK&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->
@endsection
