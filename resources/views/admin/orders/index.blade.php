@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Orders Management</h3>
            <p class="text-muted mb-0">Track, update, and manage customer orders and deliveries.</p>
        </div>
    </div>

    <!-- Status Filters Bar -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap gap-1" role="group">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-primary' : 'btn-light border' }}">
                        All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $currentStatus === 'pending' ? 'btn-warning text-dark' : 'btn-light border' }}">
                        Pending <span class="badge bg-warning text-dark ms-1">{{ $statusCounts['pending'] }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm {{ $currentStatus === 'processing' ? 'btn-info text-white' : 'btn-light border' }}">
                        Processing <span class="badge bg-info ms-1">{{ $statusCounts['processing'] }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="btn btn-sm {{ $currentStatus === 'shipped' ? 'btn-primary' : 'btn-light border' }}">
                        Shipped <span class="badge bg-primary ms-1">{{ $statusCounts['shipped'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'in_transit']) }}" class="btn btn-sm {{ $currentStatus === 'in_transit' ? 'btn-secondary text-white' : 'btn-light border' }}">
                        In Transit <span class="badge bg-secondary ms-1">{{ $statusCounts['in_transit'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ $currentStatus === 'delivered' ? 'btn-success' : 'btn-light border' }}">
                        Delivered <span class="badge bg-success ms-1">{{ $statusCounts['delivered'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $currentStatus === 'completed' ? 'btn-success' : 'btn-light border' }}">
                        Completed <span class="badge bg-success ms-1">{{ $statusCounts['completed'] }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'returned']) }}" class="btn btn-sm {{ $currentStatus === 'returned' ? 'btn-dark' : 'btn-light border' }}">
                        Returned <span class="badge bg-dark ms-1">{{ $statusCounts['returned'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $currentStatus === 'cancelled' ? 'btn-danger' : 'btn-light border' }}">
                        Cancelled <span class="badge bg-danger ms-1">{{ $statusCounts['cancelled'] }}</span>
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
                    @if ($currentStatus)
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif
                    <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search customer, order #, courier..." style="width: 240px;">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    @if ($search || $currentStatus)
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Order Records ({{ $orders->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Status & Tracking</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $order->formatted_order_id }}
                                    </a>
                                    <small class="d-block text-muted font-monospace" style="font-size: 11px;">#{{ substr($order->uuid, 0, 8) }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $order->items->sum('quantity') }} items
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format((float) $order->total, 2) }}</span>
                                </td>
                                <td>
                                    @if ($order->payment_method === 'stripe')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="fa-brands fa-stripe me-1"></i> Stripe
                                            @if ($order->payment_status === 'paid')
                                                <i class="fa-solid fa-check text-success ms-1"></i>
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark border">COD</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->status === 'completed')
                                        <span class="badge bg-success px-2 py-1">Completed</span>
                                    @elseif ($order->status === 'delivered')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Delivered</span>
                                    @elseif ($order->status === 'shipped')
                                        <span class="badge bg-primary px-2 py-1">Shipped</span>
                                    @elseif ($order->status === 'in_transit')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">In Transit</span>
                                    @elseif ($order->status === 'processing')
                                        <span class="badge bg-info px-2 py-1">Processing</span>
                                    @elseif ($order->status === 'pending')
                                        <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                                    @elseif ($order->status === 'returned')
                                        <span class="badge bg-dark px-2 py-1">Returned</span>
                                    @elseif ($order->status === 'cancelled')
                                        <span class="badge bg-danger px-2 py-1">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status) }}</span>
                                    @endif

                                    @if ($order->tracking_number)
                                        <small class="d-block text-muted mt-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-truck me-1"></i>{{ $order->courier_name ?: 'Courier' }}: {{ $order->tracking_number }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $order->created_at?->format('M d, Y · h:i A') }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View Order Details">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Order">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-box-open fs-2 text-muted mb-2 d-block"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($orders->hasPages())
            <div class="card-footer bg-transparent border-top py-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
