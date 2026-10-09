@extends('website.layouts.storefront')

@section('title', 'Your cart')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .cart-section {
            padding: 30px 0 60px;
        }
        .cart-table-wrapper {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }
        .cart-custom-table thead th {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4b5563;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 18px;
        }
        .cart-custom-table tbody td {
            padding: 18px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .cart-custom-table tbody tr:last-child td {
            border-bottom: none;
        }
        .cart-thumb {
            width: 68px;
            height: 68px;
            object-fit: contain;
            background: #ffffff;
            padding: 4px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .cart-quantity-box {
            display: inline-flex !important;
            align-items: center !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            padding: 2px 4px !important;
            width: 110px !important;
            height: 36px !important;
            position: static !important;
        }
        .cart-quantity-box .qty-control-btn {
            width: 28px !important;
            height: 28px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #f3f4f6 !important;
            border: none !important;
            border-radius: 4px !important;
            color: #374151 !important;
            font-size: 11px !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            position: static !important;
        }
        .cart-quantity-box .qty-control-btn:hover {
            background: #22c55e !important;
            color: #ffffff !important;
        }
        .cart-quantity-box .qty-input {
            border: none !important;
            background: transparent !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            color: #111827 !important;
            width: 44px !important;
            padding: 0 !important;
            height: 100% !important;
            text-align: center !important;
            box-shadow: none !important;
            position: static !important;
        }
        .hover-theme:hover {
            color: #22c55e !important;
        }
        .coupon-input {
            border: 1px solid #d1d5db;
            border-right: none;
            font-size: 14px;
        }
        .coupon-input:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.25);
        }
        .cart-summary-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
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
                        <h2>Shopping Cart</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Cart Section Start -->
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @error('quantity')
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @enderror

            @if ($cartItems->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i data-feather="shopping-bag" style="width: 64px; height: 64px;" class="text-muted"></i>
                        </div>
                        <h3>Your cart is empty.</h3>
                        <p class="text-muted">Explore our curated collections and find something you love.</p>
                        <a href="{{ route('shop.category') }}" class="btn theme-bg-color text-white fw-bold mt-3 px-4 py-2">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @else
                @php($shippingFeeCents = $cartSubtotalCents >= \App\Services\StorefrontCart::FREE_SHIPPING_THRESHOLD_CENTS ? 0 : \App\Services\StorefrontCart::SHIPPING_FEE_CENTS)
                <div class="row g-4">
                    <!-- Cart Table Column -->
                    <div class="col-xxl-9 col-lg-8">
                        <div class="cart-table-wrapper">
                            <div class="table-responsive">
                                <table class="table cart-custom-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 280px;">Product</th>
                                            <th scope="col" style="min-width: 110px;">Price</th>
                                            <th scope="col" style="min-width: 140px;">Quantity</th>
                                            <th scope="col" style="min-width: 110px;">Total</th>
                                            <th scope="col" class="text-end" style="min-width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            @php($product = $item['product'])
                                            <tr class="cart-item-row">
                                                <td class="product-info-cell">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="flex-shrink-0">
                                                            <img src="{{ asset($item['displayImage'] ?? $product->image ?: 'assets/images/vegetable/product/1.png') }}"
                                                                class="cart-thumb" alt="{{ $item['displayName'] ?? $product->name }}">
                                                        </a>
                                                        <div class="product-info-meta">
                                                            <h6 class="mb-1 fw-bold">
                                                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none text-truncate-2">
                                                                    {{ $item['displayName'] ?? $product->name }}
                                                                </a>
                                                            </h6>
                                                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                                                                <span>Sold By: <strong class="text-secondary">{{ $product->category?->name ?? 'Kenkie' }}</strong></span>
                                                                <span>•</span>
                                                                <span>SKU: {{ $item['displaySku'] ?? $product->sku }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="price-cell">
                                                    <div class="fw-semibold text-dark fs-6">${{ number_format($item['effectiveUnitPriceCents'] / 100, 2) }}</div>
                                                    @if (!empty($item['appliedOffer']))
                                                        <span class="badge bg-warning text-dark border border-warning-subtle mt-1" style="font-size: 11px;">
                                                            <i class="fa-solid fa-tags me-1"></i> {{ $item['appliedOffer']->title ?: ('Multi-Buy ' . (int)$item['appliedOffer']->discount_percentage . '% OFF') }}
                                                        </span>
                                                    @elseif(!empty($item['variation']))
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">Variation Option</span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">In Stock</span>
                                                    @endif
                                                </td>

                                                <td class="qty-cell">
                                                    <div class="cart-quantity-box">
                                                        <button type="button" class="btn qty-control-btn qty-left-minus" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" aria-label="Decrease">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                        <input type="text" class="form-control qty-input text-center"
                                                            value="{{ $item['quantity'] }}" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" max="{{ $item['maxStock'] ?? $product->stock }}" readonly>
                                                        <button type="button" class="btn qty-control-btn qty-right-plus" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" aria-label="Increase">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="total-cell">
                                                    <div class="fw-bold theme-color fs-6">
                                                        ${{ number_format($item['lineTotalCents'] / 100, 2) }}
                                                    </div>
                                                </td>

                                                <td class="action-cell text-end">
                                                    <div class="d-inline-flex flex-column align-items-end gap-1">
                                                        <form method="POST" action="{{ route('wishlist.store', $product->slug) }}" class="wishlist-form m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-link p-0 text-muted text-decoration-none small hover-theme">
                                                                <i class="fa-regular fa-heart me-1"></i>Save for later
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('cart.destroy', $product->slug) }}" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                                                            <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none small">
                                                                <i class="fa-regular fa-trash-can me-1"></i>Remove
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary Sidebar -->
                    <div class="col-xxl-3 col-lg-4">
                        <div class="cart-summary-card position-sticky" style="top: 100px;">
                            <h4 class="fw-bold mb-3 pb-2 border-bottom text-dark">Cart Total</h4>

                            <div class="coupon-block mb-4">
                                <label class="form-label text-muted small fw-semibold mb-1">Coupon Apply</label>
                                <div class="input-group">
                                    <input type="text" class="form-control coupon-input" placeholder="Enter Coupon Code...">
                                    <button class="btn theme-bg-color text-white px-3 fw-semibold" type="button">Apply</button>
                                </div>
                            </div>

                            <div class="summary-breakdown mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold text-dark">${{ number_format($cartSubtotalCents / 100, 2) }}</span>
                                </div>
                                @if (!empty($cartSavingsCents) && $cartSavingsCents > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-2 text-success">
                                        <span><i class="fa-solid fa-tag me-1"></i> Multi-Buy Savings</span>
                                        <span class="fw-bold">-${{ number_format($cartSavingsCents / 100, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Delivery</span>
                                    <span class="fw-semibold {{ $shippingFeeCents === 0 ? 'text-success' : 'text-dark' }}">
                                        @if ($shippingFeeCents === 0)
                                            FREE
                                        @else
                                            ${{ number_format($shippingFeeCents / 100, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-3 border-top border-bottom mb-4">
                                <span class="fw-bold text-dark fs-5">Total (USD)</span>
                                <span class="fw-bold theme-color fs-4">${{ number_format(($cartSubtotalCents + $shippingFeeCents) / 100, 2) }}</span>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout.index') }}" class="btn theme-bg-color text-white py-2 fw-bold text-center">
                                    Process To Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                                @guest
                                    <small class="text-muted text-center d-block">
                                        <i class="fa-solid fa-user-lock me-1 text-secondary"></i> Sign in or register is required to place your order.
                                    </small>
                                @endguest
                                <a href="{{ route('shop.category') }}" class="btn btn-light py-2 text-dark text-center fw-medium border">
                                    <i class="fa-solid fa-arrow-left-long me-2"></i>Return To Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Cart Section End -->

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
