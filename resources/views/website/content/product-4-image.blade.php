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
        .product-variations-wrapper {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
        }
        .step-num-badge {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #15803d;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .variation-attr-btn {
            border: 2px solid #e2e8f0;
            background: #ffffff;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            user-select: none;
            position: relative;
        }
        .variation-attr-btn:hover:not(:disabled) {
            border-color: #86efac;
            color: #15803d;
            background: #f0fdf4;
            transform: translateY(-1px);
        }
        .variation-attr-btn.active {
            border-color: #22c55e !important;
            background: #f0fdf4 !important;
            color: #15803d !important;
            font-weight: 700 !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2) !important;
        }
        .variation-attr-btn:disabled,
        .variation-attr-btn.is-disabled {
            opacity: 0.45;
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            transform: none !important;
            text-decoration: line-through;
        }
        .color-swatch-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.18);
            display: inline-block;
            flex-shrink: 0;
        }

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
                            <span id="stockStatusBadge" class="{{ ($product->activeVariations->first()?->stock ?? $product->stock) > 0 ? 'badge-stock-in' : 'badge-stock-out' }}">
                                @if (($product->activeVariations->first()?->stock ?? $product->stock) > 0)
                                    <i class="fa-solid fa-circle-check me-1"></i> In Stock &mdash; <span id="displayStock">{{ $product->activeVariations->first()?->stock ?? $product->stock }}</span> available
                                @else
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Out of Stock
                                @endif
                            </span>
                            @if ($product->category)
                                <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}" class="badge bg-light text-dark border text-decoration-none" style="font-size:11px;">{{ $product->category->name }}</a>
                            @endif
                        </div>

                        {{-- Product name --}}
                        <h1 class="fw-bold mb-3" style="font-size:1.85rem; line-height:1.2; color:#111;">{{ $product->name }}</h1>

                        @php
                            $initialVar = $product->activeVariations->first();
                            $initialPrice = $initialVar ? ($initialVar->sale_price ?? $initialVar->regular_price ?? $product->price) : $product->price;
                            $initialRegular = $initialVar ? ($initialVar->regular_price && (float)$initialVar->regular_price > (float)$initialPrice ? $initialVar->regular_price : null) : ($product->regular_price && (float)$product->regular_price > (float)$product->price ? $product->regular_price : null);
                            $initialDiscount = $initialVar ? $initialVar->discount_percentage : $product->discount_percentage;
                        @endphp

                        {{-- Price row --}}
                        <div class="d-flex align-items-baseline gap-3 mb-1 flex-wrap">
                            <span class="price-main" id="displayProductPrice">£{{ number_format((float) $initialPrice, 2) }}</span>
                            <span class="price-regular {{ $initialRegular ? '' : 'd-none' }}" id="displayRegularPrice">£{{ $initialRegular ? number_format((float) $initialRegular, 2) : '' }}</span>
                            <span class="discount-pill {{ $initialDiscount > 0 ? '' : 'd-none' }}" id="displayDiscountBadge">{{ $initialDiscount }}% OFF</span>
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
                        @php
                            $activeVars = $product->activeVariations ?? collect();
                            $distinctColors = $activeVars->pluck('color')->filter(fn($c) => filled($c))->unique()->values();
                            $distinctSizes = $activeVars->pluck('size')->filter(fn($s) => filled($s))->unique()->values();
                            $hasColorAndSize = $distinctColors->isNotEmpty() && $distinctSizes->isNotEmpty();
                            $hasOnlyColor = $distinctColors->isNotEmpty() && $distinctSizes->isEmpty();
                            $hasOnlySize = $distinctColors->isEmpty() && $distinctSizes->isNotEmpty();

                            $variationsJsonData = $activeVars->map(function($v) use ($product) {
                                $vPrice = $v->sale_price ?? $v->regular_price ?? $product->price;
                                $vRegular = $v->regular_price && (float) $v->regular_price > (float) $vPrice ? $v->regular_price : null;
                                return [
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'color' => $v->color,
                                    'size' => $v->size,
                                    'material' => $v->material,
                                    'dimensions' => $v->dimensions,
                                    'price' => number_format((float) $vPrice, 2, '.', ''),
                                    'raw_price' => (float) $vPrice,
                                    'regular_price' => $vRegular ? number_format((float) $vRegular, 2, '.', '') : null,
                                    'discount_percentage' => (int) $v->discount_percentage,
                                    'sku' => $v->sku ?: $product->sku,
                                    'stock' => (int) $v->stock,
                                    'image' => $v->image ? asset($v->image) : null,
                                    'label' => $v->name ?: trim(($v->color ? $v->color . ' ' : '') . ($v->size ?: '') . ($v->material ? ' (' . $v->material . ')' : ''))
                                ];
                            })->values();

                            $colorHexMap = [
                                'emerald green' => '#10b981',
                                'green' => '#22c55e',
                                'forest green' => '#15803d',
                                'mint' => '#a7f3d0',
                                'charcoal gray' => '#374151',
                                'gray' => '#6b7280',
                                'grey' => '#6b7280',
                                'black' => '#111827',
                                'white' => '#f9fafb',
                                'camel tan' => '#c29b38',
                                'tan' => '#d2b48c',
                                'brown' => '#78350f',
                                'beige' => '#f5f5dc',
                                'midnight navy' => '#1e3a8a',
                                'navy' => '#1e40af',
                                'blue' => '#3b82f6',
                                'royal blue' => '#2563eb',
                                'sky blue' => '#38bdf8',
                                'red' => '#ef4444',
                                'crimson' => '#dc2626',
                                'burgundy' => '#800020',
                                'pink' => '#ec4899',
                                'rose' => '#f43f5e',
                                'orange' => '#f97316',
                                'yellow' => '#eab308',
                                'gold' => '#d97706',
                                'purple' => '#a855f7',
                                'violet' => '#7c3aed',
                                'silver' => '#94a3b8',
                            ];
                        @endphp

                        @if ($activeVars->isNotEmpty())
                            <div class="product-variations-wrapper mb-4">
                                @if ($hasColorAndSize)
                                    {{-- 1. Choose Color First --}}
                                    <div class="variation-group mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="d-flex align-items-center gap-2 fw-bold text-dark" style="font-size:13.5px;">
                                                <span class="step-num-badge">1</span>
                                                <span>Choose Color:</span>
                                                <strong id="selectedColorLabel" class="text-success">{{ $distinctColors->first() }}</strong>
                                            </span>
                                            <span class="text-muted small">Select color first</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="colorOptionsContainer">
                                            @foreach ($distinctColors as $cIndex => $col)
                                                @php
                                                    $normCol = strtolower(trim((string)$col));
                                                    $hex = $colorHexMap[$normCol] ?? '#94a3b8';
                                                @endphp
                                                <button type="button"
                                                    class="variation-attr-btn variation-color-btn {{ $cIndex === 0 ? 'active' : '' }}"
                                                    data-color="{{ $col }}"
                                                    onclick="handleColorSelect('{{ addslashes($col) }}', this)">
                                                    <span class="color-swatch-dot" style="background-color: {{ $hex }};"></span>
                                                    <span>{{ $col }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- 2. Choose Size Next --}}
                                    <div class="variation-group">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="d-flex align-items-center gap-2 fw-bold text-dark" style="font-size:13.5px;">
                                                <span class="step-num-badge">2</span>
                                                <span>Choose Size:</span>
                                                <strong id="selectedSizeLabel" class="text-primary">{{ $distinctSizes->first() }}</strong>
                                            </span>
                                            <span class="text-muted small">Available sizes for selected color</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="sizeOptionsContainer">
                                            @foreach ($distinctSizes as $sIndex => $sz)
                                                <button type="button"
                                                    class="variation-attr-btn variation-size-btn {{ $sIndex === 0 ? 'active' : '' }}"
                                                    data-size="{{ $sz }}"
                                                    onclick="handleSizeSelect('{{ addslashes($sz) }}', this)">
                                                    <span>{{ $sz }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif ($hasOnlyColor)
                                    {{-- Color Only --}}
                                    <div class="variation-group">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="d-flex align-items-center gap-2 fw-bold text-dark" style="font-size:13.5px;">
                                                <i class="fa-solid fa-palette text-success"></i>
                                                <span>Choose Color:</span>
                                                <strong id="selectedColorLabel" class="text-success">{{ $distinctColors->first() }}</strong>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="colorOptionsContainer">
                                            @foreach ($distinctColors as $cIndex => $col)
                                                @php
                                                    $normCol = strtolower(trim((string)$col));
                                                    $hex = $colorHexMap[$normCol] ?? '#94a3b8';
                                                @endphp
                                                <button type="button"
                                                    class="variation-attr-btn variation-color-btn {{ $cIndex === 0 ? 'active' : '' }}"
                                                    data-color="{{ $col }}"
                                                    onclick="handleColorSelect('{{ addslashes($col) }}', this)">
                                                    <span class="color-swatch-dot" style="background-color: {{ $hex }};"></span>
                                                    <span>{{ $col }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif ($hasOnlySize)
                                    {{-- Size Only --}}
                                    <div class="variation-group">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="d-flex align-items-center gap-2 fw-bold text-dark" style="font-size:13.5px;">
                                                <i class="fa-solid fa-ruler-combined text-primary"></i>
                                                <span>Choose Size:</span>
                                                <strong id="selectedSizeLabel" class="text-primary">{{ $distinctSizes->first() }}</strong>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="sizeOptionsContainer">
                                            @foreach ($distinctSizes as $sIndex => $sz)
                                                <button type="button"
                                                    class="variation-attr-btn variation-size-btn {{ $sIndex === 0 ? 'active' : '' }}"
                                                    data-size="{{ $sz }}"
                                                    onclick="handleSizeSelect('{{ addslashes($sz) }}', this)">
                                                    <span>{{ $sz }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                @else
                                    {{-- General Options --}}
                                    <div class="variation-group">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="d-flex align-items-center gap-2 fw-bold text-dark" style="font-size:13.5px;">
                                                <i class="fa-solid fa-swatchbook text-primary"></i>
                                                <span>Choose Option:</span>
                                                <strong id="selectedGenericLabel" class="text-primary">{{ $activeVars->first()?->name ?: 'Option 1' }}</strong>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2" id="genericOptionsContainer">
                                            @foreach ($activeVars as $gIndex => $var)
                                                @php
                                                    $vLabel = $var->name ?: trim(($var->color ? $var->color . ' ' : '') . ($var->size ?: '') . ($var->material ? ' (' . $var->material . ')' : ''));
                                                @endphp
                                                <button type="button"
                                                    class="variation-attr-btn variation-generic-btn {{ $gIndex === 0 ? 'active' : '' }}"
                                                    data-id="{{ $var->id }}"
                                                    onclick="handleGenericSelect({{ $var->id }}, this)">
                                                    <span>{{ $vLabel }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Active selection summary badge --}}
                                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-success"></i> Selected:
                                        <span id="activeVariationSummary" class="fw-semibold text-dark"></span>
                                    </small>
                                    <span id="variationStockNote" class="small fw-semibold text-success"></span>
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
                                <div class="row g-2" id="offersGrid">
                                    @foreach ($applicableOffers as $offer)
                                        @php
                                            $discountedUnit = $offer->discountedPriceFor((float) $initialPrice);
                                            $totalSaving = round(((float) $initialPrice - $discountedUnit) * $offer->min_quantity, 2);
                                        @endphp
                                        <div class="col-sm-6">
                                            <div class="offer-card"
                                                 id="offer-card-{{ $offer->id }}"
                                                 data-offer-id="{{ $offer->id }}"
                                                 data-min-qty="{{ $offer->min_quantity }}"
                                                 data-discount-percent="{{ (float) $offer->discount_percentage }}"
                                                 onclick="selectOfferBundle({{ $offer->min_quantity }}, {{ $offer->id }}, this)">
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
                                                        <span class="fw-bold text-success offer-unit-price" style="font-size:16px;">£{{ number_format($discountedUnit, 2) }}</span>
                                                        <span class="text-muted small">/ea</span>
                                                    </div>
                                                    <span class="text-success small fw-semibold offer-total-saving">Save £{{ number_format($totalSaving, 2) }}</span>
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
                        <form class="mt-2" method="POST" action="{{ route('cart.store', $product->slug) }}" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="quantity" id="qtyHiddenInput" value="1">
                            <input type="hidden" name="variation_id" id="selectedVariationInput" value="{{ $product->activeVariations->first()?->id }}">

                            <div id="inStockControls" class="{{ ($product->activeVariations->first()?->stock ?? $product->stock) > 0 ? '' : 'd-none' }}">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="qty-control">
                                        <button type="button" id="qtyMinus" aria-label="Decrease quantity">
                                            <i class="fa-solid fa-minus" style="font-size:12px;"></i>
                                        </button>
                                        <input type="number" id="qtyDisplay" value="1" min="1" max="{{ $product->activeVariations->first()?->stock ?? $product->stock }}" readonly style="background:none;">
                                        <button type="button" id="qtyPlus" aria-label="Increase quantity">
                                            <i class="fa-solid fa-plus" style="font-size:12px;"></i>
                                        </button>
                                    </div>
                                    <button class="btn theme-bg-color text-white fw-bold flex-grow-1 py-2" type="submit" style="border-radius:12px; font-size:15px;">
                                        <i data-feather="shopping-cart" class="me-2" style="width:18px;height:18px;"></i> Add To Cart
                                    </button>
                                </div>
                            </div>

                            <div id="outOfStockAlert" class="alert alert-warning rounded-3 mt-2 d-flex align-items-center gap-2 {{ ($product->activeVariations->first()?->stock ?? $product->stock) > 0 ? 'd-none' : '' }}">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                This option is currently out of stock.
                            </div>

                            @error('quantity')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('variation_id')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </form>

                        {{-- Wishlist + Continue --}}
                        <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                            <form method="POST" action="{{ route('wishlist.store', $product->slug) }}" class="m-0 d-inline-flex">
                                @csrf
                                <button class="btn btn-outline-secondary fw-semibold d-inline-flex align-items-center justify-content-center px-3 py-2" type="submit" style="border-radius:12px; white-space:nowrap;">
                                    <i class="fa-solid fa-heart me-1 text-danger"></i> Wishlist
                                </button>
                            </form>
                            <a href="{{ route('shop.category') }}" class="btn btn-outline-dark fw-semibold d-inline-flex align-items-center justify-content-center px-3 py-2" style="border-radius:12px; white-space:nowrap;">
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
                                                            <span>£{{ number_format($normalTotal, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between small text-success fw-bold mb-1">
                                                            <span>Discount</span>
                                                            <span>−£{{ number_format($savingTotal, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-1">
                                                            <span>Bundle total</span>
                                                            <span class="text-success fs-5">£{{ number_format($totalPrice, 2) }}</span>
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
    if (img && src) {
        img.style.opacity = '0.2';
        setTimeout(() => {
            img.src = src;
            img.style.opacity = '1';
        }, 160);
    }
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');
}

/* ── Reactive Product, Variation & Offer State ── */
let currentUnitPrice = {{ (float) $initialPrice }};
let currentStock = {{ (int) ($initialVar?->stock ?? $product->stock) }};
let currentQty = 1;

function updateOfferCards() {
    const offerCards = document.querySelectorAll('.offer-card');
    offerCards.forEach(card => {
        const minQty = parseInt(card.dataset.minQty, 10) || 1;
        const discountPercent = parseFloat(card.dataset.discountPercent) || 0;
        const discountedUnit = currentUnitPrice * (1 - (discountPercent / 100));
        const totalSaving = (currentUnitPrice - discountedUnit) * minQty;

        const unitEl = card.querySelector('.offer-unit-price');
        const savingEl = card.querySelector('.offer-total-saving');

        if (unitEl) unitEl.textContent = '£' + discountedUnit.toFixed(2);
        if (savingEl) savingEl.textContent = 'Save £' + totalSaving.toFixed(2);

        if (card.classList.contains('selected')) {
            const summaryText = document.getElementById('savingsSummaryText');
            if (summaryText) {
                summaryText.textContent = 'You save £' + totalSaving.toFixed(2) + ' with this ' + minQty + '-item bundle! 🎉';
            }
        }
    });
}

function selectOfferBundle(qty, offerId, el) {
    if (el.classList.contains('selected')) {
        clearOfferSelection();
        return;
    }
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');

    // Set qty
    setQuantity(qty);

    const minQty = parseInt(el.dataset.minQty, 10) || qty;
    const discountPercent = parseFloat(el.dataset.discountPercent) || 0;
    const discountedUnit = currentUnitPrice * (1 - (discountPercent / 100));
    const totalSaving = (currentUnitPrice - discountedUnit) * minQty;

    const bar = document.getElementById('savingsBar');
    const text = document.getElementById('savingsSummaryText');
    if (bar) bar.style.display = 'block';
    if (text) text.textContent = 'You save £' + totalSaving.toFixed(2) + ' with this ' + minQty + '-item bundle! 🎉';
}

function clearOfferSelection() {
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('selected'));
    setQuantity(1);
    const bar = document.getElementById('savingsBar');
    if (bar) bar.style.display = 'none';
}

function setQuantity(n) {
    const max = Math.max(1, currentStock);
    currentQty = Math.max(1, Math.min(n, max));
    const display = document.getElementById('qtyDisplay');
    const hidden = document.getElementById('qtyHiddenInput');
    if (display) {
        display.value = currentQty;
        display.setAttribute('max', max);
    }
    if (hidden) hidden.value = currentQty;
}

const variationsData = {!! json_encode($variationsJsonData ?? []) !!};

let selectedColor = null;
let selectedSize = null;
let currentSelectedVariation = null;

if (variationsData && variationsData.length > 0) {
    selectedColor = variationsData[0].color || null;
    selectedSize = variationsData[0].size || null;
    currentSelectedVariation = variationsData[0];
}

function handleColorSelect(color, btn) {
    selectedColor = color;

    document.querySelectorAll('.variation-color-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const colorLabel = document.getElementById('selectedColorLabel');
    if (colorLabel) colorLabel.textContent = color;

    const sizeBtns = document.querySelectorAll('.variation-size-btn');
    let firstValidSizeForColor = null;
    let currentSizeStillValid = false;

    sizeBtns.forEach(sBtn => {
        const sizeVal = sBtn.dataset.size;
        const existsForColor = variationsData.some(v => v.color === selectedColor && v.size === sizeVal);

        if (existsForColor) {
            sBtn.disabled = false;
            sBtn.classList.remove('is-disabled');
            if (!firstValidSizeForColor) firstValidSizeForColor = sizeVal;
            if (sizeVal === selectedSize) currentSizeStillValid = true;
        } else {
            sBtn.disabled = true;
            sBtn.classList.add('is-disabled');
        }
    });

    if (!currentSizeStillValid && firstValidSizeForColor) {
        selectedSize = firstValidSizeForColor;
        sizeBtns.forEach(sBtn => {
            if (sBtn.dataset.size === selectedSize) {
                sBtn.classList.add('active');
            } else {
                sBtn.classList.remove('active');
            }
        });
        const sizeLabel = document.getElementById('selectedSizeLabel');
        if (sizeLabel) sizeLabel.textContent = selectedSize;
    }

    syncVariationState();
}

function handleSizeSelect(size, btn) {
    if (btn && btn.disabled) return;

    selectedSize = size;

    document.querySelectorAll('.variation-size-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const sizeLabel = document.getElementById('selectedSizeLabel');
    if (sizeLabel) sizeLabel.textContent = size;

    syncVariationState();
}

function handleGenericSelect(id, btn) {
    document.querySelectorAll('.variation-generic-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const v = variationsData.find(x => x.id === parseInt(id, 10));
    if (v) {
        currentSelectedVariation = v;
        const labelEl = document.getElementById('selectedGenericLabel');
        if (labelEl) labelEl.textContent = v.name || v.label;
        applyVariationDetails(v);
    }
}

function syncVariationState() {
    if (!variationsData || variationsData.length === 0) return;

    let matched = null;
    if (selectedColor && selectedSize) {
        matched = variationsData.find(v => v.color === selectedColor && v.size === selectedSize);
    }
    if (!matched && selectedColor) {
        matched = variationsData.find(v => v.color === selectedColor);
    }
    if (!matched && selectedSize) {
        matched = variationsData.find(v => v.size === selectedSize);
    }
    if (!matched) {
        matched = variationsData[0];
    }

    if (matched) {
        currentSelectedVariation = matched;
        applyVariationDetails(matched);
    }
}

function applyVariationDetails(v) {
    const varInput = document.getElementById('selectedVariationInput');
    const priceEl = document.getElementById('displayProductPrice');
    const regularEl = document.getElementById('displayRegularPrice');
    const discountEl = document.getElementById('displayDiscountBadge');
    const skuEl = document.getElementById('displaySku');
    const stockEl = document.getElementById('displayStock');
    const stockBadge = document.getElementById('stockStatusBadge');
    const inStockControls = document.getElementById('inStockControls');
    const outOfStockAlert = document.getElementById('outOfStockAlert');
    const summaryEl = document.getElementById('activeVariationSummary');
    const stockNote = document.getElementById('variationStockNote');

    if (varInput) varInput.value = v.id;

    currentUnitPrice = parseFloat(v.raw_price) || 0;
    if (priceEl) priceEl.textContent = '£' + v.price;

    if (regularEl) {
        if (v.regular_price && parseFloat(v.regular_price) > currentUnitPrice) {
            regularEl.textContent = '£' + v.regular_price;
            regularEl.classList.remove('d-none');
        } else {
            regularEl.classList.add('d-none');
        }
    }

    if (discountEl) {
        const disc = parseInt(v.discount_percentage, 10) || 0;
        if (disc > 0) {
            discountEl.textContent = disc + '% OFF';
            discountEl.classList.remove('d-none');
        } else {
            discountEl.classList.add('d-none');
        }
    }

    if (skuEl && v.sku) skuEl.textContent = v.sku;

    currentStock = parseInt(v.stock, 10) || 0;
    if (stockEl) stockEl.textContent = currentStock;

    if (currentStock > 0) {
        if (stockBadge) {
            stockBadge.className = 'badge-stock-in';
            stockBadge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> In Stock &mdash; <span id="displayStock">' + currentStock + '</span> available';
        }
        if (stockNote) {
            stockNote.className = 'small fw-semibold text-success';
            stockNote.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> In Stock (' + currentStock + ' available)';
        }
        if (inStockControls) inStockControls.classList.remove('d-none');
        if (outOfStockAlert) outOfStockAlert.classList.add('d-none');
    } else {
        if (stockBadge) {
            stockBadge.className = 'badge-stock-out';
            stockBadge.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Out of Stock';
        }
        if (stockNote) {
            stockNote.className = 'small fw-semibold text-danger';
            stockNote.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Out of Stock';
        }
        if (inStockControls) inStockControls.classList.add('d-none');
        if (outOfStockAlert) outOfStockAlert.classList.remove('d-none');
    }

    if (summaryEl) {
        const parts = [];
        if (v.color) parts.push('Color: ' + v.color);
        if (v.size) parts.push('Size: ' + v.size);
        if (v.material) parts.push('Material: ' + v.material);
        summaryEl.textContent = parts.length > 0 ? parts.join(' | ') : (v.name || v.label);
    }

    if (v.image) {
        switchProductImage(v.image, null);
    }

    setQuantity(currentQty);
    updateOfferCards();
}

document.addEventListener('DOMContentLoaded', function () {
    const display = document.getElementById('qtyDisplay');
    const hidden = document.getElementById('qtyHiddenInput');

    document.getElementById('qtyMinus')?.addEventListener('click', () => setQuantity(currentQty - 1));
    document.getElementById('qtyPlus')?.addEventListener('click', () => setQuantity(currentQty + 1));

    if (variationsData && variationsData.length > 0) {
        syncVariationState();
        if (selectedColor) {
            const firstColorBtn = document.querySelector('.variation-color-btn.active') || document.querySelector('.variation-color-btn');
            if (firstColorBtn) {
                handleColorSelect(firstColorBtn.dataset.color, firstColorBtn);
            }
        }
    }
});
</script>
@endsection
