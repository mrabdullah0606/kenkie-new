@extends('website.layouts.storefront')

@section('title', ($selectedCategory?->name ?? ($search ? "Search: {$search}" : 'Shop')))
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')
@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .shop-category-slider-box {
            background: #fff;
            border-radius: 8px;
            padding: 18px 12px;
            text-align: center;
            display: block;
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            text-decoration: none;
            height: 100%;
        }
        .shop-category-slider-box:hover,
        .shop-category-slider-box.active {
            border-color: #22c55e;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.15);
            transform: translateY(-2px);
        }
        .shop-category-slider-box img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            margin: 0 auto 10px;
            display: block;
        }
        .shop-category-slider-box h5 {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            margin-bottom: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .shop-category-slider-box.active h5 {
            color: #22c55e;
        }
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 0;
            margin: 0 0 15px;
            list-style: none;
        }
        .filter-tags li a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #f3f5f7;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 13px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .filter-tags li a:hover {
            background-color: #e4e7eb;
            color: #22c55e;
        }
        .filter-tags li a i {
            font-size: 11px;
        }
        .category-list-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            cursor: pointer;
        }
        .category-list-box label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 0;
            cursor: pointer;
            font-size: 14px;
            color: #4a5568;
        }
        .category-list-box label .number {
            color: #8c98a4;
            font-size: 13px;
        }
        .custome-accordion .accordion-item {
            border: none;
            border-bottom: 1px solid #eee;
            margin-bottom: 0;
            background: transparent;
        }
        .custome-accordion .accordion-button {
            padding: 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: #222;
            background: transparent;
            box-shadow: none;
        }
        .custome-accordion .accordion-button:not(.collapsed) {
            color: #22c55e;
        }
        .custome-accordion .accordion-body {
            padding: 0 0 16px;
        }
        .rating-star-row {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #ffb800;
            font-size: 13px;
        }
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
                        <h2>{{ $selectedCategory?->name ?? ($search ? "Search: {$search}" : 'Shop Categories') }}</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('shop.category') }}">Shop</a>
                                </li>
                                @if ($selectedCategory)
                                    <li class="breadcrumb-item active" aria-current="page">{{ $selectedCategory->name }}</li>
                                @elseif ($search)
                                    <li class="breadcrumb-item active" aria-current="page">{{ $search }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">All Categories</li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Top Category Slider Section Start -->
    <section class="category-slider-section pb-4">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="row g-3 row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2">
                        <div class="col">
                            <a href="{{ route('shop.category') }}" class="shop-category-slider-box {{ empty($selectedCategories) && !$search ? 'active' : '' }}">
                                <img src="{{ asset('assets/svg/1/grocery.svg') }}" alt="All Products">
                                <h5>All Products</h5>
                            </a>
                        </div>
                        @foreach ($categories as $cat)
                            @php
                                $iconMap = [
                                    'home' => 'assets/svg/1/grocery.svg',
                                    'furniture' => 'assets/svg/1/grocery.svg',
                                    'garden' => 'assets/svg/leaf.svg',
                                    'patio' => 'assets/svg/leaf.svg',
                                    'vehicle' => 'assets/svg/delivery.svg',
                                    'sporting' => 'assets/svg/market.svg',
                                    'pet' => 'assets/svg/1/pet.svg',
                                    'mobile' => 'assets/svg/product.svg',
                                    'phone' => 'assets/svg/product.svg',
                                    'health' => 'assets/svg/1/cup.svg',
                                    'beauty' => 'assets/svg/1/cup.svg',
                                    'computer' => 'assets/svg/discount.svg',
                                    'tablet' => 'assets/svg/discount.svg',
                                    'collectables' => 'assets/svg/1/biscuit.svg',
                                    'crafts' => 'assets/svg/1/breakfast.svg',
                                    'sound' => 'assets/svg/1/drink.svg',
                                    'vision' => 'assets/svg/1/drink.svg',
                                ];
                                $catSlugLower = strtolower($cat->slug);
                                $catIcon = 'assets/svg/1/grocery.svg';
                                foreach ($iconMap as $key => $svgPath) {
                                    if (str_contains($catSlugLower, $key)) {
                                        $catIcon = $svgPath;
                                        break;
                                    }
                                }
                                if ($cat->image && file_exists(public_path($cat->image))) {
                                    $catIcon = $cat->image;
                                }
                            @endphp
                            <div class="col">
                                <a href="{{ route('shop.category', ['category' => $cat->slug]) }}"
                                   class="shop-category-slider-box {{ in_array($cat->slug, $selectedCategories) ? 'active' : '' }}">
                                    <img src="{{ asset($catIcon) }}" alt="{{ $cat->name }}">
                                    <h5>{{ $cat->name }}</h5>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Top Category Slider Section End -->

    <!-- Shop Section Start -->
    <section class="section-b-space shop-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <!-- Left Sidebar Filters -->
                <div class="col-custome-3">
                    <div class="left-box wow fadeInUp">
                        <div class="shop-left-sidebar">
                            <div class="back-button d-lg-none mb-3">
                                <h3><i class="fa-solid fa-arrow-left"></i> Back</h3>
                            </div>

                            <div class="filter-category">
                                <div class="filter-title d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="fw-bold mb-0 text-dark">Filters</h4>
                                    <a href="{{ route('shop.category') }}" class="theme-color text-decoration-none fw-semibold">Clear All</a>
                                </div>

                                @if (!empty($selectedCategories) || $search || $minPrice || $maxPrice)
                                    <ul class="filter-tags">
                                        @foreach ($categories->whereIn('slug', $selectedCategories) as $selectedCat)
                                            <li>
                                                <a href="{{ route('shop.category', array_merge(request()->query(), ['categories' => array_values(array_diff($selectedCategories, [$selectedCat->slug])), 'category' => null])) }}">
                                                    {{ $selectedCat->name }} <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                        @if ($search)
                                            <li>
                                                <a href="{{ route('shop.category', request()->except(['search', 'q'])) }}">
                                                    "{{ $search }}" <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($minPrice || $maxPrice)
                                            <li>
                                                <a href="{{ route('shop.category', request()->except(['min_price', 'max_price'])) }}">
                                                    ${{ $minPrice ?? 0 }} - ${{ $maxPrice ?? (int)$priceMaxBound }} <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                @endif

                                <form id="filterForm" method="GET" action="{{ route('shop.category') }}">
                                    @if ($search)
                                        <input type="hidden" name="search" value="{{ $search }}">
                                    @endif
                                    @if ($sort)
                                        <input type="hidden" name="sort" value="{{ $sort }}">
                                    @endif

                                    <div class="accordion custome-accordion" id="accordionExample">
                                        <!-- Categories Filter -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingCategories">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseCategories" aria-expanded="true"
                                                    aria-controls="collapseCategories">
                                                    <span>Categories</span>
                                                </button>
                                            </h2>
                                            <div id="collapseCategories" class="accordion-collapse collapse show"
                                                aria-labelledby="headingCategories">
                                                <div class="accordion-body">
                                                    <div class="search-input mb-3">
                                                        <input type="search" class="form-control" id="categorySearchInput"
                                                            placeholder="Search">
                                                        <i class="fa-solid fa-magnifying-glass"></i>
                                                    </div>
                                                    <ul class="category-list custom-padding custom-height list-unstyled m-0" id="categoryListWrap">
                                                        @foreach ($categories as $cat)
                                                            <li class="category-item-row mb-2">
                                                                <div class="form-check ps-0 m-0 category-list-box">
                                                                    <input class="checkbox_animated category-checkbox"
                                                                        type="checkbox"
                                                                        name="categories[]"
                                                                        value="{{ $cat->slug }}"
                                                                        id="cat_{{ $cat->id }}"
                                                                        {{ in_array($cat->slug, $selectedCategories) ? 'checked' : '' }}
                                                                        onchange="document.getElementById('filterForm').submit();">
                                                                    <label class="form-check-label ms-2" for="cat_{{ $cat->id }}">
                                                                        <span class="name">{{ $cat->name }}</span>
                                                                        <span class="number">({{ $cat->products_count }})</span>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price Filter -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingPrice">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapsePrice" aria-expanded="true"
                                                    aria-controls="collapsePrice">
                                                    <span>Price</span>
                                                </button>
                                            </h2>
                                            <div id="collapsePrice" class="accordion-collapse collapse show"
                                                aria-labelledby="headingPrice">
                                                <div class="accordion-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-light text-dark p-2 fw-semibold" id="minPriceBadge">${{ $minPrice ?? 0 }}</span>
                                                        <span class="badge bg-light text-dark p-2 fw-semibold" id="maxPriceBadge">${{ $maxPrice ?? (int)$priceMaxBound }}</span>
                                                    </div>
                                                    <div class="d-flex gap-2 align-items-center mb-3">
                                                        <input type="number" class="form-control form-control-sm" name="min_price"
                                                            value="{{ $minPrice ?? 0 }}" min="0" max="{{ (int)$priceMaxBound }}" placeholder="Min">
                                                        <span>-</span>
                                                        <input type="number" class="form-control form-control-sm" name="max_price"
                                                            value="{{ $maxPrice ?? (int)$priceMaxBound }}" min="0" max="{{ (int)$priceMaxBound }}" placeholder="Max">
                                                        <button type="submit" class="btn btn-sm theme-bg-color text-white px-3">Go</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rating Filter -->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingRating">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseRating" aria-expanded="true"
                                                    aria-controls="collapseRating">
                                                    <span>Rating</span>
                                                </button>
                                            </h2>
                                            <div id="collapseRating" class="accordion-collapse collapse show"
                                                aria-labelledby="headingRating">
                                                <div class="accordion-body">
                                                    <ul class="category-list custom-padding list-unstyled m-0">
                                                        @for ($stars = 5; $stars >= 1; $stars--)
                                                            <li class="mb-2">
                                                                <div class="form-check ps-0 m-0 category-list-box">
                                                                    <input class="checkbox_animated" type="checkbox" id="rating_{{ $stars }}"
                                                                        name="rating" value="{{ $stars }}"
                                                                        {{ request('rating') == $stars ? 'checked' : '' }}
                                                                        onchange="document.getElementById('filterForm').submit();">
                                                                    <label class="form-check-label ms-2 d-flex align-items-center justify-content-between w-100" for="rating_{{ $stars }}">
                                                                        <div class="rating-star-row">
                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                <i class="fa-solid fa-star {{ $i <= $stars ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                                                            @endfor
                                                                        </div>
                                                                        <span class="number">({{ $stars }} Star)</span>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Product Listing Area -->
                <div class="col-custome-9">
                    <div class="show-button">
                        <div class="top-filter-menu d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <button class="btn btn-sm theme-bg-color text-white filter-button d-lg-none">
                                    <i class="fa-solid fa-filter me-1"></i> Filters
                                </button>
                                <h5 class="text-content mb-0">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</h5>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="category-dropdown d-flex align-items-center gap-2">
                                    <h5 class="text-content mb-0">Sort By :</h5>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle btn btn-sm btn-outline-secondary" type="button" id="dropdownSortMenu"
                                            data-bs-toggle="dropdown">
                                            <span>{{ match(request('sort')) { 'low' => 'Price: Low - High', 'high' => 'Price: High - Low', 'aToz' => 'A - Z Order', 'zToa' => 'Z - A Order', 'popular' => 'Most Popular', default => 'Most Popular' } }}</span> <i class="fa-solid fa-angle-down ms-1"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownSortMenu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category', array_merge(request()->query(), ['sort' => 'popular'])) }}">Most Popular</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category', array_merge(request()->query(), ['sort' => 'low'])) }}">Price: Low - High</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category', array_merge(request()->query(), ['sort' => 'high'])) }}">Price: High - Low</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category', array_merge(request()->query(), ['sort' => 'aToz'])) }}">A - Z Order</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category', array_merge(request()->query(), ['sort' => 'zToa'])) }}">Z - A Order</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="grid-option d-none d-md-block">
                                    <ul class="d-flex align-items-center gap-2 list-unstyled m-0">
                                        <li class="grid-btn active" title="3 Grid View">
                                            <a href="javascript:void(0)" class="text-muted"><i class="fa-solid fa-table-cells-large fs-5"></i></a>
                                        </li>
                                        <li class="list-btn" title="List View">
                                            <a href="javascript:void(0)" class="text-muted"><i class="fa-solid fa-list fs-5"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section mt-2">
                        @forelse ($products as $product)
                            <div class="col">
                                @include('website.includes.product-card', ['product' => $product])
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="empty-shop-box p-4">
                                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                                    <h4>No products found</h4>
                                    <p class="text-content">Try checking your spelling or adjusting your category/price filter.</p>
                                    <a href="{{ route('shop.category') }}" class="btn theme-bg-color text-white mt-3">Browse All Products</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="custome-pagination mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->

    <!-- Footer Section Start -->
    @include('website.includes.home-footer')
    <!-- Footer Section End -->

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
        document.addEventListener('DOMContentLoaded', function () {
            // Live category search filtering in sidebar
            const categorySearchInput = document.getElementById('categorySearchInput');
            if (categorySearchInput) {
                categorySearchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();
                    const categoryRows = document.querySelectorAll('#categoryListWrap .category-item-row');
                    categoryRows.forEach(function (row) {
                        const name = row.querySelector('.name')?.textContent.toLowerCase() || '';
                        if (name.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Mobile filter sidebar toggle
            const filterBtn = document.querySelector('.filter-button');
            const leftBox = document.querySelector('.left-box');
            const backBtn = document.querySelector('.shop-left-sidebar .back-button');

            if (filterBtn && leftBox) {
                filterBtn.addEventListener('click', function () {
                    leftBox.classList.add('show');
                });
            }
            if (backBtn && leftBox) {
                backBtn.addEventListener('click', function () {
                    leftBox.classList.remove('show');
                });
            }
        });
    </script>
@endsection
