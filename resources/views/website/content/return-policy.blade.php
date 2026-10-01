@extends('website.layouts.storefront')

@section('title', 'Returns and Refund Policy - Kenkie')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        .policy-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            line-height: 1.75;
        }
        .policy-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 28px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .policy-card p {
            color: #475569;
            margin-bottom: 16px;
        }
        .policy-card ul {
            color: #475569;
            margin-bottom: 20px;
            padding-left: 24px;
        }
        .policy-card ul li {
            margin-bottom: 8px;
        }
        .policy-header-badge {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .step-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f0fdf4;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .condition-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .note-card {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            color: #92400e;
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
                        <h2>Returns & Refund Policy</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Return Policy</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="section-b-space">
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="policy-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-3 border-bottom">
                            <div>
                                <h2 class="fw-bold mb-1 text-dark">Returns and Refund Policy</h2>
                                <span class="text-muted small">Guidelines on returns, replacements, and refund processing for purchases at KENKIE store.</span>
                            </div>
                            <span class="policy-header-badge">
                                <i class="fa-solid fa-rotate-left me-1"></i> 14 Working Days Window
                            </span>
                        </div>

                        <div class="p-4 rounded-4 bg-light mb-4 border">
                            <p class="fs-5 text-dark fw-medium mb-0">
                                You can return or replace most items purchased at <strong>KENKIE store</strong> for a full refund within <strong>14 working days</strong> of purchase.
                            </p>
                        </div>

                        <h3>Return & Replacement Conditions</h3>
                        <p class="text-muted">Please ensure the following conditions are met before requesting a return or replacement:</p>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="condition-item">
                                    <div class="step-icon-box">
                                        <i class="fa-solid fa-shield-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Verify that the product is not damaged</h6>
                                        <p class="text-content mb-0 small">The item must be in good physical condition without accidental or intentional damage.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="condition-item">
                                    <div class="step-icon-box">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">The product must not be used</h6>
                                        <p class="text-content mb-0 small">Items must be returned in their original packaging with all included components and tags intact.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="condition-item">
                                    <div class="step-icon-box">
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Technical Malfunctions & Manufacturer Warranty</h6>
                                        <p class="text-content mb-0 small">Technical malfunctioning is resolved via claiming warranty provided by the manufacturer.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="note-card mb-4">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fa-solid fa-circle-exclamation fs-4 mt-1 text-warning"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Important Note:</h6>
                                    <p class="mb-0 text-dark">
                                        <strong>Attach proof of product condition by uploading any picture or a video</strong> when contacting our support team for a faster resolution.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3>How to Request a Return or Refund</h3>
                        <p>
                            To initiate a return or exchange, please email our support team at <a href="mailto:info@kenkie.com" class="theme-color text-decoration-none fw-semibold">info@kenkie.com</a> or message us on WhatsApp at <a href="https://wa.me/447898346397" target="_blank" rel="noopener noreferrer" class="theme-color text-decoration-none fw-semibold">07898346397</a> with your order number, reason for return, and attached photo/video proof.
                        </p>

                        <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                            <a href="{{ route('shop.category') }}" class="btn btn-animation btn-sm">
                                <i class="fa-solid fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                            <a href="{{ route('contact.us') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-headset me-2"></i> Contact Customer Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->
@endsection
