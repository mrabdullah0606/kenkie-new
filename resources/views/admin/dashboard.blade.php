@extends('admin.layouts.app')

@section('title', 'Admin dashboard')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Admin dashboard</h1>
            <p class="text-muted mb-0">Store overview</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Add product</a>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4"><div class="border rounded p-3"><div class="text-muted">Products</div><strong class="h3">{{ $productCount }}</strong></div></div>
        <div class="col-md-4"><div class="border rounded p-3"><div class="text-muted">Categories</div><strong class="h3">{{ $categoryCount }}</strong></div></div>
        <div class="col-md-4"><div class="border rounded p-3"><div class="text-muted">Orders</div><strong class="h3">{{ $orderCount }}</strong></div></div>
    </div>

    <h2 class="h5 mb-3">Recent orders</h2>
    @forelse ($latestOrders as $order)
        <div class="d-flex justify-content-between border-bottom py-2">
            <span>{{ $order->customer_name }} <span class="text-muted">{{ $order->uuid }}</span></span>
            <span>{{ ucfirst($order->status) }} · ${{ number_format($order->total, 2) }}</span>
        </div>
    @empty
        <p class="text-muted">No orders yet.</p>
    @endforelse
@endsection