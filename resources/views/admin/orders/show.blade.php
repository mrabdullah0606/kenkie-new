@extends('admin.layouts.app')

@section('title', 'Order #' . substr($order->uuid, 0, 8))

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Order #{{ substr($order->uuid, 0, 8) }}</h3>
            <p class="text-muted mb-0">Placed on {{ $order->created_at?->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ route('orders.confirmation', $order->uuid) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 ms-2">
                <i class="fa-solid fa-receipt"></i> Customer Invoice
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Order Items & Pricing Breakdown -->
        <div class="col-xxl-8 col-xl-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Purchased Items ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: <code>{{ $item->sku }}</code></small>
                                        </td>
                                        <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-1">× {{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ${{ number_format((float) $item->line_total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="row justify-content-end">
                        <div class="col-md-6 col-lg-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-semibold">${{ number_format((float) $order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping Fee:</span>
                                <span class="fw-semibold">{{ (float) $order->shipping_fee > 0 ? '$' . number_format((float) $order->shipping_fee, 2) : 'FREE' }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold fs-5 text-dark">Total:</span>
                                <span class="fw-bold fs-5 text-success">${{ number_format((float) $order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Status Update & Customer Information -->
        <div class="col-xxl-4 col-xl-5">
            <!-- Status Update Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PATCH')
                        <label class="form-label fw-semibold" for="status">Fulfillment Status</label>
                        <div class="input-group">
                            <select name="status" id="status" class="form-select">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="completed" @selected($order->status === 'completed' || $order->status === 'paid')>Completed</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer & Delivery Address -->
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Customer & Shipping Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Customer Name</span>
                        <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Contact Details</span>
                        <div><i class="fa-solid fa-envelope me-2 text-muted"></i>{{ $order->email }}</div>
                        <div class="mt-1"><i class="fa-solid fa-phone me-2 text-muted"></i>{{ $order->phone }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Shipping Address</span>
                        <p class="mb-0 text-dark">
                            {{ $order->address_line }}<br>
                            {{ $order->city }}@if ($order->region), {{ $order->region }}@endif<br>
                            {{ $order->postal_code }}, {{ $order->country }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Payment Method & Status</span>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if ($order->payment_method === 'stripe')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold">
                                    <i class="fa-brands fa-stripe me-1"></i> Stripe Card
                                </span>
                            @else
                                <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                                    Cash On Delivery (COD)
                                </span>
                            @endif

                            @if ($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold">
                                    <i class="fa-solid fa-check me-1"></i> Paid
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-semibold">
                                    <i class="fa-regular fa-clock me-1"></i> Pending Payment
                                </span>
                            @endif
                        </div>
                        @if ($order->stripe_payment_intent_id)
                            <small class="text-muted d-block mt-2">Stripe Intent: <code>{{ $order->stripe_payment_intent_id }}</code></small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
