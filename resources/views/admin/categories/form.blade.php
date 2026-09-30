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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">{{ $category->exists ? 'Edit Category: ' . $category->name : 'Add New Category' }}</h3>
            <p class="text-muted mb-0">Set up category name, icon, and display settings.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    <form method="POST" id="categoryForm" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Category Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
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

                            <!-- Category Image Uploader -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="image_file">Category Icon / Image</label>
                                <input class="form-control @error('image_file') is-invalid @enderror" type="file" id="image_file" name="image_file" accept="image/*">
                                <small class="text-muted d-block mt-1">Upload PNG, JPG, or SVG image (Max 10MB). Image is stored automatically.</small>
                                @error('image_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Preview Box -->
                                <div class="d-flex align-items-center gap-3 mt-3">
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

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="position">Sort Order Position <span class="text-danger">*</span></label>
                                <input class="form-control @error('position') is-invalid @enderror" id="position" type="number" name="position" min="0" value="{{ old('position', $category->position ?? 1) }}" required>
                                <small class="text-muted">Determines placement in store header & menus (1 = first).</small>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
                                    <label class="form-check-label fw-semibold ms-2" for="is_active">Visible in Store (Active)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i> {{ $category->exists ? 'Save Changes' : 'Create Category' }}
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
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

    // Image Live Preview
    const imageFileInput = document.getElementById('image_file');
    const categoryPreview = document.getElementById('categoryImagePreview');
    const categoryPlaceholder = document.getElementById('categoryImagePlaceholder');
    const categoryStatus = document.getElementById('categoryImageStatus');

    imageFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                categoryPreview.src = event.target.result;
                categoryPreview.classList.remove('d-none');
                if (categoryPlaceholder) categoryPlaceholder.classList.add('d-none');
                categoryStatus.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
