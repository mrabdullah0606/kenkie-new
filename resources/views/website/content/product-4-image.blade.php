@extends('website.layouts.storefront')

@section('title', $product->name)
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')
@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .rich-description-area {
            color: #4a5568;
            line-height: 1.6;
        }
        .rich-description-area h1, .rich-description-area h2, .rich-description-area h3, .rich-description-area h4 {
            color: #222;
            font-weight: 700;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        .rich-description-area h1 { font-size: 1.4rem; }
        .rich-description-area h2 { font-size: 1.25rem; }
        .rich-description-area h3 { font-size: 1.1rem; }
        .rich-description-area ul, .rich-description-area ol {
            padding-left: 20px;
            margin-bottom: 12px;
        }
        .rich-description-area ul { list-style-type: disc; }
        .rich-description-area ol { list-style-type: decimal; }
        .rich-description-area li { margin-bottom: 4px; }
        .rich-description-area p { margin-bottom: 8px; }
    </style>
@endsection

@section('body')
    <!-- Loader Start -->
    <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- Loader End -->

    <!-- Header Start -->
    @include('website.includes.home-header')
    <!-- Header End -->

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Product Details</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                @if ($product->category?->parent)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('shop.category', ['category' => $product->category->parent->slug]) }}">{{ $product->category->parent->name }}</a>
                                    </li>
                                @endif
                                @if ($product->category)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Start -->
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row g-4">
                <div class="col-xl-6">
                    <div class="product-left-box">
                        <div class="row g-sm-4 g-2">
                            <div class="col-12 text-center p-3 border rounded bg-white main-product-image-container" style="min-height: 420px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                                <img id="mainProductImage" src="{{ asset($product->image ?: 'assets/images/vegetable/product/1.png') }}"
                                    class="img-fluid blur-up lazyload" style="max-height: 400px; max-width: 100%; object-fit: contain; transition: opacity 0.25s ease;" alt="{{ $product->name }}">
                            </div>

                            @php
                                $gallery = $product->gallery_images;
                            @endphp
                            @if (count($gallery) > 1)
                                <div class="col-12 mt-3">
                                    <div class="product-thumbnail-slider d-flex flex-wrap gap-2 justify-content-start align-items-center">
                                        @foreach ($gallery as $index => $img)
                                            <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                                 onclick="switchProductImage('{{ asset($img) }}', this)"
                                                 style="cursor: pointer; width: 88px; height: 88px; border-radius: 8px; border: 2px solid {{ $index === 0 ? '#22c55e' : '#e2e8f0' }}; padding: 6px; background: #ffffff; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                                                <img src="{{ asset($img) }}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="Thumbnail {{ $index + 1 }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="right-box-contain p-sticky wow fadeInUp">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
                            </span>
                            @if ($product->is_featured)
                                <span class="badge bg-warning text-dark">Featured</span>
                            @endif
                        </div>

                        <h1 class="name fw-bold fs-3 mb-2">{{ $product->name }}</h1>

                        <div class="price-rating my-3 d-flex align-items-center flex-wrap gap-3">
                            <h3 class="theme-color price fs-2 fw-bold mb-0" id="displayProductPrice">
                                ${{ number_format((float) $product->price, 2) }}
                            </h3>
                            @if ($product->regular_price && (float) $product->regular_price > (float) $product->price)
                                <del class="text-muted fs-5" id="displayRegularPrice">
                                    ${{ number_format((float) $product->regular_price, 2) }}
                                </del>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-6" id="displayDiscountBadge">
                                    {{ $product->discount_percentage }}% OFF
                                </span>
                            @endif
                            @if ($product->size_chart)
                                <button type="button" class="btn btn-sm btn-outline-dark ms-auto d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                    <i class="fa-solid fa-ruler-combined"></i> Size Chart
                                </button>
                            @endif
                        </div>

                        @if ($product->activeVariations && $product->activeVariations->isNotEmpty())
                            <div class="product-variations-box bg-light rounded-3 p-3 my-3 border">
                                <label class="fw-bold small text-dark d-block mb-2">Available Options & Variations:</label>
                                <div class="d-flex flex-wrap gap-2" id="variationsContainer">
                                    @foreach ($product->activeVariations as $var)
                                        @php
                                            $varPrice = $var->sale_price ?? $var->regular_price ?? $product->price;
                                            $varLabel = $var->name ?: trim(($var->color ? $var->color . ' ' : '') . ($var->size ?: '') . ($var->material ? ' (' . $var->material . ')' : ''));
                                        @endphp
                                        <button type="button" 
                                            class="btn btn-sm btn-outline-secondary variation-pill-btn {{ $loop->first ? 'active' : '' }}" 
                                            data-price="{{ number_format((float) $varPrice, 2) }}"
                                            data-regular="{{ $var->regular_price ? number_format((float) $var->regular_price, 2) : '' }}"
                                            data-discount="{{ $var->discount_percentage }}"
                                            data-sku="{{ $var->sku ?: $product->sku }}"
                                            data-stock="{{ $var->stock }}"
                                            data-name="{{ $varLabel }}">
                                            {{ $varLabel }}
                                            @if ($varPrice)
                                                <span class="ms-1 small fw-bold">(${{ number_format((float) $varPrice, 2) }})</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="product-contain text-content mb-3 rich-description-area">
                            {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                        </div>

                        <div class="product-info border-top border-bottom py-3 my-3">
                            <ul class="product-info-list list-unstyled mb-0 d-flex flex-column gap-2">
                                <li><strong>Category:</strong> <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                                <li><strong>SKU:</strong> <span id="displaySku">{{ $product->sku }}</span></li>
                                <li><strong>Unit:</strong> {{ $product->unit }}</li>
                                <li><strong>Stock:</strong> <span id="displayStock">{{ $product->stock }}</span> items</li>
                            </ul>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mt-3">{{ session('status') }}</div>
                        @endif

                        @if ($product->activeOffers && $product->activeOffers->isNotEmpty())
                            <div class="multi-offer-box p-3 my-3 rounded-3 border" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-color: #fde68a !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-tags text-warning"></i> Multi-Buy Bundle Savings
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold">Instant Discount</span>
                                </div>
                                <div class="row g-2">
                                    @foreach ($product->activeOffers as $offer)
                                        @php
                                            $discountedUnit = $offer->discountedPriceFor((float) $product->price);
                                        @endphp
                                        <div class="col-sm-6">
                                            <div class="p-2 bg-white rounded-2 border border-warning-subtle shadow-sm h-100 d-flex flex-column justify-content-between offer-bundle-card"
                                                 onclick="selectOfferBundle({{ $offer->min_quantity }}, this)"
                                                 style="cursor: pointer; transition: all 0.2s ease;">
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong class="text-dark">{{ $offer->title ?: ('Buy ' . $offer->min_quantity . ' Items') }}</strong>
                                                        @if ($offer->badge_label)
                                                            <span class="badge bg-danger text-white px-2 py-1" style="font-size: 10px;">{{ $offer->badge_label }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        Save <span class="badge bg-success-subtle text-success fw-bold">{{ (int) $offer->discount_percentage }}% OFF</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 pt-1 border-top d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success fs-6">${{ number_format($discountedUnit, 2) }} <small class="text-muted fw-normal">/ea</small></span>
                                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark py-0 px-2 fw-semibold" style="font-size: 11px;">
                                                        Select {{ $offer->min_quantity }}x
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($product->stock > 0)
                            <form class="note-box product-packege mt-4" method="POST" action="{{ route('cart.store', $product->slug) }}">
                                @csrf
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cart_qty qty-box" style="width: 140px;">
                                        <div class="input-group d-flex align-items-center">
                                            <button type="button" class="btn qty-left-minus" data-type="minus" aria-label="Decrease quantity">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input class="form-control text-center qty-input" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                                            <button type="button" class="btn qty-right-plus" data-type="plus" aria-label="Increase quantity">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button class="btn theme-bg-color text-white fw-bold btn-md flex-grow-1" type="submit">
                                        <i data-feather="shopping-cart" class="me-2"></i> Add To Cart
                                    </button>
                                </div>
                                @error('quantity')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </form>
                        @else
                            <div class="alert alert-warning mt-3">This product is currently out of stock.</div>
                        @endif

                        <div class="buy-box mt-3 d-flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('wishlist.store', $product->slug) }}">
                                @csrf
                                <button class="btn btn-outline-secondary btn-wishlist-action" type="submit">
                                    <i class="fa-solid fa-heart me-2"></i> Add To Wishlist
                                </button>
                            </form>
                            <a href="{{ route('shop.category') }}" class="btn btn-outline-dark btn-continue-action">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-5">
                    <div class="product-section-box">
                        <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                    aria-selected="true"><i class="fa-solid fa-align-left me-2"></i>Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications"
                                    type="button" role="tab" aria-controls="specifications" aria-selected="false"><i class="fa-solid fa-list-check me-2"></i>Specifications</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return-policy"
                                    type="button" role="tab" aria-controls="return-policy" aria-selected="false"><i class="fa-solid fa-shield-halved me-2"></i>Return Policy</button>
                            </li>
                        </ul>

                        <div class="tab-content custom-tab p-4 border border-top-0 rounded-bottom bg-white" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                <div class="product-description rich-description-area">
                                    {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                                    <p class="text-muted mt-3">Packaged with care to ensure the highest freshness, hygiene, and taste. Store in a cool, dry place.</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                                <table class="table table-striped align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">Product Name</th>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>
                                                @if ($product->category?->parent)
                                                    {{ $product->category->parent->name }} &gt;
                                                @endif
                                                {{ $product->category->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>SKU</th>
                                            <td><code>{{ $product->sku }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Unit Size</th>
                                            <td>{{ $product->unit }}</td>
                                        </tr>
                                        <tr>
                                            <th>Availability</th>
                                            <td>
                                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' units)' : 'Out of Stock' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if ($product->activeVariations && $product->activeVariations->isNotEmpty())
                                            <tr>
                                                <th>Variations Available</th>
                                                <td>
                                                    {{ $product->activeVariations->pluck('name')->filter()->implode(', ') ?: ($product->activeVariations->count() . ' options available') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="return-policy" role="tabpanel" aria-labelledby="return-tab">
                                <div class="return-policy-box">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-3 border">
                                        <div class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fa-solid fa-rotate-left fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1 text-dark">14-Day Hassle-Free Returns & Exchanges</h5>
                                            <p class="text-muted mb-0 small">Shop with 100% confidence. If you are not satisfied with your purchase, we make returns straightforward.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded h-100">
                                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-check text-success me-2"></i>Eligible for Return</h6>
                                                <ul class="text-muted small ps-3 mb-0">
                                                    <li>Items received within the last 14 calendar days.</li>
                                                    <li>Items in brand new, unwashed, and undamaged condition with original packaging.</li>
                                                    <li>Defective, damaged in transit, or incorrect items received.</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded h-100">
                                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-truck-fast text-primary me-2"></i>Fast Refund Process</h6>
                                                <ul class="text-muted small ps-3 mb-0">
                                                    <li>Refunds are processed within 2–4 business days of receiving the item.</li>
                                                    <li>Refund is credited back to your original payment method.</li>
                                                    <li>Our support team is available 24/7 for return assistance.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details End -->

    <!-- Related Product Section Start -->
    @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="product-list-section section-b-space mt-4">
            <div class="container-fluid-lg">
                <div class="title d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold mb-0">Related Products</h2>
                    <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}" class="theme-color">See All</a>
                </div>
                <div class="row row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-2 g-3">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col">
                            @include('website.includes.product-card', ['product' => $relatedProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Related Product Section End -->

    <!-- Footer Section Start -->
    @include('website.includes.home-footer')
    <!-- Footer Section End -->

    <!-- Size Chart Modal -->
    @if ($product->size_chart)
        <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="sizeChartModalLabel">
                            <i class="fa-solid fa-ruler-combined me-2 text-primary"></i> Size Guide: {{ $product->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <img src="{{ asset($product->size_chart) }}" class="img-fluid rounded border shadow-sm" alt="Size Chart">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tap to top start -->
    <div class="theme-option">
        <div class="back-to-top">
            <a id="back-to-top" href="#">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <!-- Tap to top end -->
@endsection

@section('page-scripts')
    <script>
        function switchProductImage(src, element) {
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg) {
                mainImg.style.opacity = '0.3';
                setTimeout(function () {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                }, 150);
            }
            document.querySelectorAll('.thumbnail-item').forEach(function (el) {
                el.classList.remove('active');
                el.style.borderColor = '#e2e8f0';
            });
            if (element) {
                element.classList.add('active');
                element.style.borderColor = '#22c55e';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Interactive Variations Switching
            const variationButtons = document.querySelectorAll('.variation-pill-btn');
            const displayPrice = document.getElementById('displayProductPrice');
            const displayRegular = document.getElementById('displayRegularPrice');
            const displayDiscount = document.getElementById('displayDiscountBadge');
            const displaySku = document.getElementById('displaySku');
            const displayStock = document.getElementById('displayStock');

            variationButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    variationButtons.forEach(b => b.classList.remove('active', 'btn-secondary'));
                    this.classList.add('active');

                    if (this.dataset.price && displayPrice) {
                        displayPrice.textContent = '$' + this.dataset.price;
                    }
                    if (this.dataset.regular && displayRegular) {
                        displayRegular.textContent = '$' + this.dataset.regular;
                        displayRegular.classList.remove('d-none');
                    } else if (displayRegular) {
                        displayRegular.classList.add('d-none');
                    }
                    if (this.dataset.discount > 0 && displayDiscount) {
                        displayDiscount.textContent = this.dataset.discount + '% OFF';
                        displayDiscount.classList.remove('d-none');
                    } else if (displayDiscount) {
                        displayDiscount.classList.add('d-none');
                    }
                    if (this.dataset.sku && displaySku) {
                        displaySku.textContent = this.dataset.sku;
                    }
                    if (this.dataset.stock && displayStock) {
                        displayStock.textContent = this.dataset.stock;
                    }
                });
            });
        });
        function selectOfferBundle(qty, element) {
            const qtyInput = document.querySelector('input.qty-input[name="quantity"]');
            if (qtyInput) {
                qtyInput.value = qty;
            }
            document.querySelectorAll('.offer-bundle-card').forEach(card => {
                card.style.borderColor = '#fde68a';
                card.style.boxShadow = 'none';
            });
            if (element) {
                element.style.borderColor = '#eab308';
                element.style.boxShadow = '0 0 0 2px rgba(234, 179, 8, 0.4)';
            }
        }
    </script>
@endsection
