@extends('website.layouts.storefront')

@section('title', ($product->meta_title ?: $product->name) . ' | KENKIE')
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 160))
@section('meta_keywords', $product->meta_keywords ?: $product->name . ', UK gadget shop, buy online')
@section('og_type', 'product')
@section('og_title', ($product->meta_title ?: $product->name) . ' | KENKIE')
@section('og_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 160))
@section('og_url', route('products.show', $product->slug))
@section('og_image', $product->image ? asset($product->image) : asset('assets/images/banner/kenkie-hero-banner.jpg'))
@section('canonical_url', route('products.show', $product->slug))
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        /* ── Rich description typography ── */
        .rich-description-area { color: #4a5568; line-height: 1.7; font-size: 15px; }
        .rich-description-area h1,.rich-description-area h2,.rich-description-area h3,.rich-description-area h4 { color:#222; font-weight:700; margin-top:14px; margin-bottom:6px; }
        .rich-description-area h1{font-size:1.4rem;} .rich-description-area h2{font-size:1.25rem;} .rich-description-area h3{font-size:1.1rem;}
        .rich-description-area ul,.rich-description-area ol { padding-left:20px; margin-bottom:12px; }
        .rich-description-area ul{list-style-type:disc;} .rich-description-area ol{list-style-type:decimal;}
        .rich-description-area li{margin-bottom:5px;} .rich-description-area p{margin-bottom:10px;}

        /* ── Gallery ── */
        .main-image-wrap {
            position: relative;
            background: #f8fafc;
            border-radius: 16px;
            overflow: hidden;
            min-height: 440px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        #mainProductImage { max-height: 420px; max-width:100%; object-fit:contain; transition: opacity 0.2s ease; }
        .thumbnail-strip { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
        .thumb-item {
            width:80px; height:80px; border-radius:10px;
            border: 2px solid #e2e8f0; padding:5px; background:#fff;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; transition: all 0.2s ease;
        }
        .thumb-item:hover { border-color: #86efac; transform: translateY(-1px); }
        .thumb-item.active { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.2); }
        .thumb-item img { max-width:100%; max-height:100%; object-fit:contain; }

        /* ── Badge pill ── */
        .badge-stock-in  { background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:12px; padding:4px 10px; border-radius:20px; font-weight:600; }
        .badge-stock-out { background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; font-size:12px; padding:4px 10px; border-radius:20px; font-weight:600; }

        /* ── Price block ── */
        .price-main { font-size: 2.2rem; font-weight: 800; color: #15803d; line-height: 1; }
        .price-regular { font-size: 1.1rem; color: #9ca3af; text-decoration: line-through; }
        .discount-pill { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:20px; font-size:12px; font-weight:700; padding:3px 10px; }

        /* ── Variations ── */
        .variation-pill-btn {
            border: 2px solid #e2e8f0; background:#fff; border-radius:10px;
            padding: 7px 14px; font-size:13px; font-weight:600; color:#374151;
            transition: all 0.18s ease; cursor:pointer;
        }
        .variation-pill-btn:hover { border-color:#86efac; color:#15803d; }
        .variation-pill-btn.active { border-color:#22c55e; background:#f0fdf4; color:#15803d; box-shadow: 0 0 0 3px rgba(34,197,94,0.15); }

        /* ── Offers ── */
        .offers-section {
            background: linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);
            border: 1.5px solid #fde68a; border-radius:14px; padding:18px;
        }
        .offer-card {
            background:#fff; border-radius:10px; border:2px solid #fde68a;
            padding:14px; cursor:pointer; transition: all 0.2s ease;
            position:relative; overflow:hidden;
        }
        .offer-card:hover { border-color:#eab308; transform:translateY(-2px); box-shadow:0 6px 20px rgba(234,179,8,0.18); }
        .offer-card.selected { border-color:#22c55e; background:#f0fdf4; box-shadow:0 0 0 3px rgba(34,197,94,0.18); }
        .offer-card .check-mark {
            position:absolute; top:10px; right:10px;
            width:22px; height:22px; border-radius:50%;
            background:#22c55e; color:#fff; font-size:12px;
            display:none; align-items:center; justify-content:center;
        }
        .offer-card.selected .check-mark { display:flex; }
        .offer-qty-badge { background:#fef3c7; color:#854d0e; border-radius:6px; font-size:11px; font-weight:700; padding:2px 8px; }
        .offer-saving { font-size:11px; color:#16a34a; font-weight:700; }
        .total-savings-bar {
            background: linear-gradient(90deg,#f0fdf4,#dcfce7);
            border:1.5px solid #86efac; border-radius:10px; padding:12px 16px;
            margin-top:12px; display:none;
        }

        /* ── Add to cart bar ── */
        .qty-control { display:flex; align-items:center; border:2px solid #e2e8f0; border-radius:12px; overflow:hidden; }
        .qty-control button { background:none; border:none; padding:10px 16px; font-size:16px; cursor:pointer; color:#374151; transition:background 0.15s; }
        .qty-control button:hover { background:#f3f4f6; }
        .qty-control input { width:56px; border:none; text-align:center; font-weight:700; font-size:16px; outline:none; }

        /* ── Trust badges ── */
        .trust-strip { display:flex; gap:16px; flex-wrap:wrap; margin-top:18px; }
        .trust-item { display:flex; align-items:center; gap:6px; font-size:12px; color:#4b5563; font-weight:500; }
        .trust-item i { color:#22c55e; font-size:14px; }

        /* ── Tabs ── */
        .product-tabs .nav-link { color:#6b7280; font-weight:600; border:none; border-bottom:3px solid transparent; padding:10px 18px; border-radius:0; transition:all 0.2s; }
        .product-tabs .nav-link.active { color:#15803d; border-bottom-color:#22c55e; background:none; }
        .product-tabs .nav-link:hover:not(.active) { color:#374151; border-bottom-color:#e2e8f0; }
    </style>
@endsection

@section('body')
    <!-- Loader -->
    <div class="fullpage-loader">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    @include('website.includes.home-header')

    <!-- Breadcrumb -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>{{ $product->name }}</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
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

    <!-- Product Section -->
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row g-5">

                {{-- ── LEFT: Image Gallery ── --}}
                <div class="col-xl-6">
                    @php $gallery = $product->gallery_images; @endphp

                    {{-- Main image --}}
                    <div class="main-image-wrap">
                        @if ($product->is_featured)
                            <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark fw-bold px-2 py-1" style="border-radius:8px; font-size:11px; z-index:2;">
                                <i class="fa-solid fa-star me-1"></i> Featured
                            </span>
                        @endif
                        @if ($product->regular_price && (float) $product->regular_price > (float) $product->price)
                            <span class="position-absolute top-0 end-0 m-3 badge bg-danger fw-bold px-2 py-1" style="border-radius:8px; font-size:11px; z-index:2;">
                                {{ $product->discount_percentage }}% OFF
                            </span>
                        @endif
                        <img id="mainProductImage"
                             src="{{ asset($product->image ?: 'assets/images/vegetable/product/1.png') }}"
                             class="img-fluid blur-up lazyload"
                             alt="{{ $product->name }}">
                    </div>

                    {{-- Thumbnail strip --}}
                    @if (count($gallery) > 1)
                        <div class="thumbnail-strip">
                            @foreach ($gallery as $index => $img)
                                <div class="thumb-item {{ $index === 0 ? 'active' : '' }}"
                                     onclick="switchProductImage('{{ asset($img) }}', this)">
                                    <img src="{{ asset($img) }}" alt="View {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── RIGHT: Product Info ── --}}
                <div class="col-xl-6">
                    <div class="right-box-contain p-sticky">

                        {{-- Stock + Category badges --}}
                        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                            @if ($product->stock > 0)
                                <span class="badge-stock-in">
                                    <i class="fa-solid fa-circle-check me-1"></i> In Stock &mdash; {{ $product->stock }} available
                                </span>
                            @else
                                <span class="badge-stock-out">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Out of Stock
                                </span>
                            @endif
                            <span class="badge bg-light text-dark border" style="font-size:11px;">{{ $product->category?->name }}</span>
                        </div>

                        {{-- Product name --}}
                        <h1 class="fw-bold mb-3" style="font-size:1.85rem; line-height:1.2; color:#111;">{{ $product->name }}</h1>

                        {{-- Price row --}}
                        <div class="d-flex align-items-baseline gap-3 mb-1 flex-wrap">
                            <span class="price-main" id="displayProductPrice">${{ number_format((float) $product->price, 2) }}</span>
                            @if ($product->regular_price && (float) $product->regular_price > (float) $product->price)
                                <span class="price-regular" id="displayRegularPrice">${{ number_format((float) $product->regular_price, 2) }}</span>
                                <span class="discount-pill" id="displayDiscountBadge">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            @php $effectiveSizeChart = $product->size_chart ?: $product->category?->size_chart; @endphp
                            @if ($effectiveSizeChart)
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-auto d-inline-flex align-items-center gap-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                    <i class="fa-solid fa-ruler-combined text-primary"></i> Size Guide
                                </button>
                            @endif
                        </div>
                        <p class="text-muted small mb-3">Per {{ $product->unit }}</p>

                        {{-- Short description --}}
                        <div class="rich-description-area mb-3" style="max-height:90px; overflow:hidden; mask-image:linear-gradient(to bottom,black 60%,transparent 100%);" id="shortDesc">
                            {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                        </div>

                        {{-- Variations --}}
                        @if ($product->activeVariations && $product->activeVariations->isNotEmpty())
                            <div class="mb-4">
                                <p class="fw-bold text-dark small mb-2">
                                    <i class="fa-solid fa-swatchbook me-1 text-primary"></i> Choose Option:
                                </p>
                                <div class="d-flex flex-wrap gap-2" id="variationsContainer">
                                    @foreach ($product->activeVariations as $var)
                                        @php
                                            $varPrice = $var->sale_price ?? $var->regular_price ?? $product->price;
                                            $varLabel = $var->name ?: trim(($var->color ? $var->color . ' ' : '') . ($var->size ?: '') . ($var->material ? ' (' . $var->material . ')' : ''));
                                        @endphp
                                        <button type="button"
                                            class="variation-pill-btn {{ $loop->first ? 'active' : '' }}"
                                            data-price="{{ number_format((float) $varPrice, 2) }}"
                                            data-regular="{{ $var->regular_price ? number_format((float) $var->regular_price, 2) : '' }}"
                                            data-discount="{{ $var->discount_percentage }}"
                                            data-sku="{{ $var->sku ?: $product->sku }}"
                                            data-stock="{{ $var->stock }}"
                                            data-name="{{ $varLabel }}">
                                            {{ $varLabel }}
                                            @if ($varPrice)
                                                <span class="ms-1 small opacity-75">${{ number_format((float) $varPrice, 2) }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Multi-buy Offers --}}
                        @php
                            $isProductOnSale = $product->regular_price && (float) $product->regular_price > (float) $product->price;
                            $applicableOffers = $product->activeOffers ? $product->activeOffers->filter(fn($o) => $isProductOnSale ? $o->allow_on_discounted : true) : collect();
                        @endphp
                        @if ($applicableOffers->isNotEmpty())
                            <div class="offers-section mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size:14px;">
                                        <i class="fa-solid fa-tags text-warning fs-5"></i>
                                        Multi-Buy Savings
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold px-2" style="font-size:11px;">Instant Discount</span>
                                </div>
                                <div class="row g-2">
                                    @foreach ($applicableOffers as $offer)
                                        @php
                                            $discountedUnit = $offer->discountedPriceFor((float) $product->price);
                                            $totalSaving = round(((float) $product->price - $discountedUnit) * $offer->min_quantity, 2);
                                        @endphp
                                        <div class="col-sm-6">
                                            <div class="offer-card"
                                                 id="offer-card-{{ $offer->id }}"
                                                 onclick="selectOfferBundle({{ $offer->min_quantity }}, {{ $offer->id }}, {{ $discountedUnit }}, {{ $totalSaving }}, this)">
                                                <div class="check-mark"><i class="fa-solid fa-check" style="font-size:10px;"></i></div>
                                                <div class="d-flex align-items-start justify-content-between mb-1">
                                                    <span class="fw-bold text-dark" style="font-size:14px;">
                                                        {{ $offer->title ?: ('Buy ' . $offer->min_quantity . ($offer->min_quantity > 1 ? ' Items' : ' Item')) }}
                                                    </span>
                                                    @if ($offer->badge_label)
                                                        <span class="badge bg-danger text-white px-2" style="font-size:10px; border-radius:6px; flex-shrink:0; margin-left:6px;">{{ $offer->badge_label }}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="offer-qty-badge">{{ $offer->min_quantity }}x</span>
                                                    <span class="offer-saving"><i class="fa-solid fa-arrow-down-long me-1"></i>{{ (int) $offer->discount_percentage }}% OFF each</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center pt-2" style="border-top:1px solid #fde68a;">
                                                    <div>
                                                        <span class="fw-bold text-success" style="font-size:16px;">${{ number_format($discountedUnit, 2) }}</span>
                                                        <span class="text-muted small">/ea</span>
                                                    </div>
                                                    <span class="text-success small fw-semibold">Save ${{ number_format($totalSaving, 2) }} total</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Savings summary bar (shown on offer select) --}}
                                <div class="total-savings-bar" id="savingsBar">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <span class="fw-bold text-success d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-piggy-bank"></i>
                                            <span id="savingsSummaryText">You're saving on this bundle!</span>
                                        </span>
                                        <button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="clearOfferSelection()">
                                            <i class="fa-solid fa-xmark me-1"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Add to Cart --}}
                        @if ($product->stock > 0)
                            <form class="mt-2" method="POST" action="{{ route('cart.store', $product->slug) }}" id="addToCartForm">
                                @csrf
                                <input type="hidden" name="quantity" id="qtyHiddenInput" value="1">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="qty-control">
                                        <button type="button" id="qtyMinus" aria-label="Decrease quantity">
                                            <i class="fa-solid fa-minus" style="font-size:12px;"></i>
                                        </button>
                                        <input type="number" id="qtyDisplay" value="1" min="1" max="{{ $product->stock }}" readonly style="background:none;">
                                        <button type="button" id="qtyPlus" aria-label="Increase quantity">
                                            <i class="fa-solid fa-plus" style="font-size:12px;"></i>
                                        </button>
                                    </div>
                                    <button class="btn theme-bg-color text-white fw-bold flex-grow-1 py-2" type="submit" style="border-radius:12px; font-size:15px;">
                                        <i data-feather="shopping-cart" class="me-2" style="width:18px;height:18px;"></i> Add To Cart
                                    </button>
                                </div>
                                @error('quantity')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </form>
                        @else
                            <div class="alert alert-warning rounded-3 mt-2 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                This product is currently out of stock.
                            </div>
                        @endif

                        {{-- Wishlist + Continue --}}
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <form method="POST" action="{{ route('wishlist.store', $product->slug) }}">
                                @csrf
                                <button class="btn btn-outline-secondary fw-semibold" type="submit" style="border-radius:12px;">
                                    <i class="fa-solid fa-heart me-1 text-danger"></i> Wishlist
                                </button>
                            </form>
                            <a href="{{ route('shop.category') }}" class="btn btn-outline-dark fw-semibold" style="border-radius:12px;">
                                Continue Shopping
                            </a>
                        </div>

                        {{-- Trust strip --}}
                        <div class="trust-strip">
                            <div class="trust-item"><i class="fa-solid fa-truck-fast"></i> Free shipping over $50</div>
                            <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> Secure checkout</div>
                            <div class="trust-item"><i class="fa-solid fa-rotate-left"></i> 14-day returns</div>
                            <div class="trust-item"><i class="fa-solid fa-headset"></i> 24/7 support</div>
                        </div>

                        {{-- Quick specs --}}
                        <div class="mt-4 pt-3 border-top" style="font-size:13px; color:#6b7280;">
                            <span><strong>SKU:</strong> <span id="displaySku" class="font-monospace">{{ $product->sku }}</span></span>
                            &nbsp;·&nbsp;
                            <span><strong>Unit:</strong> {{ $product->unit }}</span>
                            &nbsp;·&nbsp;
                            <span><strong>Stock:</strong> <span id="displayStock">{{ $product->stock }}</span> units</span>
                        </div>
                    </div>
                </div>

                {{-- ── TABS: Description / Specs / Returns ── --}}
                <div class="col-12 mt-3">
                    <div class="product-section-box">
                        <ul class="nav product-tabs border-bottom" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                                    <i class="fa-solid fa-align-left me-2"></i>Description
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button" role="tab">
                                    <i class="fa-solid fa-list-check me-2"></i>Specifications
                                </button>
                            </li>
                            @if ($applicableOffers->isNotEmpty())
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="offers-tab" data-bs-toggle="tab" data-bs-target="#offers-pane" type="button" role="tab">
                                        <i class="fa-solid fa-tags me-2"></i>Offers & Bundles
                                    </button>
                                </li>
                            @endif
                            @php $effectiveSizeChart = $product->size_chart ?: $product->category?->size_chart; @endphp
                            @if ($effectiveSizeChart)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="sizeguide-tab" data-bs-toggle="tab" data-bs-target="#sizeguide-pane" type="button" role="tab">
                                        <i class="fa-solid fa-ruler-combined me-2"></i>Size Guide
                                    </button>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return-pane" type="button" role="tab">
                                    <i class="fa-solid fa-shield-halved me-2"></i>Return Policy
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content border border-top-0 rounded-bottom bg-white p-4" id="productTabContent">
                            {{-- Description --}}
                            <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                                <div class="rich-description-area">
                                    {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                                    <p class="text-muted mt-3 small">Packaged with care to ensure the highest freshness, hygiene, and quality. Store in a cool, dry place.</p>
                                </div>
                            </div>

                            {{-- Specifications --}}
                            <div class="tab-pane fade" id="specs-pane" role="tabpanel">
                                <table class="table table-striped align-middle mb-0">
                                    <tbody>
                                        <tr><th style="width:200px;">Product Name</th><td>{{ $product->name }}</td></tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>
                                                @if ($product->category?->parent){{ $product->category->parent->name }} › @endif
                                                {{ $product->category->name }}
                                            </td>
                                        </tr>
                                        <tr><th>SKU</th><td><code>{{ $product->sku }}</code></td></tr>
                                        <tr><th>Unit Size</th><td>{{ $product->unit }}</td></tr>
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
                                                <th>Variations</th>
                                                <td>{{ $product->activeVariations->pluck('name')->filter()->implode(', ') ?: ($product->activeVariations->count() . ' options') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Offers tab --}}
                            @if ($applicableOffers->isNotEmpty())
                                <div class="tab-pane fade" id="offers-pane" role="tabpanel">
                                    <div class="row g-3">
                                        @foreach ($applicableOffers as $offer)
                                            @php
                                                $discountedUnit = $offer->discountedPriceFor((float) $product->price);
                                                $totalPrice = round($discountedUnit * $offer->min_quantity, 2);
                                                $normalTotal = round((float) $product->price * $offer->min_quantity, 2);
                                                $savingTotal = round($normalTotal - $totalPrice, 2);
                                            @endphp
                                            <div class="col-md-6 col-lg-4">
                                                <div class="p-4 border rounded-3 h-100 d-flex flex-column" style="border-color:#fde68a !important; background:linear-gradient(135deg,#fffbeb,#fef9ee);">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h6 class="fw-bold text-dark mb-1">{{ $offer->title ?: ('Buy ' . $offer->min_quantity) }}</h6>
                                                            @if ($offer->badge_label)
                                                                <span class="badge bg-danger text-white" style="font-size:10px;">{{ $offer->badge_label }}</span>
                                                            @endif
                                                        </div>
                                                        <span class="badge bg-warning text-dark fw-bold px-2">{{ (int) $offer->discount_percentage }}% OFF</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                                            <span>Normal price ({{ $offer->min_quantity }}x)</span>
                                                            <span>${{ number_format($normalTotal, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between small text-success fw-bold mb-1">
                                                            <span>Discount</span>
                                                            <span>−${{ number_format($savingTotal, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-1">
                                                            <span>Bundle total</span>
                                                            <span class="text-success fs-5">${{ number_format($totalPrice, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    @if ($offer->ends_at)
                                                        <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                                                            <i class="fa-regular fa-clock"></i> Offer ends {{ $offer->ends_at->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Return Policy --}}
                            {{-- Size Guide Pane --}}
                            @if ($effectiveSizeChart)
                                <div class="tab-pane fade" id="sizeguide-pane" role="tabpanel">
                                    <div class="p-3 bg-light rounded-3 mb-4 border text-center">
                                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 text-start">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">
                                                    <i class="fa-solid fa-ruler-combined text-primary me-2"></i>Product Size Chart & Guide
                                                </h6>
                                                <small class="text-muted">Use this guide to choose the ideal fit for {{ $product->name }}.</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                                <i class="fa-solid fa-maximize me-1"></i> Expand Full Screen
                                            </button>
                                        </div>
                                        <div class="size-chart-img-wrap p-2 bg-white rounded border d-inline-block shadow-sm">
                                            <img src="{{ asset($effectiveSizeChart) }}" class="img-fluid rounded" style="max-height: 420px; object-fit: contain;" alt="Size Guide">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Return Policy --}}
                            <div class="tab-pane fade" id="return-pane" role="tabpanel">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-3 border">
                                    <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width:50px;height:50px;flex-shrink:0;">
                                        <i class="fa-solid fa-rotate-left fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">14-Day Hassle-Free Returns & Exchanges</h5>
                                        <p class="text-muted mb-0 small">Shop with 100% confidence. If you are not satisfied, we make returns straightforward.</p>
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
                                                <li>Refunds processed within 2–4 business days of receiving the item.</li>
                                                <li>Credited back to your original payment method.</li>
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
    </section>

    {{-- Related Products --}}
    @if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="product-list-section section-b-space mt-4">
            <div class="container-fluid-lg">
                <div class="title d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold mb-0">You May Also Like</h2>
                    <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}" class="theme-color fw-semibold">See All</a>
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

    @include('website.includes.home-footer')

    {{-- Size Chart Modal --}}
    @if ($effectiveSizeChart)
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
                        <img src="{{ asset($effectiveSizeChart) }}" class="img-fluid rounded border shadow-sm" alt="Size Chart">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="theme-option"><div class="back-to-top"><a id="back-to-top" href="#"><i class="fas fa-chevron-up"></i></a></div></div>
@endsection

@section('page-scripts')
<script>
/* ── Image switcher ── */
function switchProductImage(src, el) {
    const img = document.getElementById('mainProductImage');
    if (img) { img.style.opacity = '0.2'; setTimeout(() => { img.src = src; img.style.opacity = '1'; }, 160); }
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');
}

/* ── Quantity control ── */
document.addEventListener('DOMContentLoaded', function () {
    const maxStock = {{ $product->stock ?? 0 }};
    const display  = document.getElementById('qtyDisplay');
    const hidden   = document.getElementById('qtyHiddenInput');
    let qty = 1;

    function setQty(n) {
        qty = Math.max(1, Math.min(n, maxStock));
        if (display) display.value = qty;
        if (hidden)  hidden.value  = qty;
    }

    document.getElementById('qtyMinus')?.addEventListener('click', () => setQty(qty - 1));
    document.getElementById('qtyPlus')?.addEventListener('click', () => setQty(qty + 1));

    /* ── Variation pills ── */
    const varBtns      = document.querySelectorAll('.variation-pill-btn');
    const priceEl      = document.getElementById('displayProductPrice');
    const regularEl    = document.getElementById('displayRegularPrice');
    const discountEl   = document.getElementById('displayDiscountBadge');
    const skuEl        = document.getElementById('displaySku');
    const stockEl      = document.getElementById('displayStock');

    varBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            varBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            if (this.dataset.price && priceEl)  priceEl.textContent = '$' + this.dataset.price;
            if (regularEl) {
                if (this.dataset.regular) { regularEl.textContent = '$' + this.dataset.regular; regularEl.classList.remove('d-none'); }
                else regularEl.classList.add('d-none');
            }
            if (discountEl) {
                if (this.dataset.discount > 0) { discountEl.textContent = this.dataset.discount + '% OFF'; discountEl.classList.remove('d-none'); }
                else discountEl.classList.add('d-none');
            }
            if (this.dataset.sku   && skuEl)   skuEl.textContent   = this.dataset.sku;
            if (this.dataset.stock && stockEl) stockEl.textContent = this.dataset.stock;
        });
    });
});

/* ── Offer bundle selector ── */
function selectOfferBundle(qty, offerId, unitPrice, totalSaving, el) {
    // Toggle: click again to deselect
    if (el.classList.contains('selected')) {
        clearOfferSelection();
        return;
    }
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');

    // Set qty
    const display = document.getElementById('qtyDisplay');
    const hidden  = document.getElementById('qtyHiddenInput');
    if (display) display.value = qty;
    if (hidden)  hidden.value  = qty;

    // Show savings bar
    const bar  = document.getElementById('savingsBar');
    const text = document.getElementById('savingsSummaryText');
    if (bar)  bar.style.display = 'block';
    if (text) text.textContent  = 'You save $' + totalSaving.toFixed(2) + ' with this ' + qty + '-item bundle! 🎉';
}

function clearOfferSelection() {
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('selected'));
    const display = document.getElementById('qtyDisplay');
    const hidden  = document.getElementById('qtyHiddenInput');
    if (display) display.value = 1;
    if (hidden)  hidden.value  = 1;
    const bar = document.getElementById('savingsBar');
    if (bar) bar.style.display = 'none';
}
</script>
@endsection
