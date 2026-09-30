@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Title & Header Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Admin Dashboard</h3>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name ?? 'Admin' }}! Here is what's happening today.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1" style="font-size: 13px;">TOTAL REVENUE</span>
                    <h3 class="fw-bold mb-0 text-dark">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="icon-box green">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1" style="font-size: 13px;">TOTAL ORDERS</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($orderCount) }}</h3>
                </div>
                <div class="icon-box blue">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1" style="font-size: 13px;">TOTAL PRODUCTS</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($productCount) }}</h3>
                </div>
                <div class="icon-box purple">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1" style="font-size: 13px;">ACTIVE CATEGORIES</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($categoryCount) }}</h3>
                </div>
                <div class="icon-box amber">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Row: Recent Orders & Top Products -->
    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-xxl-8 col-xl-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <span class="badge bg-light text-dark fw-bold px-3 py-2 border">Latest {{ $latestOrders->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order Reference</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestOrders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark">#{{ substr($order->uuid, 0, 8) }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                            <small class="text-muted">{{ $order->email }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format((float) $order->total, 2) }}</span>
                                        </td>
                                        <td>
                                            @if ($order->status === 'completed' || $order->status === 'paid')
                                                <span class="badge bg-success px-2 py-1">Completed</span>
                                            @elseif ($order->status === 'pending')
                                                <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $order->created_at?->diffForHumans() ?? 'Just now' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-box-open fs-3 d-block mb-2 text-muted"></i>
                                            No orders placed yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="col-xxl-4 col-xl-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Featured Products</h5>
                    <a href="{{ route('admin.products.index') }}" class="text-success text-decoration-none fw-semibold" style="font-size: 13px;">View All</a>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        @forelse ($featuredProducts as $prod)
                            <li class="d-flex align-items-center gap-3 p-2 rounded-3 shadow-none" style="border: 1px solid #eef2f6; background: #ffffff;">
                                <img src="{{ asset($prod->image ?: 'assets/images/vegetable/product/1.png') }}" class="rounded-3 border p-1" style="width: 50px; height: 50px; min-width: 50px; object-fit: contain; background: #fbfcfd;" alt="{{ $prod->name }}">
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h6 class="text-dark fw-bold mb-1 text-truncate" style="font-size: 13.5px;" title="{{ $prod->name }}">{{ $prod->name }}</h6>
                                    <small class="text-muted d-block text-truncate" style="font-size: 12px;">{{ $prod->category?->name ?? 'General' }} · Stock: {{ $prod->stock }}</small>
                                </div>
                                <div class="text-end flex-shrink-0" style="min-width: 70px;">
                                    <span class="fw-bold text-success d-block" style="font-size: 13.5px;">${{ number_format((float) $prod->price, 2) }}</span>
                                    <a href="{{ route('admin.products.edit', $prod) }}" class="btn btn-sm btn-outline-primary py-0 px-2 mt-1" style="font-size: 11px;">Edit</a>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-4 text-muted">No featured products found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection