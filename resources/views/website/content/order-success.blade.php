@extends('website.layouts.storefront')

@section('title', 'Order Success')
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
                    <div class="breadscrumb-contain breadscrumb-order">
                        <div class="order-box">
                            <div class="order-image">
                                <div class="checkmark">
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="checkmark__check" height="36" viewBox="0 0 48 36" width="48"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M47.248 3.9L43.906.667a2.428 2.428 0 0 0-3.344 0l-23.63 23.09-9.554-9.338a2.432 2.432 0 0 0-3.345 0L.692 17.654a2.236 2.236 0 0 0 .002 3.233l14.567 14.175c.926.894 2.42.894 3.342.01L47.248 7.128c.922-.89.922-2.34 0-3.23">
                                        </path>
                                    </svg>
                                    <svg class="checkmark__background" height="115" viewBox="0 0 120 115" width="120"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M107.332 72.938c-1.798 5.557 4.564 15.334 1.21 19.96-3.387 4.674-14.646 1.605-19.298 5.003-4.61 3.368-5.163 15.074-10.695 16.878-5.344 1.743-12.628-7.35-18.545-7.35-5.922 0-13.206 9.088-18.543 7.345-5.538-1.804-6.09-13.515-10.696-16.877-4.657-3.398-15.91-.334-19.297-5.002-3.356-4.627 3.006-14.404 1.208-19.962C10.93 67.576 0 63.442 0 57.5c0-5.943 10.93-10.076 12.668-15.438 1.798-5.557-4.564-15.334-1.21-19.96 3.387-4.674 14.646-1.605 19.298-5.003C35.366 13.73 35.92 2.025 41.45.22c5.344-1.743 12.628 7.35 18.545 7.35 5.922 0 13.206-9.088 18.543-7.345 5.538 1.804 6.09 13.515 10.696 16.877 4.657 3.398 15.91.334 19.297 5.002 3.356 4.627-3.006 14.404-1.208 19.962C109.07 47.424 120 51.562 120 57.5c0 5.943-10.93 10.076-12.668 15.438z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="order-contain">
                                <h3 class="theme-color">Order Success</h3>
                                <h5 class="text-content">Thank you, {{ $order->customer_name }}. Your order has been placed successfully!</h5>
                                <h6>Order Reference: {{ $order->uuid }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Order Details Section Start -->
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-9 col-lg-8">
                    <div class="cart-table order-table order-table-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="product-detail">
                                                <div class="product border-0">
                                                    @if ($item->product)
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="product-image">
                                                            <img src="{{ asset($item->product->image ?: 'assets/images/vegetable/product/1.png') }}"
                                                                class="img-fluid blur-up lazyload" alt="{{ $item->product_name }}">
                                                        </a>
                                                    @else
                                                        <div class="product-image">
                                                            <img src="{{ asset('assets/images/vegetable/product/1.png') }}"
                                                                class="img-fluid blur-up lazyload" alt="{{ $item->product_name }}">
                                                        </div>
                                                    @endif
                                                    <div class="product-detail">
                                                        <ul>
                                                            <li class="name">
                                                                @if ($item->product)
                                                                    <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product_name }}</a>
                                                                @else
                                                                    <span>{{ $item->product_name }}</span>
                                                                @endif
                                                            </li>

                                                            <li class="text-content">SKU: {{ $item->sku ?? 'N/A' }}</li>

                                                            <li class="text-content">Quantity - {{ $item->quantity }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="price">
                                                <h4 class="table-title text-content">Unit Price</h4>
                                                <h6 class="theme-color">${{ number_format($item->unit_price, 2) }}</h6>
                                            </td>

                                            <td class="quantity">
                                                <h4 class="table-title text-content">Qty</h4>
                                                <h4 class="text-title">{{ str_pad($item->quantity, 2, '0', STR_PAD_LEFT) }}</h4>
                                            </td>

                                            <td class="subtotal">
                                                <h4 class="table-title text-content">Total</h4>
                                                <h5>${{ number_format($item->line_total, 2) }}</h5>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4">
                    <div class="row g-4">
                        <div class="col-lg-12 col-sm-6">
                            <div class="summery-box">
                                <div class="summery-header">
                                    <h3>Order Summary</h3>
                                    <h5 class="ms-auto theme-color">({{ $order->items->count() }} {{ Str::plural('Item', $order->items->count()) }})</h5>
                                </div>

                                <ul class="summery-contain">
                                    <li>
                                        <h4>Subtotal</h4>
                                        <h4 class="price">${{ number_format($order->subtotal, 2) }}</h4>
                                    </li>

                                    <li>
                                        <h4>Shipping Fee</h4>
                                        <h4 class="price theme-color">
                                            @if ((float) $order->shipping_fee === 0.0)
                                                FREE
                                            @else
                                                ${{ number_format($order->shipping_fee, 2) }}
                                            @endif
                                        </h4>
                                    </li>

                                    <li>
                                        <h4>Payment Method</h4>
                                        <h4 class="price text-muted text-uppercase small">{{ str_replace('_', ' ', $order->payment_method) }}</h4>
                                    </li>

                                    <li>
                                        <h4>Order Status</h4>
                                        <h4 class="price text-success fw-bold text-capitalize">{{ $order->status }}</h4>
                                    </li>
                                </ul>

                                <ul class="summery-total">
                                    <li class="list-total">
                                        <h4>Total (USD)</h4>
                                        <h4 class="price theme-color fw-bold">${{ number_format($order->total, 2) }}</h4>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-6">
                            <div class="summery-box">
                                <div class="summery-header d-block">
                                    <h3>Delivery Address</h3>
                                </div>

                                <ul class="summery-contain pb-0 border-bottom-0">
                                    <li class="d-block">
                                        <h4 class="fw-bold">{{ $order->customer_name }}</h4>
                                        <h4 class="mt-2 text-content">{{ $order->address_line }}</h4>
                                        <h4 class="mt-1 text-content">{{ $order->city }}{{ $order->region ? ', ' . $order->region : '' }} {{ $order->postal_code }}</h4>
                                        <h4 class="mt-1 text-content">{{ $order->country }}</h4>
                                        <h4 class="mt-2 text-muted small"><i class="fa-solid fa-phone me-1"></i> {{ $order->phone }}</h4>
                                        <h4 class="mt-1 text-muted small"><i class="fa-solid fa-envelope me-1"></i> {{ $order->email }}</h4>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <a href="{{ route('shop.category') }}" class="btn theme-bg-color text-white fw-bold w-100 py-2">
                                <i class="fa-solid fa-bag-shopping me-2"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Details Section End -->

    <!-- Footer Section Start -->
    @include('website.includes.home-footer')
    <!-- Footer Section End -->

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
