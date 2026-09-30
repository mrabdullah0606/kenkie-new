<header class="border-bottom bg-white">
    <nav class="container-fluid-lg navbar navbar-expand-lg py-3" aria-label="Store navigation">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">Kenkie</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNav"
            aria-controls="storeNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="storeNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <a class="nav-link" href="{{ route('shop.category') }}">Shop</a>
                <a class="nav-link" href="{{ route('products.index') }}">Featured product</a>
                <a class="nav-link" href="{{ route('wishlist.index') }}">Wishlist ({{ count(session('wishlist', [])) }})</a>
                <a class="nav-link" href="{{ route('cart.index') }}">Cart ({{ array_sum(session('cart', [])) }})</a>
                <a class="btn btn-outline-dark btn-sm" href="{{ route('checkout.index') }}">Checkout</a>
            </div>
        </div>
    </nav>
</header>