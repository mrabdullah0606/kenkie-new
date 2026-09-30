@extends('admin.layouts.app')

@section('title', $offer->exists ? 'Edit Bank & Wallet Offer' : 'Add New Bank & Wallet Offer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">{{ $offer->exists ? 'Edit Offer: ' . $offer->title : 'Add New Bank & Wallet Offer' }}</h3>
            <p class="text-muted mb-0">Configure the promotion discount, coupon code, and card theme.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Offers
            </a>
        </div>
    </div>

    <form method="POST" action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($offer->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="fw-bold mb-0">Offer Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="title">Offer Title <span class="text-danger">*</span></label>
                                <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $offer->title) }}" placeholder="e.g. GET 10% OFF" required autofocus>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="code">Coupon / Promo Code <span class="text-danger">*</span></label>
                                <input class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $offer->code) }}" placeholder="e.g. KENKIE10" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="subtitle">Subtitle / Discount Condition</label>
                                <input class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $offer->subtitle) }}" placeholder="e.g. When you spend $20">
                                @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="validity">Validity Note</label>
                                <input class="form-control @error('validity') is-invalid @enderror" id="validity" name="validity" value="{{ old('validity', $offer->validity ?? 'Valid for 30 days') }}" placeholder="e.g. Valid for 30 days">
                                @error('validity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="color_theme">Card Color Theme <span class="text-danger">*</span></label>
                                <select class="form-select @error('color_theme') is-invalid @enderror" id="color_theme" name="color_theme" required>
                                    <option value="theme-1" @selected(old('color_theme', $offer->color_theme) === 'theme-1')>Theme 1 (Red / Crimson)</option>
                                    <option value="theme-2" @selected(old('color_theme', $offer->color_theme) === 'theme-2')>Theme 2 (Blue / Indigo)</option>
                                    <option value="theme-3" @selected(old('color_theme', $offer->color_theme) === 'theme-3')>Theme 3 (Yellow / Orange)</option>
                                    <option value="theme-4" @selected(old('color_theme', $offer->color_theme) === 'theme-4')>Theme 4 (Green / Emerald)</option>
                                </select>
                                @error('color_theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="position">Display Position (Order)</label>
                                <input class="form-control @error('position') is-invalid @enderror" id="position" type="number" name="position" value="{{ old('position', $offer->position ?? 0) }}" min="0">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="fw-bold mb-0">Bank Logo / Icon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="bank_image_file">Upload Logo / Icon</label>
                            <input class="form-control @error('bank_image_file') is-invalid @enderror" type="file" id="bank_image_file" name="bank_image_file" accept="image/*">
                            <small class="text-muted d-block mt-1">Accepts PNG, JPG, SVG, WEBP. Max 5MB.</small>
                            @error('bank_image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($offer->bank_image)
                                <div class="mt-3 p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block mb-1">Current Logo</small>
                                    <img src="{{ asset($offer->bank_image) }}" class="img-fluid" style="max-height: 40px;" alt="Current Bank Image">
                                </div>
                            @endif
                        </div>

                        <div class="form-check form-switch mt-4 mb-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $offer->is_active ?? true))>
                            <label class="form-check-label fw-semibold ms-2" for="is_active">Active & Visible in Store</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $offer->exists ? 'Update Offer' : 'Create Offer' }}
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.offers.index') }}">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
