@extends('admin.layouts.app')

@section('title', 'Bank & Wallet Offers Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Bank & Wallet Offers</h3>
            <p class="text-muted mb-0">Manage discounts, bank coupon promotions, and payment wallet deals.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.offers.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Offer
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse ($offers as $offer)
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            @if ($offer->bank_image)
                                <img src="{{ asset($offer->bank_image) }}" class="img-fluid" style="height: 24px; max-width: 80px; object-fit: contain;" alt="Bank Logo">
                            @else
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-building-columns me-1"></i> Bank Offer</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $offer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $offer->is_active ? 'Active' : 'Hidden' }}
                            </span>
                            <span class="badge bg-light text-muted border">
                                Pos #{{ $offer->position }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold mb-1 {{ $offer->color_theme === 'theme-1' ? 'text-danger' : ($offer->color_theme === 'theme-2' ? 'text-primary' : 'text-warning') }}">
                            {{ $offer->title }}
                        </h4>
                        <p class="text-muted mb-1">{{ $offer->subtitle ?: 'Special promo discount' }}</p>
                        <small class="text-secondary d-block mb-3"><i class="fa-regular fa-clock me-1"></i> {{ $offer->validity ?: 'Valid for 30 days' }}</small>

                        <div class="p-2.5 rounded border d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">PROMO CODE</small>
                                <span class="fw-bold font-monospace fs-6 text-dark">{{ $offer->code }}</span>
                            </div>
                            <span class="badge {{ $offer->color_theme === 'theme-1' ? 'bg-danger' : ($offer->color_theme === 'theme-2' ? 'bg-primary' : ($offer->color_theme === 'theme-3' ? 'bg-warning text-dark' : 'bg-success')) }} px-3 py-2">
                                {{ strtoupper($offer->color_theme) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">Updated {{ $offer->updated_at->diffForHumans() }}</small>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-sm btn-outline-primary" title="Edit Offer">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Offer">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card p-5 text-center">
                    <i class="fa-solid fa-tags text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold">No Bank & Wallet Offers Yet</h5>
                    <p class="text-muted">Create your first offer so customers can use discounts and coupons on the storefront.</p>
                    <div class="mt-2">
                        <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-1"></i> Add Offer
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
