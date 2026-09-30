@extends('website.layouts.storefront')

@section('title', 'Order #' . strtoupper(substr($order->uuid, 0, 8)) . ' - Kenkie')
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')

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
                        <h2>Order Tracking & Invoice</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">My Account</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Order Details</li>
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
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="fw-bold mb-1">Order #{{ $order->uuid }}</h4>
                                <span class="text-muted small">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $badgeClass = match ($order->status) {
                                        'completed' => 'bg-success',
                                        'processing' => 'bg-info',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase fs-6">
                                    {{ $order->status }}
                                </span>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Delivery & Customer Information -->
                            <div class="row g-4 mb-4 pb-4 border-bottom">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light h-100">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-location-dot text-primary me-2"></i> Shipping Address</h6>
                                        <p class="mb-1 fw-semibold text-dark">{{ $order->customer_name }}</p>
                                        <p class="mb-1 text-muted small">{{ $order->address_line }}</p>
                                        <p class="mb-1 text-muted small">{{ $order->city }}, {{ $order->postal_code }}</p>
                                        <p class="mb-0 text-muted small">{{ $order->country }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light h-100">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-credit-card text-primary me-2"></i> Payment & Contact Info</h6>
                                        <p class="mb-1 text-muted small"><strong>Payment Method:</strong> Cash on Delivery (COD)</p>
                                        <p class="mb-1 text-muted small"><strong>Email:</strong> {{ $order->email }}</p>
                                        <p class="mb-0 text-muted small"><strong>Phone:</strong> {{ $order->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchased Items List -->
                            <h5 class="fw-bold mb-3">Order Items ({{ $order->items->count() }})</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Line Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ asset($item->product?->image ?: 'assets/images/product/category/1.jpg') }}" class="rounded border" style="width: 50px; height: 50px; object-fit: contain;" alt="{{ $item->product_name }}">
                                                        <div>
                                                            <strong class="text-dark">{{ $item->product_name }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code>{{ $item->sku }}</code></td>
                                                <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end fw-bold text-dark">${{ number_format((float) $item->line_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Subtotal:</th>
                                            <td class="text-end fw-bold">${{ number_format((float) $order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-end">Shipping Fee:</th>
                                            <td class="text-end fw-bold">
                                                @if ($order->shipping_fee > 0)
                                                    ${{ number_format((float) $order->shipping_fee, 2) }}
                                                @else
                                                    <span class="text-success">Free Delivery</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="table-light">
                                            <th colspan="4" class="text-end fs-5">Order Total:</th>
                                            <td class="text-end fs-5 fw-bold text-success">${{ number_format((float) $order->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fa-solid fa-print me-1"></i> Print Invoice
                                </button>
                                <a href="{{ route('shop.category') }}" class="btn btn-primary ms-2">
                                    <i class="fa-solid fa-bag-shopping me-1"></i> Continue Shopping
                                </a>
                            </div>
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
