@extends('admin.layouts.app')

@section('title', 'Order ' . $order->formatted_order_id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold mb-0">Order {{ $order->formatted_order_id }}</h3>
                <span class="badge bg-secondary-subtle text-secondary border font-monospace small">{{ substr($order->uuid, 0, 8) }}</span>
            </div>
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

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

            <!-- Private Internal Admin Notes (Sticky Yellow Style) -->
            <div class="card border-warning mb-4" style="background-color: #fffdf5;">
                <div class="card-header bg-warning bg-opacity-10 border-bottom border-warning d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-note-sticky text-warning me-2"></i> Private Admin Internal Notes
                    </h5>
                    <span class="badge bg-warning text-dark small">Staff Only · Not visible to customer</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-notes', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Write internal processing notes, customer special requests, warehouse packing instructions, or follow-ups...">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                            <small class="text-muted">Use this to track order history, customer communication logs, or special packing requirements.</small>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Internal Note
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Status Update, Tracking & Customer Information -->
        <div class="col-xxl-4 col-xl-5">
            <!-- Fulfillment Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Fulfillment Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="status">Order Status</label>
                            <select name="status" id="status" class="form-select">
                                @foreach ($allowedStatuses as $st)
                                    <option value="{{ $st }}" @selected($order->status === $st)>
                                        {{ str_replace('_', ' ', ucfirst($st)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> Update Status
                        </button>
                    </form>

                    <div class="mt-3 pt-3 border-top small text-muted">
                        @if ($order->shipped_at)
                            <div class="d-flex justify-content-between mb-1">
                                <span>Shipped On:</span>
                                <strong>{{ $order->shipped_at->format('M d, Y h:i A') }}</strong>
                            </div>
                        @endif
                        @if ($order->delivered_at)
                            <div class="d-flex justify-content-between">
                                <span>Delivered On:</span>
                                <strong>{{ $order->delivered_at->format('M d, Y h:i A') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Courier & Shipment Tracking Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-truck-fast text-primary me-2"></i>Courier & Tracking Info</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-tracking', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" for="courier_name">Courier / Carrier Name</label>
                            <input type="text" name="courier_name" id="courier_name" class="form-control form-control-sm" value="{{ old('courier_name', $order->courier_name) }}" placeholder="e.g. FedEx, DHL, Royal Mail, TCS">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold" for="tracking_number">Tracking Number / AWB</label>
                            <input type="text" name="tracking_number" id="tracking_number" class="form-control form-control-sm font-monospace" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. TRK-987654321">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" for="tracking_url">Tracking URL (Optional)</label>
                            <input type="url" name="tracking_url" id="tracking_url" class="form-control form-control-sm" value="{{ old('tracking_url', $order->tracking_url) }}" placeholder="https://courier.com/track?no=...">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Tracking
                            </button>
                            @if ($order->tracking_url)
                                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-primary btn-sm" title="Track Live Shipment">
                                    <i class="fa-solid fa-up-right-from-square"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer & Shipping Details Card (With Edit Modal) -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Customer & Shipping</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Address
                    </button>
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
                        <span class="text-muted small d-block mb-1">Delivery Address</span>
                        <p class="mb-0 text-dark">
                            {{ $order->address_line }}<br>
                            {{ $order->city }}@if ($order->region), {{ $order->region }}@endif<br>
                            {{ $order->postal_code }}, {{ $order->country }}
                        </p>
                    </div>

                    <div class="mb-0">
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

<!-- Edit Customer Shipping Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.orders.update-shipping-address', $order) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editAddressModalLabel">
                        <i class="fa-solid fa-location-dot me-2 text-primary"></i> Edit Customer & Shipping Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Customer Full Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-sm" value="{{ old('customer_name', $order->customer_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $order->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $order->phone) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Street Address</label>
                            <input type="text" name="address_line" class="form-control form-control-sm" value="{{ old('address_line', $order->address_line) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">City</label>
                            <input type="text" name="city" class="form-control form-control-sm" value="{{ old('city', $order->city) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Region / State</label>
                            <input type="text" name="region" class="form-control form-control-sm" value="{{ old('region', $order->region) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control form-control-sm" value="{{ old('postal_code', $order->postal_code) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Country</label>
                            <input type="text" name="country" class="form-control form-control-sm" value="{{ old('country', $order->country) }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
