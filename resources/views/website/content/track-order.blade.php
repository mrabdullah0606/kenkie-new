@extends('website.layouts.storefront')

@section('title', 'Track Your Order - Kenkie')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('body')
    <!-- Loader Start -->
    <div class="fullpage-loader">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <!-- Loader End -->

    @include('website.includes.home-header')

    <!-- Breadcrumb -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Track Your Order</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Track Order</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space">
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9">

                    <!-- Lookup Form -->
                    <div class="card border-0 shadow-sm rounded-4 mb-5">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="mb-3" style="font-size: 3rem;">
                                    <i class="fa-solid fa-satellite-dish text-success"></i>
                                </div>
                                <h3 class="fw-bold text-dark">Track Your Parcel</h3>
                                <p class="text-muted">Enter your order number and the email address used at checkout.</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $errors->first() }}</span>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('track-order.lookup') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="order_number" class="form-label fw-semibold">Order Number</label>
                                    <input type="text" name="order_number" id="order_number"
                                        class="form-control form-control-lg font-monospace @error('order_number') is-invalid @enderror"
                                        value="{{ old('order_number') }}"
                                        placeholder="e.g. KNK-1001 or your order UUID">
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="Email used at checkout">
                                </div>
                                <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold">
                                    <i class="fa-solid fa-magnifying-glass me-2"></i> Track Order
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Results --}}
                    @if (isset($order))
                        @php
                            $courierInfo  = $order->courier_info;
                            $statusInfo   = $order->tracking_status_info;
                            $liveUrl      = $order->live_tracking_url;

                            $badgeClass = match ($order->status) {
                                'completed'  => 'bg-success',
                                'processing' => 'bg-info',
                                'shipped', 'in_transit' => 'bg-primary',
                                'delivered'  => 'bg-success',
                                'cancelled'  => 'bg-danger',
                                default      => 'bg-warning text-dark',
                            };

                            $timelineStatuses = [
                                ['label' => 'Order Placed',   'icon' => 'fa-solid fa-receipt',         'active' => true],
                                ['label' => 'Processing',     'icon' => 'fa-solid fa-box',              'active' => in_array($order->status, ['processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'completed'])],
                                ['label' => 'Shipped',        'icon' => 'fa-solid fa-truck-fast',      'active' => in_array($order->status, ['shipped', 'in_transit', 'out_for_delivery', 'delivered', 'completed'])],
                                ['label' => 'Out for Delivery','icon'=> 'fa-solid fa-person-biking',   'active' => in_array($order->status, ['out_for_delivery', 'delivered', 'completed'])],
                                ['label' => 'Delivered',      'icon' => 'fa-solid fa-house-chimney',   'active' => in_array($order->status, ['delivered', 'completed'])],
                            ];
                        @endphp

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            {{-- Header --}}
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 p-4"
                                style="background: linear-gradient(135deg,#15803d,#22c55e); color:#fff;">
                                <div>
                                    <p class="mb-1 small opacity-75">ORDER REFERENCE</p>
                                    <h4 class="fw-bold mb-0 font-monospace">{{ $order->formatted_order_id }}</h4>
                                    <p class="mb-0 small opacity-75 mt-1">Placed {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <span class="badge {{ $badgeClass }} px-3 py-2 fs-6 text-uppercase">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>

                            <div class="card-body p-4">
                                {{-- Courier + Tracking --}}
                                @if ($order->tracking_number)
                                    <div class="alert border rounded-3 d-flex align-items-center gap-3 py-3 px-4 mb-4"
                                        style="background:#f0fdf4; border-color:#86efac!important;">
                                        <div class="fs-2">
                                            <i class="{{ $courierInfo['icon'] }} text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                <span class="badge {{ $courierInfo['badge_class'] }} px-2 py-1">
                                                    {{ $courierInfo['name'] }}
                                                </span>
                                                <span class="badge {{ $statusInfo['badge_class'] }} px-2 py-1">
                                                    {{ $statusInfo['label'] }}
                                                </span>
                                            </div>
                                            <p class="mb-0">
                                                <strong>Tracking #:</strong>
                                                <span class="font-monospace">{{ $order->tracking_number }}</span>
                                            </p>
                                        </div>
                                        @if ($liveUrl)
                                            <a href="{{ $liveUrl }}" target="_blank"
                                                class="btn btn-success fw-semibold text-nowrap">
                                                <i class="fa-solid fa-satellite-dish me-1"></i> Track on {{ $courierInfo['name'] }}
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-light border text-muted mb-4">
                                        <i class="fa-solid fa-hourglass-half me-2"></i>
                                        Tracking information will be available once your order has been dispatched.
                                    </div>
                                @endif

                                {{-- Progress Timeline --}}
                                <h6 class="fw-bold text-dark mb-3">Delivery Progress</h6>
                                <div class="d-flex justify-content-between position-relative mb-4" style="padding: 0 10px;">
                                    <div class="position-absolute" style="top:22px;left:30px;right:30px;height:3px;background:#e5e7eb;z-index:1;"></div>
                                    @foreach ($timelineStatuses as $step)
                                        <div class="text-center" style="z-index:2; flex:1;">
                                            <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle border-2"
                                                style="width:44px;height:44px;background:{{ $step['active'] ? '#22c55e' : '#f3f4f6' }};border:3px solid {{ $step['active'] ? '#22c55e' : '#e5e7eb' }};color:{{ $step['active'] ? '#fff' : '#9ca3af' }};">
                                                <i class="{{ $step['icon'] }} small"></i>
                                            </div>
                                            <small class="fw-semibold d-block" style="font-size:11px;color:{{ $step['active'] ? '#15803d' : '#9ca3af' }};">
                                                {{ $step['label'] }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Shipping Address --}}
                                <div class="p-3 border rounded-3 bg-light">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fa-solid fa-location-dot text-primary me-2"></i> Delivering To
                                    </h6>
                                    <p class="mb-0 text-muted small">
                                        {{ $order->customer_name }}<br>
                                        {{ $order->address_line }}<br>
                                        {{ $order->city }}@if ($order->region), {{ $order->region }}@endif<br>
                                        {{ $order->postal_code }}, {{ $order->country }}
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer bg-white px-4 py-3 d-flex gap-2">
                                @auth
                                    <a href="{{ route('account.orders.show', $order->uuid) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-receipt me-1"></i> Full Invoice
                                    </a>
                                @endauth
                                <a href="{{ route('track-order.show') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Track Another Order
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    @include('website.includes.home-footer')
@endsection
