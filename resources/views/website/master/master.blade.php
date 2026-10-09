@extends('website.layouts.storefront')

@section('title', 'Home')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')
@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        /* Section Spacing */
        .home-section-2 {
            margin-bottom: 25px;
        }
        .banner-section {
            margin-bottom: 30px;
        }
        .category-section-3 {
            padding-top: 32px !important;
            padding-bottom: 32px !important;
        }
        .product-section-3 {
            padding-top: 38px !important;
            padding-bottom: 38px !important;
        }
        .product-section-3 .title {
            margin-bottom: 24px !important;
        }
        .bank-section {
            padding-top: 34px !important;
            padding-bottom: 34px !important;
        }
        .service-section {
            padding-top: 38px !important;
            padding-bottom: 38px !important;
        }

        /* Hero Banner Styling */
        .home-section-2 .home-contain {
            min-height: 520px;
            display: flex;
            align-items: center;
        }
        .hero-content-wrap {
            max-width: 620px !important;
            padding: 20px 0 !important;
            position: relative;
            z-index: 2;
        }
        .hero-badge {
            background: #dcfce7 !important;
            color: #15803d !important;
            font-size: 13px !important;
            letter-spacing: 0.6px !important;
            border-radius: 6px !important;
            display: inline-block !important;
            font-weight: 700 !important;
        }
        .hero-title {
            font-size: 40px !important;
            font-weight: 900 !important;
            line-height: 1.18 !important;
            color: #000000 !important;
            margin-top: 12px !important;
            margin-bottom: 8px !important;
            letter-spacing: -0.5px !important;
        }
        .hero-subtitle {
            font-size: 19px !important;
            font-weight: 700 !important;
            color: #16a34a !important;
            margin-bottom: 10px !important;
        }
        .hero-desc {
            font-size: 16px !important;
            color: #1e293b !important;
            margin-bottom: 24px !important;
            line-height: 1.55 !important;
            max-width: 520px !important;
            font-weight: 500 !important;
        }
        .hero-btn {
            padding: 13px 30px !important;
            border-radius: 8px !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            box-shadow: 0 6px 18px rgba(34, 197, 94, 0.35) !important;
            transition: all 0.25s ease !important;
        }
        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(34, 197, 94, 0.45) !important;
        }

        /* Hero Slider Arrow Navigation Controls */
        .slider-animate {
            position: relative;
        }
        .hero-arrow {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 46px !important;
            height: 46px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.92) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            color: #0f172a !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 16px !important;
            cursor: pointer !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14) !important;
            transition: all 0.22s ease !important;
            padding: 0 !important;
        }
        .hero-arrow:hover {
            background: #22c55e !important;
            color: #ffffff !important;
            border-color: #22c55e !important;
            box-shadow: 0 6px 18px rgba(34, 197, 94, 0.4) !important;
            transform: translateY(-50%) scale(1.08) !important;
        }
        .hero-arrow::before,
        .hero-arrow::after,
        .hero-arrow:hover::before,
        .hero-arrow:hover::after,
        .hero-arrow:focus::before,
        .hero-arrow:focus::after {
            display: none !important;
            content: none !important;
            opacity: 0 !important;
        }
        .hero-arrow i {
            font-size: 16px !important;
            line-height: 1 !important;
            display: inline-block !important;
            margin: 0 !important;
        }
        .hero-prev {
            left: 24px !important;
        }
        .hero-next {
            right: 24px !important;
        }

        @media (max-width: 768px) {
            .home-section-2 .home-contain,
            .home-section-2 .home-contain.bg-size {
                min-height: 480px !important;
                background-position: 85% center !important;
            }
            .home-section-2 .home-contain::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.82) 55%, rgba(255, 255, 255, 0.2) 100%);
                pointer-events: none;
                z-index: 1;
            }
            .home-section-2 .home-detail {
                z-index: 2 !important;
                position: relative !important;
                padding-left: 16px !important;
                padding-right: 16px !important;
            }
            .hero-title {
                font-size: 28px !important;
                line-height: 1.22 !important;
            }
            .hero-subtitle {
                font-size: 16px !important;
            }
            .hero-desc {
                font-size: 14px !important;
            }
            .hero-arrow {
                width: 36px !important;
                height: 36px !important;
                font-size: 13px !important;
                background: rgba(255, 255, 255, 0.9) !important;
            }
            .hero-prev {
                left: 10px !important;
            }
            .hero-next {
                right: 10px !important;
            }
        }

        @media (max-width: 360px) {
            .home-section-2 .home-contain,
            .home-section-2 .home-contain.bg-size {
                min-height: 440px !important;
                background-position: 88% center !important;
            }
            .hero-content-wrap {
                padding: 10px 0 !important;
            }
            .hero-title {
                font-size: 22px !important;
                line-height: 1.25 !important;
                margin-top: 8px !important;
                margin-bottom: 6px !important;
            }
            .hero-subtitle {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }
            .hero-desc {
                font-size: 12.5px !important;
                line-height: 1.4 !important;
                margin-bottom: 16px !important;
            }
            .hero-btn {
                padding: 9px 18px !important;
                font-size: 13px !important;
            }
            .mobile-header-right {
                gap: 4px !important;
            }
            .mobile-header-icon {
                width: 31px !important;
                height: 31px !important;
                font-size: 14px !important;
            }
            .nav-logo img {
                max-height: 26px !important;
                max-width: 110px !important;
            }
            .mobile-search-form {
                height: 38px !important;
                padding: 2px 3px 2px 10px !important;
            }
            .mobile-search-prefix {
                font-size: 14px !important;
                margin-right: 5px !important;
            }
            .mobile-search-input {
                font-size: 12px !important;
            }
            .mobile-search-btn {
                height: 30px !important;
                min-height: 30px !important;
                padding: 0 10px !important;
                font-size: 11.5px !important;
            }
        }

        /* Promo 4-Cards Styling */
        .promo-card-styled {
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05) !important;
        }
        .promo-card-styled .banner-detail {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(8px);
            padding: 18px 22px !important;
            border-radius: 10px;
            margin-left: 20px;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .promo-card-styled .banner-detail h5 {
            color: #64748b !important;
            font-size: 13px !important;
        }
        .promo-card-styled .banner-detail h4 {
            color: #0f172a !important;
            font-size: 17px !important;
            font-weight: 700 !important;
        }

        /* Category Header Slider Arrow Controls (Matches Template) */
        .slider-nav-arrows {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .slider-nav-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #ffffff;
            color: #4b5563;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            padding: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        .slider-nav-btn:hover {
            background: #22c55e;
            border-color: #22c55e;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.25);
        }
        .slider-nav-btn:active {
            transform: scale(0.95);
        }

        /* Equal Card Heights & Alignment for Category Boxes */
        .category-slider-1 .slick-track {
            display: flex !important;
        }
        .category-slider-1 .slick-slide {
            height: auto !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 0 10px !important;
            box-sizing: border-box !important;
        }
        .category-slider-1 .slick-list {
            margin: 0 -10px !important;
        }
        .category-slider-1 .slick-slide > div {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
        }
        .category-box-list {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            min-height: 290px !important;
            background: #f8fafc !important;
            border: 1px solid #f0f0f0 !important;
            border-radius: 12px !important;
            padding: 20px !important;
            transition: all 0.3s ease !important;
        }
        .category-box-list:hover {
            border-color: #22c55e !important;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.12) !important;
            transform: translateY(-2px);
        }
        .category-box-list .category-name {
            display: block !important;
            min-height: 56px !important;
        }
        .category-box-list .category-name h4 {
            font-size: 15px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            height: 40px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            margin-bottom: 4px !important;
            color: #222222 !important;
        }
        .category-box-list .category-name h6 {
            font-size: 13px !important;
            color: #7e7e7e !important;
            margin-bottom: 0 !important;
        }
        .category-box-list .category-box-view {
            margin-top: auto !important;
            text-align: center !important;
            width: 100% !important;
        }
        .category-box-list .category-box-view a img {
            width: 100% !important;
            height: 150px !important;
            object-fit: contain !important;
            background: #ffffff !important;
            border-radius: 8px !important;
            padding: 10px !important;
            transition: transform 0.3s ease !important;
        }
        .category-box-list:hover .category-box-view a img {
            transform: scale(1.05) !important;
        }
        .category-box-list .category-box-view .shop-button {
            display: none !important;
        }

        /* Equal Card Heights & Sizing for Product Cards */
        .slider-category-products .slick-track {
            display: flex !important;
        }
        .slider-category-products .slick-slide {
            height: auto !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 0 10px;
            box-sizing: border-box;
        }
        .slider-category-products .slick-slide > div {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
        }
        .slider-category-products .slick-list {
            margin: 0 -10px;
        }
        .product-box-3 {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            background: #ffffff !important;
            border: 1px solid #f0f0f0 !important;
            border-radius: 12px !important;
            padding: 14px !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s ease !important;
        }
        .product-box-3:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
            border-color: #22c55e !important;
        }
        .product-box-3 .product-header {
            width: 100% !important;
        }
        .product-box-3 .product-header .product-image {
            width: 100% !important;
            height: 230px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            position: relative !important;
            background: #f8fafc !important;
            border-radius: 10px !important;
            padding: 12px !important;
            margin-bottom: 12px !important;
        }
        .product-box-3 .product-header .product-image a {
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .product-box-3 .product-header .product-image img {
            max-height: 205px !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            transition: transform 0.35s ease !important;
        }
        .product-box-3:hover .product-header .product-image img {
            transform: scale(1.06) !important;
        }
        .product-box-3 .product-footer {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            flex-grow: 1 !important;
        }
        .product-box-3 .product-footer .product-detail {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
        }
        .product-box-3 .product-footer .product-detail .span-name {
            font-size: 13px !important;
            color: #888888 !important;
            margin-bottom: 2px !important;
        }
        .product-box-3 .product-footer .product-detail .name {
            font-size: 15px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            height: 42px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            margin-bottom: 6px !important;
        }
        .product-box-3 .product-footer .product-detail .product-content {
            font-size: 13px !important;
            color: #888888 !important;
            margin-bottom: 6px !important;
        }
        .product-box-3 .product-footer .product-detail .price {
            font-size: 16px !important;
            font-weight: 700 !important;
            margin-bottom: 12px !important;
        }
        .product-box-3 .product-footer .product-detail .add-to-cart-box {
            margin-top: auto !important;
        }

        /* Hot Deal / Special Offer Custom Styles */
        .product-bg-image {
            background: #ffffff !important;
            border: 1px solid #f0f0f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04) !important;
            height: 100% !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .product-bg-image .product-title.product-warning {
            background: #22c55e !important;
            padding: 14px 20px !important;
        }
        .product-bg-image .product-title.product-warning h2 {
            color: #ffffff !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            margin: 0 !important;
        }
        .product-bg-image .product-box-4 {
            border: none !important;
            background: transparent !important;
            padding: 20px !important;
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            position: relative !important;
        }
        .product-bg-image .product-box-4 .product-image {
            width: 100% !important;
            height: 310px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #f8fafc !important;
            border-radius: 12px !important;
            padding: 20px !important;
            margin-bottom: 18px !important;
            overflow: hidden !important;
        }
        .product-bg-image .product-box-4 .product-image a {
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .product-bg-image .product-box-4 .product-image img {
            max-height: 270px !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            transition: transform 0.35s ease !important;
        }
        .product-bg-image .product-box-4:hover .product-image img {
            transform: scale(1.05) !important;
        }
        .product-bg-image .deal-box {
            position: absolute !important;
            top: 10px !important;
            left: 10px !important;
            z-index: 4 !important;
            margin: 0 !important;
            pointer-events: none !important;
        }
        .product-bg-image .deal-box .circle-box {
            position: relative !important;
            width: 52px !important;
            height: 52px !important;
            margin: 0 !important;
        }
        .product-bg-image .deal-box .shape-circle {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            width: 52px !important;
            height: 52px !important;
            margin: 0 !important;
        }
        .product-bg-image .deal-box .shape-circle img {
            width: 52px !important;
            height: 52px !important;
            max-width: 52px !important;
            display: block !important;
            animation: rounded 15s linear infinite;
        }
        .product-bg-image .deal-box .shape-text {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            text-align: center !important;
            color: #ffffff !important;
            width: 100% !important;
        }
        .product-bg-image .deal-box .shape-text h6 {
            font-size: 10px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin: 0 !important;
            line-height: 1.1 !important;
            text-transform: uppercase !important;
        }

        /* 5-Box Service Benefits Section */
        .service-box-custom {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 18px 20px;
            height: 100%;
            min-height: 85px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .service-box-custom:hover {
            background: #22c55e !important;
            box-shadow: 0 8px 22px rgba(34, 197, 94, 0.28) !important;
            transform: translateY(-3px);
            border-color: #22c55e !important;
        }
        .service-box-custom:hover h5,
        .service-box-custom:hover p {
            color: #ffffff !important;
        }
        .service-box-custom:hover .service-icon svg {
            stroke: #ffffff !important;
            color: #ffffff !important;
        }
        .service-box-custom:hover .service-badge-24 {
            color: #ffffff !important;
            border-color: #ffffff !important;
        }
        .service-box-custom .service-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .service-badge-24 {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px dashed #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            color: #22c55e;
            transition: all 0.3s ease;
        }
        .service-box-custom .service-icon svg {
            transition: all 0.3s ease;
        }
        .service-box-custom .service-text h5 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #222222;
            transition: color 0.3s ease;
        }
        .service-box-custom .service-text p {
            font-size: 13px;
            color: #777777;
            margin-bottom: 0;
            transition: color 0.3s ease;
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

    <!-- Mobile Fix Menu Start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li class="active">
                <a href="{{ route('home') }}">
                    <i class="iconly-Home icli"></i>
                    <span>Home</span>
                </a>
            </li>

            <li class="mobile-category">
                <a href="{{ route('shop.category') }}">
                    <i class="iconly-Category icli js-link"></i>
                    <span>Category</span>
                </a>
            </li>

            <li>
                <a href="{{ route('shop.category') }}" class="search-box">
                    <i class="iconly-Search icli"></i>
                    <span>Search</span>
                </a>
            </li>

            <li>
                <a href="{{ route('wishlist.index') }}" class="notifi-wishlist">
                    <i class="iconly-Heart icli"></i>
                    <span>Wishlist</span>
                </a>
            </li>

            <li>
                <a href="{{ route('cart.index') }}">
                    <i class="iconly-Bag-2 icli fly-cate"></i>
                    <span>Cart</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Mobile Fix Menu End -->

    <!-- Hero Section Start -->
    <section class="home-section-2 home-section-bg pt-0 overflow-hidden">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="slider-animate">
                        @if (isset($heroSlides) && $heroSlides->isNotEmpty())
                            @foreach ($heroSlides as $slide)
                                <div>
                                    <div class="home-contain rounded-0 p-0 position-relative">
                                        <img src="{{ asset($slide->image ?: 'assets/images/banner/kenkie-hero-banner.jpg') }}"
                                            class="img-fluid bg-img blur-up lazyload" alt="{{ $slide->title }}">
                                        <div class="home-detail home-big-space p-center-left position-relative" style="z-index: 2;">
                                            <div class="container-fluid-lg">
                                                <div class="hero-content-wrap">
                                                    @if (!empty($slide->badge))
                                                        <span class="badge hero-badge mb-2 px-3 py-2 text-uppercase">{{ $slide->badge }}</span>
                                                    @endif
                                                    <h1 class="heding-2 hero-title">{{ $slide->title }}</h1>
                                                    @if (!empty($slide->subtitle))
                                                        <h2 class="content-2 hero-subtitle">{{ $slide->subtitle }}</h2>
                                                    @endif
                                                    @if (!empty($slide->description))
                                                        <h5 class="text-content hero-desc">{{ $slide->description }}</h5>
                                                    @endif
                                                    <a href="{{ $slide->button_url ? url($slide->button_url) : route('shop.category') }}"
                                                        class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 mt-2 mend-auto d-inline-flex align-items-center gap-2 hero-btn">
                                                        {{ $slide->button_text ?: 'Shop Collection' }} <i class="fa-solid fa-arrow-right icon"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <div class="home-contain rounded-0 p-0 position-relative">
                                    <img src="{{ asset($homeSettings->hero_image ?? 'assets/images/banner/kenkie-hero-banner.jpg') }}"
                                        class="img-fluid bg-img blur-up lazyload" alt="Kenkie Collection Banner">
                                    <div class="home-detail home-big-space p-center-left position-relative" style="z-index: 2;">
                                        <div class="container-fluid-lg">
                                            <div class="hero-content-wrap">
                                                @if (!empty($homeSettings->hero_badge))
                                                    <span class="badge hero-badge mb-2 px-3 py-2 text-uppercase">{{ $homeSettings->hero_badge }}</span>
                                                @endif
                                                <h1 class="heding-2 hero-title">{{ $homeSettings->hero_title ?? 'Premium Quality Home & Garden Collection' }}</h1>
                                                @if (!empty($homeSettings->hero_subtitle))
                                                    <h2 class="content-2 hero-subtitle">{{ $homeSettings->hero_subtitle }}</h2>
                                                @endif
                                                @if (!empty($homeSettings->hero_description))
                                                    <h5 class="text-content hero-desc">{{ $homeSettings->hero_description }}</h5>
                                                @endif
                                                <a href="{{ $homeSettings->hero_button_url ? url($homeSettings->hero_button_url) : route('shop.category') }}"
                                                    class="btn theme-bg-color btn-md text-white fw-bold mt-md-4 mt-2 mend-auto d-inline-flex align-items-center gap-2 hero-btn">
                                                    {{ $homeSettings->hero_button_text ?? 'Shop Collection' }} <i class="fa-solid fa-arrow-right icon"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner 4-Cards Section Start -->
    <section class="banner-section banner-small ratio_65">
        <div class="container-fluid-lg">
            <div class="slider-4-banner no-arrow slick-height">
                @forelse ($homeBanners as $banner)
                    <div>
                        <div class="banner-contain-3 hover-effect promo-card-styled">
                            <a href="{{ $banner->button_url ? url($banner->button_url) : route('shop.category') }}">
                                <img src="{{ asset($banner->image ?: 'assets/images/banner/kenkie-promo-home.jpg') }}" class="bg-img blur-up lazyload" alt="{{ $banner->title }}">
                            </a>
                            <div class="banner-detail p-center-left w-75 banner-p-sm mend-auto">
                                <div>
                                    @if ($banner->subtitle)
                                        <h5 class="fw-light mb-2">{{ $banner->subtitle }}</h5>
                                    @endif
                                    <h4 class="fw-bold mb-0">{{ $banner->title }}</h4>
                                    <a href="{{ $banner->button_url ? url($banner->button_url) : route('shop.category') }}"
                                        class="btn shop-now-button mt-3 ps-0 mend-auto theme-color fw-bold">
                                        {{ $banner->button_text ?: 'Shop Now' }} <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        <div class="banner-contain-3 hover-effect promo-card-styled">
                            <a href="{{ route('shop.category', ['category' => 'home-furniture-diy']) }}">
                                <img src="{{ asset('assets/images/banner/kenkie-promo-home.jpg') }}" class="bg-img blur-up lazyload" alt="Home & Furniture">
                            </a>
                            <div class="banner-detail p-center-left w-75 banner-p-sm mend-auto">
                                <div>
                                    <h5 class="fw-light mb-2">New Arrivals</h5>
                                    <h4 class="fw-bold mb-0">Home & Furniture</h4>
                                    <a href="{{ route('shop.category', ['category' => 'home-furniture-diy']) }}" class="btn shop-now-button mt-3 ps-0 mend-auto theme-color fw-bold">Shop Now <i class="fa-solid fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Banner 4-Cards Section End -->

    <!-- Categories Section Start -->
    <section class="category-section-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Shop By Categories</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="category-slider-1 arrow-slider wow fadeInUp">
                        @forelse (($mainCategories ?? $categories->whereNull('parent_id')) as $category)
                            <div>
                                <div class="category-box-list">
                                    <a href="{{ route('shop.category', ['category' => $category->slug]) }}" class="category-name">
                                        <h4>{{ $category->name }}</h4>
                                        <h6>{{ $category->total_products_count ?? $category->products_count }} items</h6>
                                    </a>
                                    <div class="category-box-view">
                                        <a href="{{ route('shop.category', ['category' => $category->slug]) }}">
                                            <img src="{{ asset($category->image ?: 'assets/images/product/category/1.jpg') }}" class="img-fluid blur-up lazyload" alt="{{ $category->name }}">
                                        </a>
                                        <a href="{{ route('shop.category', ['category' => $category->slug]) }}" class="btn shop-button">
                                            <span>Shop Now</span><i class="fas fa-angle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-content">No categories available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Top Selling Products & Hot Deals Section Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title d-flex justify-content-between align-items-center">
                <h2><i class="fa-solid fa-fire text-danger me-2"></i>Top Selling Products & Hot Deals</h2>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.category', ['sort' => 'popular']) }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <div class="slider-nav-arrows">
                        <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-top-selling" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-top-selling" aria-label="Next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-category-products product-box-slider-custom" id="slider-top-selling">
                        @forelse ($topSellingProducts as $product)
                            <div>
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            @foreach ($featuredProducts->take(6) as $product)
                                <div>
                                    @include('website.includes.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Top Selling Products & Hot Deals Section End -->

    <!-- New Arrivals Section Start -->
    <section class="product-section-3 pt-0">
        <div class="container-fluid-lg">
            <div class="title d-flex justify-content-between align-items-center">
                <h2><i class="fa-solid fa-sparkles text-warning me-2"></i>New Arrivals</h2>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.category') }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <div class="slider-nav-arrows">
                        <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-new-arrivals" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-new-arrivals" aria-label="Next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-category-products product-box-slider-custom" id="slider-new-arrivals">
                        @forelse ($newArrivals as $product)
                            <div>
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            @foreach ($featuredProducts->take(6) as $product)
                                <div>
                                    @include('website.includes.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- New Arrivals Section End -->

    <!-- Bank & Wallet Offers Section Start -->
    <section class="bank-section overflow-hidden">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Bank & Wallet Offers</h2>
            </div>
            <div class="slider-bank-3 arrow-slider slick-height">
                @forelse ($bankOffers as $index => $offer)
                    @php
                        $footerClass = match ($offer->color_theme) {
                            'theme-2' => 'bank-footer-2',
                            'theme-3' => 'bank-footer-3',
                            'theme-4' => 'bank-footer-4',
                            default => 'bank-footer-1',
                        };
                        $titleClass = match ($offer->color_theme) {
                            'theme-2' => 'bank-offer-2',
                            'theme-3' => 'bank-offer-3',
                            'theme-4' => 'text-success',
                            default => '',
                        };
                        $defaultBankImg = match ($offer->color_theme) {
                            'theme-2' => 'assets/images/grocery/bank/name/2.png',
                            'theme-3' => 'assets/images/grocery/bank/name/3.png',
                            default => 'assets/images/grocery/bank/name/1.png',
                        };
                    @endphp
                    <div>
                        <div class="bank-offer">
                            <div class="bank-header">
                                <div class="bank-left w-100">
                                    <div class="bank-image">
                                        <img src="{{ asset($offer->bank_image ?: $defaultBankImg) }}" class="img-fluid" alt="{{ $offer->title }}">
                                    </div>
                                    <div class="bank-name">
                                        <h2 class="{{ $titleClass }}">{{ $offer->title }}</h2>
                                        @if ($offer->subtitle)
                                            <h5 class="discount text-content">{{ $offer->subtitle }}</h5>
                                        @endif
                                        <h5 class="valid text-content">{{ $offer->validity ?: 'Valid for 30 days' }}</h5>
                                    </div>
                                </div>
                                <div class="bank-right w-100">
                                    <img src="{{ asset('assets/images/grocery/bank/price/' . (($index % 3) + 1) . '.svg') }}" class="img-fluid" alt="">
                                </div>
                            </div>
                            <div class="bank-footer {{ $footerClass }}">
                                <h4>Code : <input id="clipboardexample_{{ $offer->id }}" value="{{ $offer->code }}" readonly /></h4>
                                <button type="button" class="bank-coupon btn copy-coupon-btn"
                                    onclick="navigator.clipboard ? navigator.clipboard.writeText('{{ $offer->code }}').then(() => alert('Copied coupon code: {{ $offer->code }}')) : null">
                                    Copy Code
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        <div class="bank-offer">
                            <div class="bank-header">
                                <div class="bank-left w-100">
                                    <div class="bank-image">
                                        <img src="{{ asset('assets/images/grocery/bank/name/1.png') }}" class="img-fluid" alt="">
                                    </div>
                                    <div class="bank-name">
                                        <h2>GET 10% OFF</h2>
                                        <h5 class="discount text-content">When you spend $20</h5>
                                        <h5 class="valid text-content">Valid for 30 days</h5>
                                    </div>
                                </div>
                                <div class="bank-right w-100">
                                    <img src="{{ asset('assets/images/grocery/bank/price/1.svg') }}" class="img-fluid" alt="">
                                </div>
                            </div>
                            <div class="bank-footer bank-footer-1">
                                <h4>Code : <input value="KENKIE10" readonly /></h4>
                                <button type="button" class="bank-coupon btn" onclick="navigator.clipboard.writeText('KENKIE10')">Copy Code</button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Bank & Wallet Offers Section End -->

    <!-- Deal & Featured Section Start -->
    <section class="product-section product-section-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Top Deals & Featured Items</h2>
            </div>
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-4 col-lg-5 order-lg-2">
                    <div class="product-bg-image wow fadeInUp">
                        <div class="product-title product-warning">
                            <h2>Special Offer</h2>
                        </div>

                        <div class="product-box-4 product-box-3 rounded-0">
                            @if ($featuredDealProduct)
                                <div class="p-3 text-center">
                                    <div class="product-image position-relative mb-3">
                                        <div class="deal-box">
                                            <div class="circle-box">
                                                <div class="shape-circle">
                                                    <img src="{{ asset('assets/images/grocery/circle.svg') }}" class="blur-up lazyload" alt="">
                                                    <div class="shape-text">
                                                        <h6>Hot <br> Deal</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('products.show', $featuredDealProduct->slug) }}">
                                            <img src="{{ asset($featuredDealProduct->image ?: 'assets/images/furniture/1.png') }}"
                                                 class="img-fluid blur-up lazyload rounded" alt="{{ $featuredDealProduct->name }}">
                                        </a>
                                    </div>
                                    <div class="product-detail">
                                        <h6 class="text-muted mb-1">{{ $featuredDealProduct->category?->name }}</h6>
                                        <a href="{{ route('products.show', $featuredDealProduct->slug) }}">
                                            <h4 class="name fw-bold mb-2">{{ $featuredDealProduct->name }}</h4>
                                        </a>
                                        <h3 class="price theme-color mb-3">${{ number_format((float) $featuredDealProduct->price, 2) }}</h3>
                                        <form method="POST" action="{{ route('cart.store', $featuredDealProduct->slug) }}">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn theme-bg-color text-white w-100 fw-bold">
                                                <i class="fa-solid fa-cart-shopping me-2"></i> Add To Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 col-lg-7 order-lg-1">
                    <div class="row g-3 row-cols-md-3 row-cols-2">
                        @foreach ($topDealProducts->take(6) as $product)
                            <div class="col">
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Deal & Featured Section End -->

    <!-- Section 2: Garden & Patio Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title d-flex justify-content-between align-items-center">
                <h2>Garden & Patio</h2>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.category', ['category' => 'garden-patio']) }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <div class="slider-nav-arrows">
                        <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-garden-patio" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-garden-patio" aria-label="Next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-category-products product-box-slider-custom" id="slider-garden-patio">
                        @forelse ($gardenPatioProducts as $product)
                            <div>
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            <p class="text-content">No products available in this category.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section 2: Garden & Patio End -->

    <!-- Section 3: Health & Beauty Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title d-flex justify-content-between align-items-center">
                <h2>Health & Beauty</h2>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.category', ['category' => 'health-beauty']) }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <div class="slider-nav-arrows">
                        <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-health-beauty" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-health-beauty" aria-label="Next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-category-products product-box-slider-custom" id="slider-health-beauty">
                        @forelse ($healthBeautyProducts as $product)
                            <div>
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            <p class="text-content">No products available in this category.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section 3: Health & Beauty End -->

    <!-- Section 4: Sound & Vision Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title d-flex justify-content-between align-items-center">
                <h2>Sound & Vision</h2>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.category', ['category' => 'sound-vision']) }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                    <div class="slider-nav-arrows">
                        <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-sound-vision" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-sound-vision" aria-label="Next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-category-products product-box-slider-custom" id="slider-sound-vision">
                        @forelse ($soundVisionProducts as $product)
                            <div>
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            <p class="text-content">No products available in this category.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section 4: Sound & Vision End -->

    <!-- Section 5: Computers & Tablets Start -->
    @if ($techProducts->count() > 0)
        <section class="product-section-3">
            <div class="container-fluid-lg">
                <div class="title d-flex justify-content-between align-items-center">
                    <h2>Computers, Tablets & Gadgets</h2>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('shop.category', ['category' => 'computers-tablets-networking']) }}" class="theme-color fw-bold text-decoration-none d-none d-sm-inline">
                            View All <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                        <div class="slider-nav-arrows">
                            <button type="button" class="slider-nav-btn slider-prev-btn" data-target="#slider-computers-tablets" aria-label="Previous">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button type="button" class="slider-nav-btn slider-next-btn" data-target="#slider-computers-tablets" aria-label="Next">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="slider-category-products product-box-slider-custom" id="slider-computers-tablets">
                            @foreach ($techProducts as $product)
                                <div>
                                    @include('website.includes.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Section 5: Computers & Tablets End -->

    <!-- Service Benefits Section Start -->
    <section class="service-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-3 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">
                <div class="col">
                    <div class="service-box-custom">
                        <div class="service-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-color"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        <div class="service-text">
                            <h5>Free Shipping</h5>
                            <p>Free Shipping world wide</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="service-box-custom">
                        <div class="service-icon">
                            <span class="service-badge-24">24</span>
                        </div>
                        <div class="service-text">
                            <h5>24 x 7 Service</h5>
                            <p>Online Service For 24 x 7</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="service-box-custom">
                        <div class="service-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-color"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div class="service-text">
                            <h5>Online Pay</h5>
                            <p>Online Payment Avaible</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="service-box-custom">
                        <div class="service-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-color"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                        </div>
                        <div class="service-text">
                            <h5>Festival Offer</h5>
                            <p>Super Sale Upto 50% off</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="service-box-custom">
                        <div class="service-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-color"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        </div>
                        <div class="service-text">
                            <h5>100% Original</h5>
                            <p>100% Money Back</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Service Benefits Section End -->

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->

    <!-- Cookie Bar Box Start -->
    <div class="cookie-bar-box">
        <div class="cookie-box">
            <div class="cookie-image">
                <img src="{{ asset('assets/images/cookie-bar.png') }}" class="blur-up lazyload" alt="">
                <h2>Cookies!</h2>
            </div>
            <div class="cookie-contain">
                <h5 class="text-content">We use cookies to make your experience better</h5>
            </div>
        </div>
        <div class="button-group">
            <button class="btn privacy-button">Privacy Policy</button>
            <button class="btn ok-button">OK</button>
        </div>
    </div>
    <!-- Cookie Bar Box End -->

    <!-- Tap to top start -->
    <div class="theme-option">
        <div class="back-to-top">
            <a id="back-to-top" href="#">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <!-- Tap to top end -->
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('.slider-category-products').each(function () {
                if (!$(this).hasClass('slick-initialized')) {
                    $(this).slick({
                        arrows: false,
                        dots: false,
                        infinite: true,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        swipe: true,
                        touchMove: true,
                        responsive: [
                            {
                                breakpoint: 1660,
                                settings: {
                                    slidesToShow: 5,
                                }
                            },
                            {
                                breakpoint: 1399,
                                settings: {
                                    slidesToShow: 4,
                                }
                            },
                            {
                                breakpoint: 1124,
                                settings: {
                                    slidesToShow: 3,
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    slidesToShow: 1,
                                }
                            }
                        ]
                    });
                }
            });

            $(document).on('click', '.slider-prev-btn', function (e) {
                e.preventDefault();
                var target = $(this).data('target');
                if (target && $(target).hasClass('slick-initialized')) {
                    $(target).slick('slickPrev');
                }
            });

            $(document).on('click', '.slider-next-btn', function (e) {
                e.preventDefault();
                var target = $(this).data('target');
                if (target && $(target).hasClass('slick-initialized')) {
                    $(target).slick('slickNext');
                }
            });
        });
    </script>
@endsection
