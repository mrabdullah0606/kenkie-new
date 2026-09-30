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
            </div>

            <!-- Right Column: Category, Pricing, Stock & Status -->
            <div class="col-xxl-4 col-xl-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Category & Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="category_id">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="price">Price ($ USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control @error('price') is-invalid @enderror" id="price" type="number" name="price" min="0.01" step="0.01" value="{{ old('price', $product->price) }}" placeholder="0.00" required>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="stock">Stock Inventory <span class="text-danger">*</span></label>
                            <input class="form-control @error('stock') is-invalid @enderror" id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="unit">Unit Measure <span class="text-danger">*</span></label>
                            <input class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $product->unit ?? 'each') }}" placeholder="e.g. each, 500 g, 1 set" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visibility & Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Publish & Promotion Status</h5>
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

                <!-- Save Action Buttons -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $product->exists ? 'Update Product' : 'Create Product' }}
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Cancel</a>
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
});
</script>
@endpush