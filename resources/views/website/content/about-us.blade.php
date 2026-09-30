@extends('website.layouts.storefront')

@section('title', 'About Us - A Company You Can Trust - Kenkie')
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        .business-details-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #22c55e;
            border-radius: 12px;
            padding: 24px;
        }
        .team-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 24px 16px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            height: 100%;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(34, 197, 94, 0.12);
            border-color: #22c55e;
        }
        .team-avatar-wrapper {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin: 0 auto 16px;
            border: 3px solid #22c55e;
            overflow: hidden;
            background: #e2e8f0;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }
        .team-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .value-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 24px;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }
        .value-card:hover {
            border-color: #22c55e;
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.1);
        }
        .value-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #f0fdf4;
            color: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
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
                        <h2>About Us</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">About Us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- 1. A Company You Can Trust Section -->
    <section class="section-b-space">
        <div class="container-fluid-lg">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-xl-9 col-lg-10">
                    <h2 class="fw-bold mb-3 text-uppercase text-dark">A Company You Can Trust</h2>
                    <p class="text-content fs-5 mb-4">
                        We believe quality and distinctiveness. Our brand has introduced itself as a reliable, continuous, dynamic, and customer-friendly seller in the world of online selling sites. An e-commerce store which provides unique representation in electronics, home accessories etc.
                    </p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <p class="text-content fs-6 mb-4">
                        The brand has developed rapidly in a short period. Our goal is to provide customers with a “reassuring” experience as we always strive to provide the highest quality products. KENKIE’s main focus is customer service, quality, value for money, and innovation from the very start.
                    </p>

                    <div class="business-details-card mt-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-building text-success me-2"></i> The Business Details:</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small"><strong>Company Legal Entity:</strong></p>
                                <p class="mb-2 fw-semibold text-dark">KENKIE LTD UK based private registered company since 2018</p>
                                <p class="mb-1 text-muted small"><strong>Company Number:</strong></p>
                                <p class="mb-0 fw-semibold text-dark">15579321 &middot; ICO Registered: 11147305</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small"><strong>Company Registered Address:</strong></p>
                                <p class="mb-2 fw-semibold text-dark">Flat A, 51 Bescot Road Walsall WS2 9AD United Kingdom</p>
                                <p class="mb-1 text-muted small"><strong>Official Contact Email:</strong></p>
                                <p class="mb-0 fw-semibold"><a href="mailto:info@kenkie.com" class="theme-color text-decoration-none">info@kenkie.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Our Values Section -->
    <section class="section-b-space bg-light py-5">
        <div class="container-fluid-lg">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-8">
                    <h2 class="fw-bold mb-2 text-uppercase text-dark">OUR VALUES</h2>
                    <p class="text-muted">Built on principles of efficiency, customer-first service, and uncompromised quality.</p>
                </div>
            </div>

            <div class="row g-4 row-cols-lg-3 row-cols-md-2 row-cols-1 justify-content-center">
                <div class="col">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Free UK Delivery</h5>
                        <p class="text-content mb-0">Kenkie provides you FREE DELIVERY service all over united kingdom.</p>
                    </div>
                </div>

                <div class="col">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa-solid fa-medal"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Quality & Experience</h5>
                        <p class="text-content mb-0">We offer the best quality product with relevant knowledge and experience. Our experienced team provide you with fine customer service along with quick response to problems. here you will get a reliable refund & exchange policy.</p>
                    </div>
                </div>

                <div class="col">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Product Warranty</h5>
                        <p class="text-content mb-0">We also offer our customers product warranty. Their prices are also reasonable. The tech used in our electronic products is also up to the mark.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Meet the Team Section -->
    <section class="section-b-space py-5">
        <div class="container-fluid-lg">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-8">
                    <h2 class="fw-bold mb-2 text-uppercase text-dark">MEET THE TEAM</h2>
                    <p class="text-muted">The dedicated professionals driving Kenkie's commitment to excellence.</p>
                </div>
            </div>

            <div class="row g-4 row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-1">
                <!-- Ashfaq Ahmad -->
                <div class="col">
                    <div class="team-card">
                        <div class="team-avatar-wrapper">
                            <img src="{{ asset('assets/images/team-ashfaq.jpg') }}" alt="Ashfaq Ahmad" class="team-avatar-img">
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">ASHFAQ AHMAD</h5>
                        <p class="mb-2 text-muted small"><a href="mailto:ashfaq@kenkie.com" class="theme-color text-decoration-none">ashfaq@kenkie.com</a></p>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">Director &amp; CEO</span>
                    </div>
                </div>

                <!-- Naveed Azhar -->
                <div class="col">
                    <div class="team-card">
                        <div class="team-avatar-wrapper">
                            <img src="{{ asset('assets/images/team-naveed.jpg') }}" alt="Naveed Azhar" class="team-avatar-img">
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">NAVEED AZHAR</h5>
                        <p class="mb-2 text-muted small"><a href="mailto:naveed@kenkie.com" class="theme-color text-decoration-none">naveed@kenkie.com</a></p>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">Operational Manager</span>
                    </div>
                </div>

                <!-- Iftikhar Anwar -->
                <div class="col">
                    <div class="team-card">
                        <div class="team-avatar-wrapper">
                            <img src="{{ asset('assets/images/team-iftikhar.jpg') }}" alt="Iftikhar Anwar" class="team-avatar-img">
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">IFTIKHAR ANWAR</h5>
                        <p class="mb-2 text-muted small"><a href="mailto:iftikhar@kenkie.com" class="theme-color text-decoration-none">iftikhar@kenkie.com</a></p>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1">Compliance Manager</span>
                    </div>
                </div>

                <!-- Tahreem Tahir -->
                <div class="col">
                    <div class="team-card">
                        <div class="team-avatar-wrapper">
                            <img src="{{ asset('assets/images/team-tahreem.jpg') }}" alt="Tahreem Tahir" class="team-avatar-img">
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">TAHREEM TAHIR</h5>
                        <p class="mb-2 text-muted small"><a href="mailto:tahreem@kenkie.com" class="theme-color text-decoration-none">tahreem@kenkie.com</a></p>
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1">Software Engineer</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Offer / CTA Banner -->
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
        <div class="container-fluid-lg py-3">
            <h2 class="fw-bold mb-3 text-white text-uppercase">CHECKOUT WHAT WE CAN OFFER YOU</h2>
            <p class="mb-4 text-white opacity-90 fs-5">For more information, check out our latest products and holiday deals at KENKIE.COM</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('shop.category') }}" class="btn btn-light btn-lg fw-bold px-5 py-3 shadow text-success bg-white">
                    <i class="fa-solid fa-bag-shopping me-2"></i> Shop Now
                </a>
                <a href="{{ route('contact.us') }}" class="btn btn-light btn-lg fw-bold px-5 py-3 shadow text-success bg-white">
                    <i class="fa-solid fa-envelope me-2"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->
@endsection
