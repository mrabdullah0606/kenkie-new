@extends('website.layouts.storefront')

@section('title', 'Wishlist')
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        <h2>Wishlist</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Wishlist Section Start -->
    <section class="wishlist-section section-b-space">
        <div class="container-fluid-lg">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="row g-sm-3 g-2">
                @forelse ($products as $product)
                    <div class="col-xxl-2 col-lg-3 col-md-4 col-6">
                        @include('website.includes.product-card', ['product' => $product, 'removeFromWishlist' => true])
                    </div>
                @empty
                    <div class="col-12 py-5 text-center">
                        <h4 class="mb-3">Your wishlist is empty.</h4>
                        <p class="text-content mb-4">Explore our catalog and add your favorite items to your wishlist.</p>
                        <a class="btn theme-bg-color text-white btn-md" href="{{ route('shop.category') }}">Browse Products</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Wishlist Section End -->

    <!-- Footer Section Start -->
    @include('website.includes.home-footer')
    <!-- Footer Section End -->

    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather-icon.js') }}"></script>
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
@endsection
