@extends('website.layouts.storefront')

@section('title', 'Checkout')
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        <h2>Checkout</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout section Start -->
    <section class="checkout-section-2 section-b-space">
        <div class="container-fluid-lg">
            @if ($errors->has('cart'))
                <div class="alert alert-danger mb-4">{{ $errors->first('cart') }}</div>
            @endif

            @if ($cartItems->isEmpty())
                <div class="text-center py-5">
                    <h3 class="mb-3">Your cart is empty</h3>
                    <p class="text-content mb-4">Please add items to your cart before proceeding to checkout.</p>
                    <a href="{{ route('shop.category') }}" class="btn theme-bg-color text-white btn-md">Browse Products</a>
                </div>
            @else
                <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                    @csrf
                    <div class="row g-sm-4 g-3">
                        <div class="col-lg-8">
                            <div class="left-sidebar-checkout">
                                <div class="checkout-detail-box">
                                    <ul>
                                        <li>
                                            <div class="checkout-icon">
                                                <i class="fa-solid fa-location-dot fs-4 text-theme"></i>
                                            </div>
                                            <div class="checkout-box">
                                                <div class="checkout-title">
                                                    <h4>Delivery & Customer Information</h4>
                                                </div>

                                                <div class="checkout-detail">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold" for="customer_name">Full Name <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" placeholder="e.g. John Doe" required>
                                                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold" for="email">Email Address <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" placeholder="e.g. john@example.com" required>
                                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold" for="phone">Phone Number <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. +1 555 123 4567" required>
                                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold" for="country">Country <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', 'United States') }}" required>
                                                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-12">
                                                            <label class="form-label fw-bold" for="address_line">Street Address <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('address_line') is-invalid @enderror" id="address_line" name="address_line" value="{{ old('address_line') }}" placeholder="House #, Street name, Apt / Suite" required>
                                                            @error('address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold" for="city">City <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="City" required>
                                                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold" for="region">State / Province</label>
                                                            <input class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region') }}" placeholder="State / Province">
                                                            @error('region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold" for="postal_code">Postal Code <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="Postal / ZIP" required>
                                                            @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="checkout-icon">
                                                <i class="fa-solid fa-credit-card fs-4 text-theme"></i>
                                            </div>
                                            <div class="checkout-box">
                                                <div class="checkout-title">
                                                    <h4>Payment Method</h4>
                                                </div>

                                                <div class="checkout-detail">
                                                    <!-- Stripe Payment Option -->
                                                    <div class="p-3 mb-3 border rounded-3 payment-method-option" id="stripe-option-box" style="background: #f8fafc; cursor: pointer;">
                                                        <div class="form-check custom-form-check d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                                                                <label class="form-check-label fw-bold fs-6 mb-0 text-dark" for="stripe" style="cursor: pointer;">
                                                                    Credit / Debit Card (Stripe)
                                                                </label>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 text-secondary fs-5">
                                                                <i class="fa-brands fa-cc-visa text-primary"></i>
                                                                <i class="fa-brands fa-cc-mastercard text-warning"></i>
                                                                <i class="fa-brands fa-cc-amex text-info"></i>
                                                                <i class="fa-brands fa-apple text-dark"></i>
                                                                <i class="fa-brands fa-google-pay text-primary"></i>
                                                            </div>
                                                        </div>
                                                        <p class="text-muted small mt-2 mb-0 ps-4">
                                                            <i class="fa-solid fa-lock text-success me-1"></i> Fast, secure 256-bit encrypted checkout via Stripe. Supports Visa, MasterCard, Amex, Apple Pay &amp; Google Pay.
                                                        </p>
                                                    </div>

                                                    <!-- Cash on Delivery Option -->
                                                    <div class="p-3 border rounded-3 payment-method-option" id="cod-option-box" style="background: #ffffff; cursor: pointer;">
                                                        <div class="form-check custom-form-check">
                                                            <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                                                            <label class="form-check-label fw-bold fs-6 mb-0 text-dark" for="cash_on_delivery" style="cursor: pointer;">
                                                                Cash On Delivery (COD)
                                                            </label>
                                                        </div>
                                                        <p class="text-muted small mt-2 mb-0 ps-4">
                                                            Pay with cash upon delivery of your items to your doorstep.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="right-side-summery-box">
                                <div class="summery-box-2">
                                    <div class="summery-header">
                                        <h3>Order Summary</h3>
                                    </div>

                                    <ul class="summery-contain">
                                        @foreach ($cartItems as $item)
                                            @php($product = $item['product'])
                                            <li>
                                                <img src="{{ asset($product->image ?: 'assets/images/vegetable/product/1.png') }}"
                                                    class="img-fluid blur-up lazyloaded checkout-image" alt="{{ $product->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                                <h4>{{ $product->name }} <span>X {{ $item['quantity'] }}</span></h4>
                                                <h4 class="price">${{ number_format($item['lineTotalCents'] / 100, 2) }}</h4>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <ul class="summery-total">
                                        <li>
                                            <h4>Subtotal</h4>
                                            <h4 class="price">${{ number_format($cartSubtotalCents / 100, 2) }}</h4>
                                        </li>

                                        <li>
                                            <h4>Shipping</h4>
                                            <h4 class="price">
                                                @if ($shippingFeeCents === 0)
                                                    <span class="text-success">FREE</span>
                                                @else
                                                    ${{ number_format($shippingFeeCents / 100, 2) }}
                                                @endif
                                            </h4>
                                        </li>

                                        <li class="list-total">
                                            <h4>Total (USD)</h4>
                                            <h4 class="price">${{ number_format($orderTotalCents / 100, 2) }}</h4>
                                        </li>
                                    </ul>
                                </div>

                                <button type="submit" form="checkout-form" id="checkout-submit-btn" class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold py-3">
                                    <i class="fa-solid fa-lock me-2"></i> Pay with Stripe (${{ number_format($orderTotalCents / 100, 2) }})
                                </button>
                                
                                <div class="mt-3 text-center">
                                    <a href="{{ route('cart.index') }}" class="text-muted small">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Return to cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </section>
    <!-- Checkout section End -->

    <!-- Footer Section Start -->
    @include('website.includes.home-footer')
    <!-- Footer Section End -->

    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather-icon.js') }}"></script>
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripeRadio = document.getElementById('stripe');
            const codRadio = document.getElementById('cash_on_delivery');
            const submitBtn = document.getElementById('checkout-submit-btn');
            const stripeBox = document.getElementById('stripe-option-box');
            const codBox = document.getElementById('cod-option-box');
            const orderTotal = '{{ number_format($orderTotalCents / 100, 2) }}';

            function updatePaymentUI() {
                if (stripeRadio && stripeRadio.checked) {
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay with Stripe ($' + orderTotal + ')';
                        submitBtn.classList.remove('btn-secondary');
                        submitBtn.classList.add('theme-bg-color');
                    }
                    if (stripeBox) stripeBox.style.background = '#f0fdf4';
                    if (stripeBox) stripeBox.style.borderColor = '#22c55e';
                    if (codBox) codBox.style.background = '#ffffff';
                    if (codBox) codBox.style.borderColor = '#e2e8f0';
                } else if (codRadio && codRadio.checked) {
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fa-solid fa-truck-ramp-box me-2"></i> Place Cash On Delivery Order';
                    }
                    if (codBox) codBox.style.background = '#f0fdf4';
                    if (codBox) codBox.style.borderColor = '#22c55e';
                    if (stripeBox) stripeBox.style.background = '#ffffff';
                    if (stripeBox) stripeBox.style.borderColor = '#e2e8f0';
                }
            }

            if (stripeRadio) stripeRadio.addEventListener('change', updatePaymentUI);
            if (codRadio) codRadio.addEventListener('change', updatePaymentUI);

            if (stripeBox) {
                stripeBox.addEventListener('click', function(e) {
                    if (e.target !== stripeRadio) {
                        stripeRadio.checked = true;
                        updatePaymentUI();
                    }
                });
            }

            if (codBox) {
                codBox.addEventListener('click', function(e) {
                    if (e.target !== codRadio) {
                        codRadio.checked = true;
                        updatePaymentUI();
                    }
                });
            }

            updatePaymentUI();
        });
    </script>
@endsection
