@extends('website.layouts.storefront')

@section('title', 'My Account - Kenkie')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
<style>
    .dashboard-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.25);
    }
    .user-nav-tab {
        border-radius: 10px;
        padding: 12px 18px;
        font-weight: 600;
        color: #4b5563;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        margin-bottom: 6px;
        text-decoration: none;
        background: transparent;
        width: 100%;
        text-align: left;
    }
    .user-nav-tab:hover {
        background: #f3f4f6;
        color: #111827;
    }
    .user-nav-tab.active {
        background: #22c55e;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
    }
    .stat-metric-card {
        border-radius: 14px;
        padding: 22px;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease;
    }
    .stat-metric-card:hover {
        transform: translateY(-2px);
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .order-card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .order-card-header {
        background: #f9fafb;
        padding: 16px 22px;
        border-bottom: 1px solid #e5e7eb;
    }
    .order-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 20px 0 10px;
    }
    .order-timeline::before {
        content: '';
        position: absolute;
        top: 14px;
        left: 20px;
        right: 20px;
        height: 3px;
        background: #e5e7eb;
        z-index: 1;
    }
    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        background: #ffffff;
        padding: 0 8px;
    }
    .timeline-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 6px;
        font-size: 13px;
    }
    .timeline-step.completed .timeline-dot {
        background: #22c55e;
        color: #ffffff;
    }
    .timeline-step.current .timeline-dot {
        background: #3b82f6;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
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
                        <h2>My Account</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- User Dashboard Section Start -->
    <section class="user-dashboard-section section-b-space">
        <div class="container-fluid-lg">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <div>{{ session('status') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Sidebar -->
                <div class="col-xxl-3 col-lg-4">
                    <div class="dashboard-left-sidebar card border-0 shadow-sm rounded-4 p-4">
                        <div class="text-center pb-4 border-bottom">
                            <div class="dashboard-avatar mx-auto mb-3">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">{{ $user->email }}</p>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 mt-2">
                                Active Customer
                            </span>
                        </div>

                        <div class="nav flex-column nav-pills mt-4" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="user-nav-tab active" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-selected="true">
                                <i class="fa-solid fa-chart-pie" style="width: 20px;"></i>
                                <span>Dashboard Overview</span>
                            </button>

                            <button class="user-nav-tab" id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-box-open" style="width: 20px;"></i>
                                <span>My Orders & Tracking</span>
                                <span class="badge bg-light text-dark ms-auto">{{ $orders->count() }}</span>
                            </button>

                            <a href="{{ route('wishlist.index') }}" class="user-nav-tab text-decoration-none">
                                <i class="fa-solid fa-heart" style="width: 20px;"></i>
                                <span>My Wishlist</span>
                                <span class="badge bg-light text-dark ms-auto">{{ $wishlistCount }}</span>
                            </a>

                            <a href="{{ route('cart.index') }}" class="user-nav-tab text-decoration-none">
                                <i class="fa-solid fa-cart-shopping" style="width: 20px;"></i>
                                <span>My Cart</span>
                                <span class="badge bg-light text-dark ms-auto">{{ $cartCount }}</span>
                            </a>

                            <button class="user-nav-tab" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-user-gear" style="width: 20px;"></i>
                                <span>Profile & Security</span>
                            </button>
                        </div>

                        <div class="pt-4 mt-3 border-top">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Content Panel -->
                <div class="col-xxl-9 col-lg-8">
                    <div class="tab-content" id="v-pills-tabContent">
                        <!-- TAB 1: DASHBOARD OVERVIEW -->
                        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                            <!-- Welcome Banner -->
                            <div class="card border-0 rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #15803d 0%, #22c55e 100%);">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div>
                                        <h3 class="fw-bold mb-1 text-white">Hello, {{ $user->name }}!</h3>
                                        <p class="text-white-50 mb-0">Track your recent orders, manage your delivery address, and view your store activity.</p>
                                    </div>
                                    <a href="{{ route('shop.category') }}" class="btn btn-light text-success fw-bold px-4 py-2">
                                        <i class="fa-solid fa-bag-shopping me-1"></i> Shop Now
                                    </a>
                                </div>
                            </div>

                            <!-- Metrics Counters -->
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6 col-xl-3">
                                    <div class="stat-metric-card">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-muted fw-semibold small">TOTAL ORDERS</span>
                                            <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                        </div>
                                        <h3 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h3>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="stat-metric-card">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-muted fw-semibold small">PENDING</span>
                                            <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                                                <i class="fa-solid fa-clock"></i>
                                            </div>
                                        </div>
                                        <h3 class="fw-bold mb-0">{{ $stats['pending_orders'] }}</h3>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="stat-metric-card">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-muted fw-semibold small">PROCESSING</span>
                                            <div class="stat-icon-wrapper bg-info-subtle text-info">
                                                <i class="fa-solid fa-truck-fast"></i>
                                            </div>
                                        </div>
                                        <h3 class="fw-bold mb-0">{{ $stats['processing_orders'] }}</h3>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="stat-metric-card">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="text-muted fw-semibold small">COMPLETED</span>
                                            <div class="stat-icon-wrapper bg-success-subtle text-success">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                        </div>
                                        <h3 class="fw-bold mb-0">{{ $stats['completed_orders'] }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Orders Card -->
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0 text-dark">Recent Orders</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('v-pills-orders-tab').click()">
                                        View All ({{ $orders->count() }})
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Order Ref</th>
                                                    <th>Date</th>
                                                    <th>Items</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders->take(5) as $order)
                                                    <tr>
                                                        <td>
                                                            <code class="fw-bold text-dark">#{{ strtoupper(substr($order->uuid, 0, 8)) }}</code>
                                                        </td>
                                                        <td class="text-muted small">
                                                            {{ $order->created_at->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border">{{ $order->items->count() }} items</span>
                                                        </td>
                                                        <td>
                                                            <strong class="text-success">${{ number_format((float) $order->total, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $badgeClass = match ($order->status) {
                                                                    'completed' => 'bg-success',
                                                                    'processing' => 'bg-info',
                                                                    'cancelled' => 'bg-danger',
                                                                    default => 'bg-warning text-dark',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }} px-2 py-1 text-uppercase" style="font-size: 11px;">
                                                                {{ $order->status }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('account.orders.show', $order->uuid) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa-solid fa-eye me-1"></i> Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">
                                                            You have not placed any orders yet. <a href="{{ route('shop.category') }}" class="theme-color fw-semibold">Start shopping</a>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: MY ORDERS & TRACKING -->
                        <div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-1">My Orders & Tracking</h4>
                                    <p class="text-muted mb-0">View your order history and live delivery progress.</p>
                                </div>
                                <span class="badge bg-primary fs-6 px-3 py-2">{{ $orders->count() }} Orders Placed</span>
                            </div>

                            @forelse ($orders as $order)
                                @php
                                    $badgeClass = match ($order->status) {
                                        'completed' => 'bg-success',
                                        'processing' => 'bg-info',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                    $isStep1 = true;
                                    $isStep2 = in_array($order->status, ['processing', 'completed']);
                                    $isStep3 = in_array($order->status, ['completed']);
                                @endphp
                                <div class="order-card">
                                    <div class="order-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <span class="text-muted small d-block">ORDER REFERENCE</span>
                                            <strong class="fs-6 font-monospace text-dark">#{{ $order->uuid }}</strong>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase fs-6">
                                                {{ $order->status }}
                                            </span>
                                            <a href="{{ route('account.orders.show', $order->uuid) }}" class="btn btn-sm btn-primary">
                                                <i class="fa-solid fa-receipt me-1"></i> Invoice & Tracking
                                            </a>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <!-- Tracking Timeline -->
                                        <div class="order-timeline">
                                            <div class="timeline-step {{ $isStep1 ? 'completed' : '' }}">
                                                <div class="timeline-dot"><i class="fa-solid fa-check"></i></div>
                                                <small class="fw-semibold d-block">Order Placed</small>
                                                <span class="text-muted" style="font-size: 11px;">{{ $order->created_at->format('M d, H:i') }}</span>
                                            </div>

                                            <div class="timeline-step {{ $isStep2 ? 'completed' : ($order->status === 'pending' ? 'current' : '') }}">
                                                <div class="timeline-dot"><i class="fa-solid fa-box"></i></div>
                                                <small class="fw-semibold d-block">Processing</small>
                                                <span class="text-muted" style="font-size: 11px;">In warehouse</span>
                                            </div>

                                            <div class="timeline-step {{ $isStep3 ? 'completed' : ($order->status === 'processing' ? 'current' : '') }}">
                                                <div class="timeline-dot"><i class="fa-solid fa-truck"></i></div>
                                                <small class="fw-semibold d-block">Out for Delivery</small>
                                                <span class="text-muted" style="font-size: 11px;">Courier dispatch</span>
                                            </div>

                                            <div class="timeline-step {{ $order->status === 'completed' ? 'completed' : '' }}">
                                                <div class="timeline-dot"><i class="fa-solid fa-house-chimney"></i></div>
                                                <small class="fw-semibold d-block">Delivered</small>
                                                <span class="text-muted" style="font-size: 11px;">{{ $order->status === 'completed' ? 'Delivered' : 'Pending' }}</span>
                                            </div>
                                        </div>

                                        <!-- Order Items List -->
                                        <div class="border-top pt-3 mt-3">
                                            <div class="row g-3">
                                                @foreach ($order->items as $item)
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center gap-3 p-2 border rounded-3 bg-light">
                                                            <img src="{{ asset($item->product?->image ?: 'assets/images/product/category/1.jpg') }}" class="rounded border" style="width: 50px; height: 50px; object-fit: contain;" alt="{{ $item->product_name }}">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <h6 class="fw-bold text-dark text-truncate mb-0">{{ $item->product_name }}</h6>
                                                                <small class="text-muted">Qty: {{ $item->quantity }} &times; ${{ number_format((float) $item->unit_price, 2) }}</small>
                                                            </div>
                                                            <span class="fw-bold text-dark">${{ number_format((float) $item->line_total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Summary Bar -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 mt-3 border-top">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <div class="text-muted small me-2">
                                                    <i class="fa-solid fa-location-dot me-1"></i> Delivery to: <strong>{{ $order->address_line }}, {{ $order->city }}, {{ $order->country }}</strong>
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    @if ($order->payment_method === 'stripe')
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11px;">
                                                            <i class="fa-brands fa-stripe me-1"></i> Stripe
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-dark border" style="font-size: 11px;">
                                                            COD
                                                        </span>
                                                    @endif

                                                    @if ($order->payment_status === 'paid')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                                            <i class="fa-solid fa-check me-1"></i> Paid
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 11px;">
                                                            <i class="fa-regular fa-clock me-1"></i> Pending
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="text-muted small">Subtotal: ${{ number_format((float) $order->subtotal, 2) }}</span>
                                                <span class="text-muted small">Shipping: ${{ number_format((float) $order->shipping_fee, 2) }}</span>
                                                <span class="fs-5 fw-bold text-success">Total: ${{ number_format((float) $order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card p-5 text-center border-0 shadow-sm rounded-4">
                                    <i class="fa-solid fa-box-open text-muted fs-1 mb-3"></i>
                                    <h4 class="fw-bold">No Orders Found</h4>
                                    <p class="text-muted">You have not made any purchases with this account yet.</p>
                                    <div>
                                        <a href="{{ route('shop.category') }}" class="btn btn-primary px-4">
                                            <i class="fa-solid fa-bag-shopping me-1"></i> Start Shopping
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- TAB 3: PROFILE & SECURITY -->
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="fw-bold mb-0 text-dark">Profile Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('account.profile.update') }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="name">Full Name <span class="text-danger">*</span></label>
                                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="email">Email Address <span class="text-danger">*</span></label>
                                                <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                                    <i class="fa-solid fa-floppy-disk me-1"></i> Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="fw-bold mb-0 text-dark">Change Password</h5>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('account.password.update') }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold" for="current_password">Current Password <span class="text-danger">*</span></label>
                                                <input class="form-control @error('current_password') is-invalid @enderror" id="current_password" type="password" name="current_password" required>
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="password">New Password <span class="text-danger">*</span></label>
                                                <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                                                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required>
                                            </div>

                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-outline-primary px-4 fw-semibold">
                                                    <i class="fa-solid fa-key me-1"></i> Change Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- User Dashboard Section End -->

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->
@endsection
