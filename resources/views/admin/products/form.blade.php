@extends('admin.layouts.app')

@section('title', $product->exists ? 'Edit Product' : 'Add New Product')

@push('styles')
<!-- Quill Rich Text Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        border-color: #dee2e6;
        background: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-color: #dee2e6;
        min-height: 180px;
        font-family: inherit;
        font-size: 14px;
    }
    .preview-box {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        overflow: hidden;
        position: relative;
    }
    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .card-header-toggle {
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s ease;
    }
    .card-header-toggle:hover {
        background-color: #f8fafc !important;
    }
    .chevron-rotate {
        transition: transform 0.25s ease;
        display: inline-block;
    }
    [aria-expanded="false"] .chevron-rotate {
        transform: rotate(-90deg);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">{{ $product->exists ? 'Edit Product: ' . $product->name : 'Add New Product' }}</h3>
            <p class="text-muted mb-0">Fill in the product details, description, and images.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Products
            </a>
            <button type="submit" form="productForm" class="btn btn-primary d-inline-flex align-items-center gap-2 ms-2">
                <i class="fa-solid fa-floppy-disk"></i> {{ $product->exists ? 'Save Changes' : 'Create Product' }}
            </button>
        </div>
    </div>

    <form method="POST" id="productForm" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <!-- Left Column: Primary Information -->
            <div class="col-xxl-8 col-xl-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Product Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Organic Cotton Gauze Sheet" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="slug">URL Slug <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted small">/product/</span>
                                    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="organic-cotton-sheet" required>
                                </div>
                                <small class="text-muted">Auto-generated from name. You can also customize it.</small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="sku">SKU Code <span class="text-danger">*</span></label>
                                <input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="e.g. SHT-ORG-001" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rich Text Editor for Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Product Description</label>
                                <div id="quillEditor">{!! old('description', $product->description) !!}</div>
                                <textarea class="d-none @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media & Image Upload -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Product Images</h5>
                    </div>
                    <div class="card-body">
                        <!-- Primary Featured Image -->
                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label fw-semibold" for="image_file">Upload Primary Featured Image</label>
                            <input class="form-control @error('image_file') is-invalid @enderror" type="file" id="image_file" name="image_file" accept="image/*">
                            <small class="text-muted d-block mt-1">Accepts PNG, JPG, JPEG, WEBP (Max 10MB). Image is automatically stored.</small>
                            @error('image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Hidden field to signal primary image removal -->
                            <input type="hidden" name="remove_primary_image" id="remove_primary_image" value="0">

                            <!-- Primary Preview -->
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <div class="preview-box" id="primaryImagePreviewBox">
                                    @if ($product->image)
                                        <img src="{{ asset($product->image) }}" id="primaryImagePreview" alt="Current Image" onerror="this.src='{{ asset('assets/images/furniture/1.png') }}'">
                                        <i class="fa-solid fa-image text-muted fs-4 d-none" id="primaryImagePlaceholder"></i>
                                    @else
                                        <img src="" id="primaryImagePreview" class="d-none" alt="Preview">
                                        <i class="fa-solid fa-image text-muted fs-4" id="primaryImagePlaceholder"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block small">Primary Thumbnail</span>
                                    <small class="text-muted d-block" id="primaryImageStatus">{{ $product->image ? 'Current featured image loaded' : 'No image selected yet' }}</small>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-2 {{ $product->image ? '' : 'd-none' }}" id="removePrimaryImageBtn">
                                        <i class="fa-solid fa-trash-can me-1"></i> Remove Image
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Multi-Image Upload -->
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold mb-0" for="gallery_files">Upload Gallery Images (Multiple)</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="clearNewGalleryBtn">
                                    <i class="fa-solid fa-xmark me-1"></i> Clear Selected Files
                                </button>
                            </div>
                            <input class="form-control @error('gallery_files') is-invalid @enderror" type="file" id="gallery_files" name="gallery_files[]" multiple accept="image/*">
                            <small class="text-muted d-block mt-1">Select one or multiple images for the product photo gallery. Click the red <strong>&times;</strong> on any image to remove it.</small>
                            @error('gallery_files')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Gallery Previews Grid -->
                            <div class="d-flex flex-wrap gap-2 mt-3" id="galleryPreviewContainer">
                                @if (is_array($product->images) && count($product->images) > 0)
                                    @foreach ($product->images as $index => $img)
                                        <div class="preview-box position-relative existing-gallery-item">
                                            <input type="hidden" name="keep_gallery_images[]" value="{{ $img }}">
                                            <img src="{{ asset($img) }}" alt="Gallery Image" onerror="this.src='{{ asset('assets/images/furniture/1.png') }}'">
                                            <button type="button" class="btn btn-danger btn-sm remove-existing-gallery-btn position-absolute top-0 end-0 m-1 p-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px;" title="Remove this gallery image">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Size Chart Card -->
                <div class="card mb-4 mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-ruler-combined text-primary me-2"></i>Size Chart</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold" for="size_chart_file">Upload Size Chart Guide (Optional)</label>
                        <input class="form-control @error('size_chart_file') is-invalid @enderror" type="file" id="size_chart_file" name="size_chart_file" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload size reference chart or diagram (PNG, JPG, WEBP, Max 10MB).</small>
                        @error('size_chart_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <input type="hidden" name="remove_size_chart" id="remove_size_chart" value="0">
                        @if ($product->size_chart)
                            <div class="d-flex align-items-center gap-3 mt-3" id="sizeChartCurrentBox">
                                <img src="{{ asset($product->size_chart) }}" class="rounded border" style="width: 90px; height: 90px; object-fit: contain;" alt="Size Chart">
                                <div>
                                    <span class="fw-semibold text-dark d-block small">Current Size Chart Attached</span>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-1" id="removeSizeChartBtn">
                                        <i class="fa-solid fa-trash-can me-1"></i> Remove Size Chart
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Category, Pricing, Stock & Status -->
            <div class="col-xxl-4 col-xl-5">
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-tags text-primary me-2"></i>Category & Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="category_id">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $cat)
                                    @if ($cat->children && $cat->children->isNotEmpty())
                                        <optgroup label="{{ $cat->name }}">
                                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                                {{ $cat->name }} (Main Category)
                                            </option>
                                            @foreach ($cat->children as $child)
                                                <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Regular Price (Original / Strike-through) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="regular_price">Regular / Compare Price ($ USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control @error('regular_price') is-invalid @enderror" id="regular_price" type="number" name="regular_price" min="0.01" step="0.01" value="{{ old('regular_price', $product->regular_price) }}" placeholder="e.g. 59.99">
                            </div>
                            <small class="text-muted">Original list price before discount (shown struck through).</small>
                            @error('regular_price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sale Price (Active Selling Price) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="price">Sale / Selling Price ($ USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control @error('price') is-invalid @enderror" id="price" type="number" name="price" min="0.01" step="0.01" value="{{ old('price', $product->price) }}" placeholder="e.g. 44.99" required>
                            </div>
                            <div id="discountBadgeContainer" class="mt-2 {{ ($product->regular_price && $product->price && $product->regular_price > $product->price) ? '' : 'd-none' }}">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" id="discountBadgeText">
                                    <i class="fa-solid fa-tag me-1"></i>
                                    Discount: {{ $product->discount_percentage }}% OFF
                                </span>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cost Price (Internal Margin) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="cost_price">Cost Price ($ USD - Private)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control @error('cost_price') is-invalid @enderror" id="cost_price" type="number" name="cost_price" min="0.01" step="0.01" value="{{ old('cost_price', $product->cost_price) }}" placeholder="e.g. 20.00">
                            </div>
                            <small class="text-muted">For internal profit calculation only (never displayed to customers).</small>
                            @error('cost_price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Inventory -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold mb-0" for="stock">Stock Inventory <span class="text-danger">*</span></label>
                                @if ($product->exists && $product->stock <= 5)
                                    <span class="badge bg-danger text-white small"><i class="fa-solid fa-triangle-exclamation me-1"></i> Low Stock ({{ $product->stock }})</span>
                                @endif
                            </div>
                            <input class="form-control @error('stock') is-invalid @enderror" id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
                            <small class="text-muted">Items with 5 or fewer in stock will trigger admin low-stock alerts.</small>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="unit">Unit Measure <span class="text-danger">*</span></label>
                            <input class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $product->unit ?? 'each') }}" placeholder="e.g. each, 500 g, 1 set" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visibility & Status -->
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Publish & Promotion Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
                            <label class="form-check-label fw-semibold ms-2" for="is_active">Visible in Store (Active)</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                            <label class="form-check-label fw-semibold ms-2" for="is_featured">Featured Product</label>
                            <small class="text-muted d-block ms-4">Displays in featured sliders and homepage categories.</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_top_deal" name="is_top_deal" value="1" @checked(old('is_top_deal', $product->is_top_deal))>
                            <label class="form-check-label fw-semibold ms-2" for="is_top_deal">Top Deals & Items</label>
                            <small class="text-muted d-block ms-4">Displays in the homepage "Top Deals & Featured Items" grid.</small>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="is_hot_deal" name="is_hot_deal" value="1" @checked(old('is_hot_deal', $product->is_hot_deal))>
                            <label class="form-check-label fw-semibold ms-2 text-danger" for="is_hot_deal">
                                <i class="fa-solid fa-fire me-1 text-danger"></i> Hot Deal (Special Offer Highlight)
                            </label>
                            <small class="text-muted d-block ms-4">Highlights as the large Special Offer card on the homepage.</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="card shadow-sm border p-3 mb-4 bg-light">
                    <button class="btn btn-primary w-100 mb-2 py-2 fw-bold" type="submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $product->exists ? 'Update Product' : 'Create Product' }}
                    </button>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.products.index') }}">Cancel</a>
                </div>
            </div>

            <!-- FULL WIDTH ROW: Product Variations Card (Expandable) -->
            <div class="col-12">
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-3 bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-layer-group fs-5"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">
                                    Product Variations
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2 font-monospace" id="variationsCountBadge">
                                        {{ count(old('variations', $product->variations ?? collect())) }}
                                    </span>
                                </h5>
                                <small class="text-muted">Manage attributes such as Color, Size, Dimensions, Material with custom SKU, Stock, and Pricing across full width.</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" id="addVariationBtn">
                                <i class="fa-solid fa-plus"></i> Add Variation
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" data-bs-target="#variationsCollapse" aria-expanded="true" aria-controls="variationsCollapse" title="Click to collapse / expand variations">
                                <i class="fa-solid fa-chevron-down chevron-rotate"></i>
                            </button>
                        </div>
                    </div>
                    <div class="collapse show" id="variationsCollapse">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0 w-100" id="variationsTable">
                                    <thead class="table-light">
                                        <tr class="small text-nowrap">
                                            <th style="min-width: 170px;">Name / Label</th>
                                            <th style="min-width: 120px;">Color</th>
                                            <th style="min-width: 110px;">Size</th>
                                            <th style="min-width: 120px;">Dimensions</th>
                                            <th style="min-width: 120px;">Material</th>
                                            <th style="min-width: 130px;">SKU</th>
                                            <th style="min-width: 115px;">Reg. Price ($)</th>
                                            <th style="min-width: 115px;">Sale Price ($)</th>
                                            <th style="min-width: 90px;">Stock</th>
                                            <th style="min-width: 80px;" class="text-center">Active</th>
                                            <th style="width: 50px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variationsBody">
                                        @php
                                            $existingVariations = old('variations', $product->variations ?? collect());
                                        @endphp
                                        @forelse ($existingVariations as $index => $variation)
                                            @php
                                                $v = is_array($variation) ? (object) $variation : $variation;
                                            @endphp
                                            <tr class="variation-row">
                                                @if (!empty($v->id))
                                                    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $v->id }}">
                                                @endif
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variations[{{ $index }}][name]" value="{{ $v->name ?? '' }}" placeholder="e.g. Emerald Green / King">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variations[{{ $index }}][color]" value="{{ $v->color ?? '' }}" placeholder="e.g. Green">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variations[{{ $index }}][size]" value="{{ $v->size ?? '' }}" placeholder="e.g. King">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variations[{{ $index }}][dimensions]" value="{{ $v->dimensions ?? '' }}" placeholder="e.g. 76x80 in">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variations[{{ $index }}][material]" value="{{ $v->material ?? '' }}" placeholder="e.g. Egyptian Cotton">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm font-monospace" name="variations[{{ $index }}][sku]" value="{{ $v->sku ?? '' }}" placeholder="SKU-CODE">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="variations[{{ $index }}][regular_price]" value="{{ $v->regular_price ?? '' }}" placeholder="0.00">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="variations[{{ $index }}][sale_price]" value="{{ $v->sale_price ?? '' }}" placeholder="0.00">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control form-control-sm text-center" name="variations[{{ $index }}][stock]" value="{{ $v->stock ?? 0 }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="variations[{{ $index }}][is_active]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="variations[{{ $index }}][is_active]" value="1" @checked($v->is_active ?? true)>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-variation-btn" title="Remove Variation">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="noVariationsRow">
                                                <td colspan="11" class="text-center text-muted py-4 small">
                                                    <i class="fa-solid fa-layer-group fs-3 text-muted d-block mb-2"></i>
                                                    No variations added yet. Click <strong>"Add Variation"</strong> above to configure sizes, colors, or materials.
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

            <!-- FULL WIDTH ROW: Multiple Buyer Offers (Volume & Tiered Discounts) -->
            <div class="col-12">
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-3 bg-warning-subtle text-warning p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-tags fs-5"></i>
                            </span>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="fw-bold mb-0 text-dark">Multiple Buyer Offers & Tiered Discounts</h5>
                                    <span class="badge bg-warning-subtle text-dark border border-warning" id="offersCountBadge">
                                        {{ $product->offers->count() }} {{ Str::plural('Offer', $product->offers->count()) }}
                                    </span>
                                </div>
                                <small class="text-muted">Configure bundle savings such as Buy 2 Get 10% Off or Buy 3+ Get 15% Off with optional start & end dates.</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-semibold d-inline-flex align-items-center gap-1" id="addOfferBtn">
                                <i class="fa-solid fa-plus"></i> Add Multi-Buy Offer
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" data-bs-target="#offersCollapse" aria-expanded="true" aria-controls="offersCollapse" title="Click to collapse / expand Offers section">
                                <i class="fa-solid fa-chevron-down chevron-rotate"></i>
                            </button>
                        </div>
                    </div>
                    <div class="collapse show" id="offersCollapse">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="offersTable">
                                    <thead class="table-light">
                                        <tr class="small text-uppercase text-muted">
                                            <th style="min-width: 200px;">Offer Title / Headline</th>
                                            <th style="min-width: 110px;">Min Qty</th>
                                            <th style="min-width: 130px;">Discount (%)</th>
                                            <th style="min-width: 140px;">Badge Tag</th>
                                            <th style="min-width: 160px;">Starts At</th>
                                            <th style="min-width: 160px;">Ends At</th>
                                            <th style="min-width: 70px;" class="text-center">Active</th>
                                            <th style="min-width: 50px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="offersBody">
                                        @forelse (old('offers', $product->offers) as $index => $offer)
                                            @php
                                                $off = is_array($offer) ? (object) $offer : $offer;
                                            @endphp
                                            <tr class="offer-row">
                                                @if (!empty($off->id))
                                                    <input type="hidden" name="offers[{{ $index }}][id]" value="{{ $off->id }}">
                                                @endif
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="offers[{{ $index }}][title]" value="{{ $off->title ?? '' }}" placeholder="e.g. Buy 2 Save 10%">
                                                </td>
                                                <td>
                                                    <input type="number" min="2" class="form-control form-control-sm text-center" name="offers[{{ $index }}][min_quantity]" value="{{ $off->min_quantity ?? 2 }}">
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" min="1" max="100" class="form-control" name="offers[{{ $index }}][discount_percentage]" value="{{ $off->discount_percentage ?? 10 }}">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="offers[{{ $index }}][badge_label]" value="{{ $off->badge_label ?? '' }}" placeholder="POPULAR">
                                                </td>
                                                <td>
                                                    <input type="datetime-local" class="form-control form-control-sm" name="offers[{{ $index }}][starts_at]" value="{{ !empty($off->starts_at) ? \Carbon\Carbon::parse($off->starts_at)->format('Y-m-d\TH:i') : '' }}">
                                                </td>
                                                <td>
                                                    <input type="datetime-local" class="form-control form-control-sm" name="offers[{{ $index }}][ends_at]" value="{{ !empty($off->ends_at) ? \Carbon\Carbon::parse($off->ends_at)->format('Y-m-d\TH:i') : '' }}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="offers[{{ $index }}][is_active]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="offers[{{ $index }}][is_active]" value="1" @checked($off->is_active ?? true)>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-offer-btn" title="Remove Offer">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="noOffersRow">
                                                <td colspan="8" class="text-center text-muted py-4 small">
                                                    <i class="fa-solid fa-tags fs-3 text-muted d-block mb-2"></i>
                                                    No buyer offers configured yet. Click <strong>"Add Multi-Buy Offer"</strong> above to incentivize bulk orders (e.g. Buy 2 Get 10% Off).
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

            <!-- FULL WIDTH ROW: SEO & Search Engine Optimization Card (Expandable) -->
            <div class="col-12">
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-3 bg-info-subtle text-info p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-magnifying-glass-chart fs-5"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">SEO & Search Engine Optimization</h5>
                                <small class="text-muted">Customize search snippet preview, meta title, description, and keywords for search engines.</small>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" data-bs-toggle="collapse" data-bs-target="#seoCollapse" aria-expanded="true" aria-controls="seoCollapse" title="Click to collapse / expand SEO section">
                                <i class="fa-solid fa-chevron-down chevron-rotate"></i>
                            </button>
                        </div>
                    </div>
                    <div class="collapse show" id="seoCollapse">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="meta_title">Meta Title</label>
                                    <input class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" placeholder="Custom browser title (Defaults to Product Name)">
                                    <small class="text-muted">Recommended: Up to 60 characters for optimal Google search appearance.</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="meta_keywords">Meta Keywords</label>
                                    <input class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" placeholder="e.g. bedding, organic cotton, sheets, luxury home">
                                    <small class="text-muted">Comma-separated search keywords.</small>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="meta_description">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3" placeholder="Brief summary of the product for search engine snippets">{{ old('meta_description', $product->meta_description) }}</textarea>
                                    <small class="text-muted">Recommended: Up to 160 characters describing the product.</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Action Bar -->
            <div class="col-12">
                <div class="card bg-white border shadow-sm p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-muted small">Ready to save? Verify product details and variations above before submitting.</span>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Cancel</a>
                            <button class="btn btn-primary px-4 fw-bold" type="submit">
                                <i class="fa-solid fa-floppy-disk me-2"></i> {{ $product->exists ? 'Update Product' : 'Create Product' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Quill JS CDN -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Quill Rich Text Editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Write formatted product description (headings, bold, lists)...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    const descriptionTextarea = document.getElementById('description');
    const form = document.getElementById('productForm');

    form.addEventListener('submit', function() {
        descriptionTextarea.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
    });

    // 2. Dynamic Slug Generator from Product Name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let isSlugManuallyEdited = {{ $product->exists ? 'true' : 'false' }};

    slugInput.addEventListener('input', function() {
        isSlugManuallyEdited = slugInput.value.trim().length > 0;
    });

    nameInput.addEventListener('input', function() {
        if (!isSlugManuallyEdited) {
            const slug = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });

    // 3. Primary Image Live Preview & Removal
    const imageFileInput = document.getElementById('image_file');
    const primaryPreview = document.getElementById('primaryImagePreview');
    const primaryPlaceholder = document.getElementById('primaryImagePlaceholder');
    const primaryStatus = document.getElementById('primaryImageStatus');
    const removePrimaryImageBtn = document.getElementById('removePrimaryImageBtn');
    const removePrimaryImageInput = document.getElementById('remove_primary_image');

    imageFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                primaryPreview.src = event.target.result;
                primaryPreview.classList.remove('d-none');
                if (primaryPlaceholder) primaryPlaceholder.classList.add('d-none');
                primaryStatus.textContent = 'New file selected: ' + file.name;
                removePrimaryImageInput.value = '0';
                if (removePrimaryImageBtn) removePrimaryImageBtn.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    if (removePrimaryImageBtn) {
        removePrimaryImageBtn.addEventListener('click', function() {
            imageFileInput.value = '';
            removePrimaryImageInput.value = '1';
            primaryPreview.src = '';
            primaryPreview.classList.add('d-none');
            if (primaryPlaceholder) primaryPlaceholder.classList.remove('d-none');
            primaryStatus.textContent = 'Image removed (save form to apply)';
            removePrimaryImageBtn.classList.add('d-none');
        });
    }

    // 4. Gallery Files Live Previews & Image Removal
    const galleryFileInput = document.getElementById('gallery_files');
    const galleryContainer = document.getElementById('galleryPreviewContainer');
    const clearNewGalleryBtn = document.getElementById('clearNewGalleryBtn');

    // Remove existing gallery image items
    galleryContainer.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-existing-gallery-btn');
        if (removeBtn) {
            const item = removeBtn.closest('.existing-gallery-item');
            if (item) {
                item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => item.remove(), 200);
            }
        }
    });

    // Preview new gallery files
    galleryFileInput.addEventListener('change', function(e) {
        const files = e.target.files;
        // Remove only previous "new-preview-item" elements, keep existing saved gallery items
        document.querySelectorAll('.new-gallery-item').forEach(el => el.remove());

        if (files && files.length > 0) {
            if (clearNewGalleryBtn) clearNewGalleryBtn.classList.remove('d-none');
            Array.from(files).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const box = document.createElement('div');
                    box.className = 'preview-box position-relative new-gallery-item border-primary';
                    box.innerHTML = `
                        <img src="${event.target.result}" alt="New Preview">
                        <span class="badge bg-primary position-absolute bottom-0 start-0 m-1 px-1 small" style="font-size: 9px;">New</span>
                    `;
                    galleryContainer.appendChild(box);
                };
                reader.readAsDataURL(file);
            });
        } else {
            if (clearNewGalleryBtn) clearNewGalleryBtn.classList.add('d-none');
        }
    });

    if (clearNewGalleryBtn) {
        clearNewGalleryBtn.addEventListener('click', function() {
            galleryFileInput.value = '';
            document.querySelectorAll('.new-gallery-item').forEach(el => el.remove());
            clearNewGalleryBtn.classList.add('d-none');
        });
    }

    // 5. Size Chart Removal
    const removeSizeChartBtn = document.getElementById('removeSizeChartBtn');
    const removeSizeChartInput = document.getElementById('remove_size_chart');
    const sizeChartBox = document.getElementById('sizeChartCurrentBox');
    if (removeSizeChartBtn) {
        removeSizeChartBtn.addEventListener('click', function() {
            if (confirm('Remove current size chart?')) {
                removeSizeChartInput.value = '1';
                if (sizeChartBox) sizeChartBox.remove();
            }
        });
    }

    // 6. Live Discount % Calculation
    const regularPriceInput = document.getElementById('regular_price');
    const priceInput = document.getElementById('price');
    const discountContainer = document.getElementById('discountBadgeContainer');
    const discountBadgeText = document.getElementById('discountBadgeText');

    function updateDiscountBadge() {
        const reg = parseFloat(regularPriceInput.value);
        const sale = parseFloat(priceInput.value);
        if (!isNaN(reg) && !isNaN(sale) && reg > sale && reg > 0) {
            const pct = Math.round(((reg - sale) / reg) * 100);
            discountBadgeText.innerHTML = `<i class="fa-solid fa-tag me-1"></i> Discount: ${pct}% OFF`;
            discountContainer.classList.remove('d-none');
        } else {
            discountContainer.classList.add('d-none');
        }
    }

    if (regularPriceInput && priceInput) {
        regularPriceInput.addEventListener('input', updateDiscountBadge);
        priceInput.addEventListener('input', updateDiscountBadge);
    }

    // 7. Dynamic Variations Management
    const addVariationBtn = document.getElementById('addVariationBtn');
    const variationsBody = document.getElementById('variationsBody');
    const noVariationsRow = document.getElementById('noVariationsRow');
    let variationIndex = document.querySelectorAll('.variation-row').length + 100;

    if (addVariationBtn && variationsBody) {
        addVariationBtn.addEventListener('click', function() {
            const variationsCollapse = document.getElementById('variationsCollapse');
            if (variationsCollapse && !variationsCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(variationsCollapse);
                bsCollapse.show();
            }

            if (noVariationsRow) noVariationsRow.remove();

            const tr = document.createElement('tr');
            tr.className = 'variation-row';
            tr.innerHTML = `
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][name]" placeholder="e.g. Red / XL">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][color]" placeholder="Color">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][size]" placeholder="Size">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][dimensions]" placeholder="Dimensions">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][material]" placeholder="Material">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="variations[${variationIndex}][sku]" placeholder="SKU">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="variations[${variationIndex}][regular_price]" placeholder="0.00">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="variations[${variationIndex}][sale_price]" placeholder="0.00">
                </td>
                <td>
                    <input type="number" min="0" class="form-control form-control-sm" name="variations[${variationIndex}][stock]" value="0">
                </td>
                <td class="text-center">
                    <input type="hidden" name="variations[${variationIndex}][is_active]" value="0">
                    <input type="checkbox" class="form-check-input" name="variations[${variationIndex}][is_active]" value="1" checked>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-variation-btn" title="Remove Variation">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            variationsBody.appendChild(tr);
            variationIndex++;
        });

        variationsBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-variation-btn');
            if (btn) {
                const row = btn.closest('.variation-row');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    // 8. Dynamic Multi-Buy Offers Management
    const addOfferBtn = document.getElementById('addOfferBtn');
    const offersBody = document.getElementById('offersBody');
    const noOffersRow = document.getElementById('noOffersRow');
    let offerIndex = document.querySelectorAll('.offer-row').length + 100;

    if (addOfferBtn && offersBody) {
        addOfferBtn.addEventListener('click', function() {
            const offersCollapse = document.getElementById('offersCollapse');
            if (offersCollapse && !offersCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(offersCollapse);
                bsCollapse.show();
            }

            if (noOffersRow) noOffersRow.remove();

            const tr = document.createElement('tr');
            tr.className = 'offer-row';
            tr.innerHTML = `
                <td>
                    <input type="text" class="form-control form-control-sm" name="offers[${offerIndex}][title]" placeholder="e.g. Buy 2 Save 10%">
                </td>
                <td>
                    <input type="number" min="2" class="form-control form-control-sm text-center" name="offers[${offerIndex}][min_quantity]" value="2">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" min="1" max="100" class="form-control" name="offers[${offerIndex}][discount_percentage]" value="10">
                        <span class="input-group-text">%</span>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="offers[${offerIndex}][badge_label]" placeholder="POPULAR">
                </td>
                <td>
                    <input type="datetime-local" class="form-control form-control-sm" name="offers[${offerIndex}][starts_at]">
                </td>
                <td>
                    <input type="datetime-local" class="form-control form-control-sm" name="offers[${offerIndex}][ends_at]">
                </td>
                <td class="text-center">
                    <input type="hidden" name="offers[${offerIndex}][is_active]" value="0">
                    <input type="checkbox" class="form-check-input" name="offers[${offerIndex}][is_active]" value="1" checked>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-offer-btn" title="Remove Offer">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            offersBody.appendChild(tr);
            offerIndex++;
        });

        offersBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-offer-btn');
            if (btn) {
                const row = btn.closest('.offer-row');
                if (row) {
                    row.remove();
                }
            }
        });
    }
});
</script>
@endpush