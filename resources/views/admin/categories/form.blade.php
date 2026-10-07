@extends('admin.layouts.app')

@section('title', $category->exists ? 'Edit Category' : 'Add New Category')

@push('styles')
<style>
    .preview-box {
        width: 90px;
        height: 90px;
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
    .preview-box-wide {
        width: 120px;
        height: 90px;
        border-radius: 8px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        overflow: hidden;
        position: relative;
    }
    .preview-box-wide img {
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
            <h3 class="fw-bold mb-1">{{ $category->exists ? 'Edit Category: ' . $category->name : 'Add New Category' }}</h3>
            <p class="text-muted mb-0">Set up category name, icon, size chart guide, and display settings.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please check form errors:</h6>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" id="categoryForm" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Left Column: Category Info & Images --}}
            <div class="col-lg-8">
                {{-- Basic Information --}}
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-tag text-primary me-2"></i>Category Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="parent_id">Parent Category</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                    <option value="">None (This is a Top-Level Main Category)</option>
                                    @foreach ($parentCategories ?? [] as $parent)
                                        <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave empty for main categories or select a parent to create a sub-category.</small>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="name">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="e.g. Garden & Patio" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="slug">URL Slug <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted small">/shop-category?category=</span>
                                    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="garden-patio" required>
                                </div>
                                <small class="text-muted">Auto-generated from name.</small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Category Icon / Image --}}
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-image text-primary me-2"></i>Category Icon / Thumbnail</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold" for="image_file">Upload Category Icon / Image</label>
                        <input class="form-control @error('image_file') is-invalid @enderror" type="file" id="image_file" name="image_file" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload PNG, JPG, or SVG image (Max 10MB).</small>
                        @error('image_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="d-flex align-items-center gap-3 mt-3 p-2 bg-light rounded border">
                            <div class="preview-box" id="categoryImagePreviewBox">
                                @if ($category->image)
                                    <img src="{{ asset($category->image) }}" id="categoryImagePreview" alt="Current Image">
                                @else
                                    <img src="" id="categoryImagePreview" class="d-none" alt="Preview">
                                    <i class="fa-solid fa-tags text-muted fs-4" id="categoryImagePlaceholder"></i>
                                @endif
                            </div>
                            <div>
                                <span class="fw-semibold text-dark d-block small">Category Icon Preview</span>
                                <small class="text-muted" id="categoryImageStatus">{{ $category->image ? 'Current icon loaded' : 'No image uploaded yet' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Size Chart Guide Card --}}
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-ruler-combined text-primary me-2"></i>Default Category Size Chart / Guide</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold" for="size_chart_file">Upload Category Size Chart Image (Optional)</label>
                        <input class="form-control @error('size_chart_file') is-invalid @enderror" type="file" id="size_chart_file" name="size_chart_file" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload a default size chart for all products in this category (overridable per product).</small>
                        @error('size_chart_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="d-flex align-items-center gap-3 mt-3 p-2 bg-light rounded border">
                            <div class="preview-box-wide" id="sizeChartPreviewBox">
                                @if ($category->size_chart)
                                    <img src="{{ asset($category->size_chart) }}" id="sizeChartPreview" alt="Size Chart">
                                @else
                                    <img src="" id="sizeChartPreview" class="d-none" alt="Preview">
                                    <i class="fa-solid fa-ruler-combined text-muted fs-4" id="sizeChartPlaceholder"></i>
                                @endif
                            </div>
                            <div>
                                <span class="fw-semibold text-dark d-block small">Size Chart Preview</span>
                                <small class="text-muted" id="sizeChartStatus">{{ $category->size_chart ? 'Current size chart active' : 'No size chart uploaded yet' }}</small>
                                @if($category->size_chart)
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="remove_size_chart" id="remove_size_chart" value="1">
                                        <label class="form-check-label text-danger small fw-semibold" for="remove_size_chart">
                                            <i class="fa-solid fa-trash-can me-1"></i> Remove Size Chart
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Display & Status --}}
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm border">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders text-primary me-2"></i>Display Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="position">Sort Order Position <span class="text-danger">*</span></label>
                            <input class="form-control @error('position') is-invalid @enderror" id="position" type="number" name="position" min="0" value="{{ old('position', $category->position ?? 1) }}" required>
                            <small class="text-muted">Placement order in header & navigation menus (1 = first).</small>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="pt-3 border-top">
                            <label class="form-label fw-semibold d-block mb-2">Visibility Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
                                <label class="form-check-label fw-semibold ms-2" for="is_active">Visible in Store (Active)</label>
                            </div>
                            <small class="text-muted d-block mt-1">When disabled, this category and its menu links will be hidden from the storefront.</small>
                        </div>

                        <div class="d-grid gap-2 mt-4 pt-3 border-top">
                            <button class="btn btn-primary fw-bold py-2 d-inline-flex align-items-center justify-content-center gap-2" type="submit">
                                <i class="fa-solid fa-floppy-disk"></i> {{ $category->exists ? 'Save Changes' : 'Create Category' }}
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Slug Generation
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let isSlugManuallyEdited = {{ $category->exists ? 'true' : 'false' }};

    if (slugInput) {
        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = slugInput.value.trim().length > 0;
        });
    }

    if (nameInput && slugInput) {
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
    }

    // Category Icon Live Preview
    const imageFileInput = document.getElementById('image_file');
    const categoryPreview = document.getElementById('categoryImagePreview');
    const categoryPlaceholder = document.getElementById('categoryImagePlaceholder');
    const categoryStatus = document.getElementById('categoryImageStatus');

    if (imageFileInput) {
        imageFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    categoryPreview.src = event.target.result;
                    categoryPreview.classList.remove('d-none');
                    if (categoryPlaceholder) categoryPlaceholder.classList.add('d-none');
                    if (categoryStatus) categoryStatus.textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Size Chart Live Preview
    const sizeChartFileInput = document.getElementById('size_chart_file');
    const sizeChartPreview = document.getElementById('sizeChartPreview');
    const sizeChartPlaceholder = document.getElementById('sizeChartPlaceholder');
    const sizeChartStatus = document.getElementById('sizeChartStatus');

    if (sizeChartFileInput) {
        sizeChartFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    sizeChartPreview.src = event.target.result;
                    sizeChartPreview.classList.remove('d-none');
                    if (sizeChartPlaceholder) sizeChartPlaceholder.classList.add('d-none');
                    if (sizeChartStatus) sizeChartStatus.textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
