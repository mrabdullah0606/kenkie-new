@extends('admin.layouts.app')

@section('title', $popup->exists ? 'Edit Promotional Pop-up' : 'Add New Promotional Pop-up')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">{{ $popup->exists ? 'Edit Promotional Pop-up' : 'Add New Promotional Pop-up' }}</h3>
            <p class="text-muted mb-0">Configure the popup appearance, promo discount code, call-to-action button, and targeting.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.popups.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Pop-ups
            </a>
            <button type="submit" form="popupForm" class="btn btn-primary d-inline-flex align-items-center gap-2 ms-2">
                <i class="fa-solid fa-floppy-disk"></i> {{ $popup->exists ? 'Save Changes' : 'Create Pop-up' }}
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" id="popupForm" action="{{ $popup->exists ? route('admin.popups.update', $popup) : route('admin.popups.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($popup->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Left Column: Details --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-heading text-primary me-2"></i>Popup Content & Text</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="title" class="form-label fw-semibold">Popup Main Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $popup->title) }}" placeholder="e.g. 🎉 Special Welcome Offer! Get 15% OFF" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="subtitle" class="form-label fw-semibold">Subtitle / Promo Tagline</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $popup->subtitle) }}" placeholder="e.g. Limited time offer for all new & returning shoppers">
                                @error('subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="content" class="form-label fw-semibold">Description / Message</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" placeholder="e.g. Subscribe to our updates and use this coupon code at checkout to enjoy instant savings on your entire basket!">{{ old('content', $popup->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="discount_code" class="form-label fw-semibold">Discount / Promo Code</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-tag text-success"></i></span>
                                    <input type="text" class="form-control font-monospace text-uppercase @error('discount_code') is-invalid @enderror" id="discount_code" name="discount_code" value="{{ old('discount_code', $popup->discount_code) }}" placeholder="e.g. KENKIE15">
                                </div>
                                <small class="text-muted">Customers will have a 1-click "Copy Code" button in the popup.</small>
                                @error('discount_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="delay_seconds" class="form-label fw-semibold">Display Delay (Seconds) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-stopwatch"></i></span>
                                    <input type="number" class="form-control @error('delay_seconds') is-invalid @enderror" id="delay_seconds" name="delay_seconds" min="0" max="120" value="{{ old('delay_seconds', $popup->delay_seconds ?? 3) }}" required>
                                </div>
                                <small class="text-muted">Seconds to wait after page loads before showing popup.</small>
                                @error('delay_seconds')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-arrow-pointer text-primary me-2"></i>Call to Action (Button)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="button_text" class="form-label fw-semibold">Button Text <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $popup->button_text ?? 'Shop Now') }}" required>
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="button_url" class="form-label fw-semibold">Button Link URL <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('button_url') is-invalid @enderror" id="button_url" name="button_url" value="{{ old('button_url', $popup->button_url ?? '/shop-category') }}" required>
                                @error('button_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Targeting & Image --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders text-primary me-2"></i>Targeting & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="target_page" class="form-label fw-semibold">Display On Pages</label>
                            <select class="form-select @error('target_page') is-invalid @enderror" id="target_page" name="target_page">
                                <option value="all" {{ old('target_page', $popup->target_page) === 'all' ? 'selected' : '' }}>All Storefront Pages</option>
                                <option value="home" {{ old('target_page', $popup->target_page) === 'home' ? 'selected' : '' }}>Home Page Only</option>
                                <option value="shop" {{ old('target_page', $popup->target_page) === 'shop' ? 'selected' : '' }}>Shop / Categories Page Only</option>
                                <option value="product" {{ old('target_page', $popup->target_page) === 'product' ? 'selected' : '' }}>Product Pages Only</option>
                            </select>
                            @error('target_page')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $popup->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold ms-2" for="is_active">Enable Pop-up (Active)</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-image text-primary me-2"></i>Banner Image</h5>
                    </div>
                    <div class="card-body">
                        @if($popup->image)
                            <div class="mb-3 text-center">
                                <img src="{{ asset($popup->image) }}" alt="Preview" class="img-fluid rounded border mb-2 shadow-sm" style="max-height: 160px; object-fit: cover;">
                                <div class="form-check text-start mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                    <label class="form-check-label text-danger small fw-semibold" for="remove_image">Remove current image</label>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="image_file" class="form-label fw-semibold">Upload Image</label>
                            <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*">
                            <small class="text-muted d-block mt-1">Recommended size: 600x400px (JPG, PNG, WebP).</small>
                            @error('image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $popup->exists ? 'Save Changes' : 'Create Pop-up' }}
                    </button>
                    <a href="{{ route('admin.popups.index') }}" class="btn btn-outline-secondary w-50 py-2">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
