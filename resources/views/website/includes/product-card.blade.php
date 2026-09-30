<div class="product-box-3 h-100 wow fadeInUp">
    <div class="product-header">
        <div class="product-image">
            <a href="{{ route('products.show', $product->slug) }}">
                <img src="{{ asset($product->image ?: 'assets/images/vegetable/product/1.png') }}"
                    class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
            </a>

            <ul class="product-option">
                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                    <a href="javascript:void(0)" class="quick-view-btn" data-bs-toggle="modal" data-bs-target="#view" data-product="{{ json_encode([
                        'name' => $product->name,
                        'price' => number_format($product->price, 2),
                        'image' => asset($product->image ?: 'assets/images/vegetable/product/1.png'),
                        'category' => $product->category?->name ?? 'General',
                        'sku' => $product->sku ?? 'N/A',
                        'unit' => $product->unit ?? '1 Unit',
                        'stock' => (int) $product->stock,
                        'description' => $product->description ?? 'Fresh and high quality product from our store.',
                        'show_url' => route('products.show', $product->slug),
                        'cart_url' => route('cart.store', $product->slug),
                    ]) }}">
                        <i data-feather="eye"></i>
                    </a>
                </li>

                <li data-bs-toggle="tooltip" data-bs-placement="top" title="{{ ($removeFromWishlist ?? false) ? 'Remove' : 'Wishlist' }}">
                    @if (($removeFromWishlist ?? false) === true)
                        <form method="POST" action="{{ route('wishlist.destroy', $product->slug) }}" class="wishlist-form m-0 p-0 w-100 h-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-0 border-0 bg-transparent text-danger w-100 h-100 d-flex align-items-center justify-content-center">
                                <i data-feather="trash-2"></i>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('wishlist.store', $product->slug) }}" class="wishlist-form m-0 p-0 w-100 h-100">
                            @csrf
                            <button type="submit" class="p-0 border-0 bg-transparent text-muted w-100 h-100 d-flex align-items-center justify-content-center">
                                <i data-feather="heart"></i>
                            </button>
                        </form>
                    @endif
                </li>
            </ul>
        </div>
    </div>

    <div class="product-footer">
        <div class="product-detail">
            @if ($product->category)
                <a href="{{ route('shop.category', ['category' => $product->category->slug]) }}" class="span-name text-muted">{{ $product->category->name }}</a>
            @endif
            <a href="{{ route('products.show', $product->slug) }}">
                <h5 class="name">{{ $product->name }}</h5>
            </a>
            <p class="text-content mt-1 mb-2 product-content">{{ $product->unit }} · {{ $product->stock }} in stock</p>
            <h5 class="price">
                <span class="theme-color">${{ number_format($product->price, 2) }}</span>
            </h5>
            <div class="add-to-cart-box bg-white mt-2">
                <button class="btn btn-add-cart addcart-button" type="button" @disabled($product->stock < 1) data-slug="{{ $product->slug }}">
                    {{ $product->stock > 0 ? 'Add' : 'Out of Stock' }}
                    <span class="add-icon bg-light-gray">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </button>
                <div class="cart_qty qty-box">
                    <form method="POST" action="{{ route('cart.store', $product->slug) }}" class="input-group d-flex align-items-center" data-slug="{{ $product->slug }}">
                        @csrf
                        <button type="button" class="btn qty-left-minus" data-type="minus" aria-label="Decrease quantity">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input class="form-control input-number qty-input text-center" type="text"
                            name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" class="btn qty-right-plus" data-type="plus" aria-label="Increase quantity">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>