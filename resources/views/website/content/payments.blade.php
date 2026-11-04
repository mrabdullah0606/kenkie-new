@extends('website.layouts.storefront')

@section('title', 'Payment Methods & Security - Kenkie')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        .payment-policy-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            line-height: 1.75;
        }
        .payment-policy-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 32px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .payment-policy-card p {
            color: #475569;
            margin-bottom: 16px;
        }
        .payment-policy-card ul {
            color: #475569;
            margin-bottom: 20px;
            padding-left: 24px;
        }
        .payment-policy-card ul li {
            margin-bottom: 8px;
        }
        .payment-badge {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .method-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .method-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }
        .method-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 22px;
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
                        <h2>Payment Methods &amp; Security</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Payments</li>
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
                    <div class="payment-policy-card">
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-3 border-bottom">
                            <div>
                                <h2 class="fw-bold mb-1 text-dark">Payment Methods &amp; Security</h2>
                                <span class="text-muted small">Learn about accepted payment options, security standards, and billing practices at KENKIE.</span>
                            </div>
                            <span class="payment-badge">
                                <i class="fa-solid fa-shield-halved me-1"></i> 256-Bit SSL Encrypted
                            </span>
                        </div>

                        <!-- Payment Methods Grid -->
                        <div class="row g-4 my-2">
                            <div class="col-md-6">
                                <div class="method-box">
                                    <div class="method-icon bg-success-subtle text-success">
                                        <i class="fa-regular fa-credit-card"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Credit &amp; Debit Cards (Stripe)</h5>
                                    <p class="small text-muted mb-3">
                                        Fast, seamless card payments powered by Stripe's bank-level infrastructure.
                                    </p>
                                    <ul class="small ps-3 mb-0">
                                        <li>Visa, MasterCard, American Express</li>
                                        <li>Apple Pay &amp; Google Pay supported</li>
                                        <li>3D Secure 2.0 biometric verification</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="method-box">
                                    <div class="method-icon bg-primary-subtle text-primary">
                                        <i class="fa-brands fa-paypal"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">PayPal &amp; Pay in 3</h5>
                                    <p class="small text-muted mb-3">
                                        Checkout securely without entering card details on our site.
                                    </p>
                                    <ul class="small ps-3 mb-0">
                                        <li>Pay directly via PayPal balance or linked bank</li>
                                        <li>PayPal Pay in 3 interest-free installments</li>
                                        <li>Full PayPal Buyer Protection coverage</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h3>Accepted Currencies</h3>
                        <p>
                            All transactions on KENKIE are processed in <strong>British Pounds (£ GBP)</strong>. All product prices shown on the store include UK VAT where applicable. There are no surprise foreign transaction charges or hidden conversion fees for UK customers.
                        </p>

                        <h3>Payment Security &amp; Data Protection</h3>
                        <p>
                            Your financial safety is our highest priority. KENKIE complies strictly with the Payment Card Industry Data Security Standard (<strong>PCI-DSS Level 1</strong>):
                        </p>
                        <ul>
                            <li><strong>Zero Card Data Storage:</strong> We never view, collect, or store your complete card number or CVV code on our servers. All sensitive card data is directly handled by certified PCI-compliant gateways (Stripe &amp; PayPal).</li>
                            <li><strong>End-to-End Encryption:</strong> All communications between your browser and our website are protected using TLS 1.3 / 256-bit encryption.</li>
                            <li><strong>Fraud &amp; Risk Monitoring:</strong> Every order is analyzed by real-time risk evaluation algorithms to protect consumers from unauthorized card usage.</li>
                        </ul>

                        <h3>Order Invoices &amp; Receipts</h3>
                        <p>
                            As soon as your payment is authorized, an itemized order confirmation email with your official receipt is automatically dispatched from <strong>info@kenkie.com</strong> to your provided email address. You can also view your live order status and print your invoice anytime through the <a href="{{ route('track-order.show') }}" class="theme-color fw-semibold">Track My Order</a> portal.
                        </p>

                        <h3>Payment Inquiries &amp; Support</h3>
                        <p>
                            If you encounter any issues during checkout or have questions regarding a charge on your statement, our Walsall-based customer service team is ready to assist:
                        </p>
                        @php
                            $mktSettings = $mktSettings ?? \App\Models\MarketingSetting::getSettings();
                            $storePhone = $mktSettings->whatsapp_number ?: '07898346397';
                            $waUrl = 'https://wa.me/' . ($mktSettings->clean_whatsapp_number ?: '447898346397');
                        @endphp
                        <div class="p-3 bg-light rounded-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <span class="d-block fw-bold text-dark">Need assistance with your payment?</span>
                                <small class="text-muted">Email: <a href="mailto:info@kenkie.com" class="theme-color">info@kenkie.com</a> &bull; WhatsApp: <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="theme-color fw-semibold">{{ $storePhone }}</a></small>
                            </div>
                            <a href="{{ route('contact.us') }}" class="btn btn-sm btn-animation">Contact Support</a>
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

