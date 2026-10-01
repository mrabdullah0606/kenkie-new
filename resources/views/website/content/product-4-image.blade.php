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
                        <h2>{{ $product->name }}</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
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

                        <h2 class="name fw-bold">{{ $product->name }}</h2>

                        <div class="price-rating my-3">
                            <h3 class="theme-color price fs-2 fw-bold">${{ number_format($product->price, 2) }}</h3>
                        </div>

                        <div class="product-contain text-content mb-3 rich-description-area">
                            {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                        </div>

                        <div class="product-info border-top border-bottom py-3 my-3">
                            <ul class="product-info-list list-unstyled mb-0 d-flex flex-column gap-2">
                                <li><strong>Category:</strong> <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                                <li><strong>SKU:</strong> {{ $product->sku }}</li>
                                <li><strong>Unit:</strong> {{ $product->unit }}</li>
                                <li><strong>Stock:</strong> {{ $product->stock }} items</li>
                            </ul>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mt-3">{{ session('status') }}</div>
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
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab" aria-controls="info" aria-selected="false">Additional Info</button>
                            </li>
                        </ul>

                        <div class="tab-content custom-tab p-4 border border-top-0 rounded-bottom bg-white" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                <div class="product-description rich-description-area">
                                    {!! $product->description ?: '<p>Fresh and premium quality product delivered directly to your door.</p>' !!}
                                    <p class="text-muted mt-3">Packaged with care to ensure the highest freshness, hygiene, and taste. Store in a cool, dry place.</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                                <table class="table table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Product Name</th>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $product->category->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>SKU</th>
                                            <td>{{ $product->sku }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit Size</th>
                                            <td>{{ $product->unit }}</td>
                                        </tr>
                                        <tr>
                                            <th>Availability</th>
                                            <td>{{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' units)' : 'Out of Stock' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
    </script>
@endsection
