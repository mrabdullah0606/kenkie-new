@extends('admin.layouts.app')

@section('title', 'Homepage Banner & Content Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="fw-bold mb-1">Homepage Content & Banners</h3>
            <p class="text-muted mb-0">Customize your storefront hero slider, headline texts, action buttons, and promo cards.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-eye"></i> View Live Storefront
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

    <!-- SECTION 1: HERO BANNER SETTINGS -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-image text-primary fs-5"></i>
                <h5 class="fw-bold mb-0">Main Hero Banner & Headlines</h5>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.home.hero') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="hero_badge">Badge / Tagline</label>
                                <input class="form-control @error('hero_badge') is-invalid @enderror" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $settings->hero_badge) }}" placeholder="e.g. Weekend Special Offer">
                                @error('hero_badge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="hero_button_text">Button Text</label>
                                <input class="form-control @error('hero_button_text') is-invalid @enderror" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $settings->hero_button_text) }}" placeholder="e.g. Shop Collection">
                                @error('hero_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="hero_title">Main Heading Title <span class="text-danger">*</span></label>
                                <input class="form-control @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}" placeholder="e.g. Premium Quality Home & Garden Collection" required>
                                @error('hero_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="hero_subtitle">Subtitle / Highlight Text</label>
                                <input class="form-control @error('hero_subtitle') is-invalid @enderror" id="hero_subtitle" name="hero_subtitle" value="{{ old('hero_subtitle', $settings->hero_subtitle) }}" placeholder="e.g. Online Shopping Made Easy & Fast">
                                @error('hero_subtitle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="hero_description">Short Description</label>
                                <textarea class="form-control @error('hero_description') is-invalid @enderror" id="hero_description" name="hero_description" rows="2" placeholder="Discover our curated selection of quality essentials at best prices!">{{ old('hero_description', $settings->hero_description) }}</textarea>
                                @error('hero_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="hero_button_url">Button Destination URL</label>
                                <input class="form-control @error('hero_button_url') is-invalid @enderror" id="hero_button_url" name="hero_button_url" value="{{ old('hero_button_url', $settings->hero_button_url) }}" placeholder="/shop-category">
                                @error('hero_button_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <label class="form-label fw-semibold">Hero Background Image</label>
                        <div class="border rounded-3 p-3 bg-light text-center">
                            <div class="position-relative overflow-hidden rounded mb-3" style="max-height: 200px; background: #e2e8f0;">
                                <img src="{{ asset($settings->hero_image ?: 'assets/images/banner/kenkie-hero-banner.jpg') }}" id="heroImagePreview" class="img-fluid w-100 object-fit-cover" style="max-height: 200px;" alt="Hero Preview">
                            </div>
                            <input class="form-control @error('hero_image_file') is-invalid @enderror" type="file" id="hero_image_file" name="hero_image_file" accept="image/*">
                            <small class="text-muted d-block mt-2">Recommended resolution: 1920x650px. Max 10MB.</small>
                            @error('hero_image_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Hero Banner
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SECTION 2: PROMO BANNER CARDS -->
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-cubes text-primary fs-5"></i>
                <h5 class="fw-bold mb-0">Promo Banner Cards (4-Grid Under Hero)</h5>
            </div>
            <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                <i class="fa-solid fa-plus"></i> Add Promo Card
            </button>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse ($banners as $banner)
                    <div class="col-xl-3 col-md-6">
                        <div class="card h-100 border shadow-sm">
                            <div class="position-relative overflow-hidden" style="height: 140px; background: #f1f5f9;">
                                <img src="{{ asset($banner->image ?: 'assets/images/banner/kenkie-promo-home.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $banner->title }}">
                                <span class="position-absolute top-0 start-0 m-2 badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $banner->is_active ? 'Active' : 'Disabled' }}
                                </span>
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark opacity-75">
                                    #{{ $banner->position + 1 }}
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted fw-semibold">{{ $banner->subtitle ?: 'Subtitle' }}</small>
                                <h5 class="fw-bold text-dark mb-2">{{ $banner->title }}</h5>
                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-light text-primary border">{{ $banner->button_text ?: 'Shop Now' }}</span>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $banner->id }}" title="Edit Card">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.home.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this promo card?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Card">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted text-center py-4">No promo banner cards found. Click "Add Promo Card" to create one.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- All Promo Card Modals Placed Outside Grid -->
@foreach ($banners as $banner)
    <div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1" aria-labelledby="editBannerModalLabel{{ $banner->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <form method="POST" action="{{ route('admin.home.banners.update', $banner) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-primary fs-5"></i>
                            <h5 class="modal-title fw-bold mb-0" id="editBannerModalLabel{{ $banner->id }}">Edit Promo Card #{{ $banner->position + 1 }}</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Card Title <span class="text-danger">*</span></label>
                                <input class="form-control" name="title" value="{{ $banner->title }}" placeholder="e.g. Health & Beauty" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subtitle / Tagline</label>
                                <input class="form-control" name="subtitle" value="{{ $banner->subtitle }}" placeholder="e.g. Personal Care">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Button Text</label>
                                <input class="form-control" name="button_text" value="{{ $banner->button_text }}" placeholder="Shop Now">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sort Position</label>
                                <input class="form-control" type="number" name="position" value="{{ $banner->position }}" min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Button Link URL</label>
                                <input class="form-control" name="button_url" value="{{ $banner->button_url }}" placeholder="/shop-category?category=health-beauty">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Card Image</label>
                                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light mb-2">
                                    <img src="{{ asset($banner->image ?: 'assets/images/banner/kenkie-promo-home.jpg') }}" id="bannerPreview{{ $banner->id }}" class="rounded-3 border object-fit-cover shadow-sm" style="width: 100px; height: 70px;" alt="{{ $banner->title }}">
                                    <div class="flex-grow-1">
                                        <input class="form-control banner-file-input" type="file" name="banner_image_file" data-target="bannerPreview{{ $banner->id }}" accept="image/*">
                                        <small class="text-muted d-block mt-1">Accepts PNG, JPG, JPEG, WEBP. Max 10MB.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch p-3 border rounded-3 bg-light">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" id="active{{ $banner->id }}" name="is_active" value="1" @checked($banner->is_active)>
                                    <label class="form-check-label fw-semibold" for="active{{ $banner->id }}">Active & Visible in Store</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top px-4 py-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <form method="POST" action="{{ route('admin.home.banners.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus text-primary fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0" id="addBannerModalLabel">Add Promo Banner Card</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input class="form-control" name="title" placeholder="e.g. Garden & Patio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subtitle / Tagline</label>
                            <input class="form-control" name="subtitle" placeholder="e.g. Outdoor Living">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Button Text</label>
                            <input class="form-control" name="button_text" value="Shop Now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sort Position</label>
                            <input class="form-control" type="number" name="position" value="{{ $banners->count() }}" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Button Link URL</label>
                            <input class="form-control" name="button_url" value="/shop-category" placeholder="/shop-category">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Card Image</label>
                            <input class="form-control" type="file" name="banner_image_file" accept="image/*">
                            <small class="text-muted d-block mt-1">Accepts JPG, PNG, WEBP. Max 10MB.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="fa-solid fa-plus me-1"></i> Create Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroInput = document.getElementById('hero_image_file');
    const heroPreview = document.getElementById('heroImagePreview');
    if (heroInput && heroPreview) {
        heroInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    heroPreview.src = evt.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    document.querySelectorAll('.banner-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const targetId = input.getAttribute('data-target');
            const previewImg = document.getElementById(targetId);
            if (previewImg && e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
});
</script>
@endpush
