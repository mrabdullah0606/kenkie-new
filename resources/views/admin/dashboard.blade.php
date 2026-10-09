@extends('admin.layouts.app')

@section('title', 'Admin Dashboard & Analytics')

@section('content')
<div class="container-fluid">
    <!-- Page Title & Header Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Executive Dashboard & Analytics</h3>
            <p class="text-muted mb-0">Real-time performance analytics, revenue insights, order trends, and store metrics.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0 d-flex justify-content-sm-end justify-content-start gap-2 flex-wrap">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check"></i> Manage Orders
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards Row 1: High Level Store Performance -->
    <div class="row g-3 mb-4">
        <div class="col-xxl-3 col-md-6">
            <div class="metric-card bg-white p-3 rounded-3 border shadow-sm h-100 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL REVENUE</span>
                    <h3 class="fw-bold mb-1 text-dark">${{ number_format($totalRevenue, 2) }}</h3>
                    <small class="text-success fw-semibold"><i class="fa-solid fa-calendar-day me-1"></i> Today: ${{ number_format($todayRevenue, 2) }}</small>
                </div>
                <div class="icon-box green rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(13, 164, 135, 0.12); color: #0da487; font-size: 20px;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="metric-card bg-white p-3 rounded-3 border shadow-sm h-100 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL ORDERS</span>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($orderCount) }}</h3>
                    <small class="text-primary fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> {{ $completedOrdersCount }} Completed</small>
                </div>
                <div class="icon-box blue rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(59, 130, 246, 0.12); color: #3b82f6; font-size: 20px;">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="metric-card bg-white p-3 rounded-3 border shadow-sm h-100 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">AVG ORDER VALUE (AOV)</span>
                    <h3 class="fw-bold mb-1 text-dark">${{ number_format($averageOrderValue, 2) }}</h3>
                    <small class="text-muted"><i class="fa-solid fa-chart-pie me-1"></i> Per Successful Order</small>
                </div>
                <div class="icon-box purple rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; font-size: 20px;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="metric-card bg-white p-3 rounded-3 border shadow-sm h-100 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL CUSTOMERS</span>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($customerCount) }}</h3>
                    <small class="text-muted"><i class="fa-solid fa-users me-1"></i> {{ $registeredUserCount }} Accounts</small>
                </div>
                <div class="icon-box amber rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(245, 158, 11, 0.12); color: #f59e0b; font-size: 20px;">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Sales & Revenue Trend Chart -->
        <div class="col-xxl-8 col-xl-7">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-area text-success"></i> Sales & Revenue Growth Trend
                        </h5>
                        <small class="text-muted">Monthly performance over the last 6 months</small>
                    </div>
                    <span class="badge bg-success-subtle text-success px-3 py-1 fw-bold">Live Updates</span>
                </div>
                <div class="card-body p-4">
                    <div style="height: 320px; position: relative;">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Doughnut Chart -->
        <div class="col-xxl-4 col-xl-5">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-primary"></i> Order Breakdown
                        </h5>
                        <small class="text-muted">Fulfillment & payment distribution</small>
                    </div>
                </div>
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                    <div style="height: 230px; width: 100%; max-width: 250px; position: relative;" class="mb-3">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="w-100 d-flex justify-content-around text-center border-top pt-3 mt-2">
                        <div>
                            <span class="text-muted small d-block">Pending</span>
                            <span class="fw-bold text-warning fs-6">{{ $pendingOrdersCount }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Completed</span>
                            <span class="fw-bold text-success fs-6">{{ $completedOrdersCount }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Cancelled</span>
                            <span class="fw-bold text-danger fs-6">{{ $cancelledOrdersCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Row: Top Sellers & Low Stock Alerts -->
    <div class="row g-4 mb-4">
        <!-- Top Selling Products -->
        <div class="col-lg-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-trophy text-warning"></i> Top Selling Products
                    </h5>
                    <small class="text-muted">By order volume</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Product Name</th>
                                    <th>SKU</th>
                                    <th class="text-center">Units Sold</th>
                                    <th class="text-end pe-3">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topSellingProducts as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="fw-semibold text-dark">{{ $item->product_name }}</span>
                                        </td>
                                        <td><code class="text-muted">{{ $item->sku ?: 'N/A' }}</code></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary px-2 py-1">{{ $item->total_qty }}</span>
                                        </td>
                                        <td class="text-end pe-3 fw-bold text-success">
                                            ${{ number_format((float) $item->total_sales, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-bag-shopping d-block fs-3 mb-2 text-muted"></i>
                                            Sales data will populate once orders are placed.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts / Low Stock -->
        <div class="col-lg-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i> Inventory Stock Alerts
                    </h5>
                    <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1">{{ $lowStockCount }} Low Stock</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lowStockProducts as $prod)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset($prod->image ?: 'assets/images/vegetable/product/1.png') }}" class="rounded border p-1" style="width: 36px; height: 36px; object-fit: contain;" alt="{{ $prod->name }}">
                                                <span class="fw-semibold text-dark text-truncate" style="max-width: 180px;">{{ $prod->name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $prod->category?->name ?? 'General' }}</span></td>
                                        <td>${{ number_format((float) $prod->price, 2) }}</td>
                                        <td>
                                            @if ($prod->stock <= 0)
                                                <span class="badge bg-danger">0 Out of Stock</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $prod->stock }} Left</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.products.edit', $prod) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;">Restock</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-success">
                                            <i class="fa-solid fa-check-double d-block fs-3 mb-2"></i>
                                            All product inventories are well stocked!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Row: Recent Orders & Featured Products -->
    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-xxl-8 col-xl-7">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-bold mb-0 text-dark">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="text-success fw-semibold text-decoration-none" style="font-size: 13px;">View All Orders &rarr;</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Order Reference</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th class="pe-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestOrders as $order)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-primary text-decoration-none">
                                                {{ $order->formatted_order_id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                            <small class="text-muted">{{ $order->email }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format((float) $order->total, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ ucfirst($order->payment_method ?? 'card') }}</span>
                                        </td>
                                        <td>
                                            @if ($order->status === 'completed' || $order->status === 'paid')
                                                <span class="badge bg-success px-2 py-1">Completed</span>
                                            @elseif ($order->status === 'pending')
                                                <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                                            @elseif ($order->status === 'processing')
                                                <span class="badge bg-info text-dark px-2 py-1">Processing</span>
                                            @elseif ($order->status === 'cancelled')
                                                <span class="badge bg-danger px-2 py-1">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="pe-3">
                                            <small class="text-muted">{{ $order->created_at?->diffForHumans() ?? 'Just now' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
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

        <!-- Catalog Quick Summary & Featured Items -->
        <div class="col-xxl-4 col-xl-5">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-bold mb-0 text-dark">Featured Catalog Items</h5>
                    <a href="{{ route('admin.products.index') }}" class="text-success text-decoration-none fw-semibold" style="font-size: 13px;">View All</a>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        @forelse ($featuredProducts as $prod)
                            <li class="d-flex align-items-center gap-3 p-2 rounded-3" style="border: 1px solid #eef2f6; background: #ffffff;">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. Revenue & Order Trends Chart ──
    const revenueCtx = document.getElementById('revenueTrendChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Revenue ($)',
                        data: @json($chartRevenueData),
                        borderColor: '#0da487',
                        backgroundColor: 'rgba(13, 164, 135, 0.1)',
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'y',
                        pointBackgroundColor: '#0da487',
                        pointRadius: 4,
                    },
                    {
                        label: 'Orders Count',
                        data: @json($chartOrderData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        borderDash: [5, 5],
                        tension: 0.35,
                        yAxisID: 'y1',
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y') {
                                    return 'Revenue: $' + context.raw.toFixed(2);
                                }
                                return 'Orders: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    }

    // ── 2. Order Status Doughnut Chart ──
    const statusCtx = document.getElementById('orderStatusChart');
    if (statusCtx) {
        const completed = {{ (int) ($statusCounts['completed'] ?? $statusCounts['paid'] ?? 0) }};
        const pending = {{ (int) ($statusCounts['pending'] ?? 0) }};
        const processing = {{ (int) ($statusCounts['processing'] ?? 0) }};
        const cancelled = {{ (int) ($statusCounts['cancelled'] ?? 0) }};

        const totalOrders = completed + pending + processing + cancelled;

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
                datasets: [{
                    data: totalOrders > 0 ? [completed, pending, processing, cancelled] : [1, 0, 0, 0],
                    backgroundColor: totalOrders > 0 ? ['#0da487', '#f59e0b', '#3b82f6', '#ef4444'] : ['#e5e7eb'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush