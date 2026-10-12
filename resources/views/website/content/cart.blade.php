@extends('website.layouts.storefront')

@section('title', 'Your cart')
@section('favicon', asset('assets/images/logo/kenkie-favicon-32.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .cart-section {
            padding: 30px 0 60px;
        }
        .cart-table-wrapper {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }
        .cart-custom-table thead th {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4b5563;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 18px;
        }
        .cart-custom-table tbody td {
            padding: 18px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .cart-custom-table tbody tr:last-child td {
            border-bottom: none;
        }
        .cart-thumb {
            width: 68px;
            height: 68px;
            object-fit: contain;
            background: #ffffff;
            padding: 4px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .cart-quantity-box {
            display: inline-flex !important;
            align-items: center !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            padding: 2px 4px !important;
            width: 110px !important;
            height: 36px !important;
            position: static !important;
        }
        .cart-quantity-box .qty-control-btn {
            width: 28px !important;
            height: 28px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #f3f4f6 !important;
            border: none !important;
            border-radius: 4px !important;
            color: #374151 !important;
            font-size: 11px !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            position: static !important;
        }
        .cart-quantity-box .qty-control-btn:hover {
            background: #22c55e !important;
            color: #ffffff !important;
        }
        .cart-quantity-box .qty-input {
            border: none !important;
            background: transparent !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            color: #111827 !important;
            width: 44px !important;
            padding: 0 !important;
            height: 100% !important;
            text-align: center !important;
            box-shadow: none !important;
            position: static !important;
        }
        .hover-theme:hover {
            color: #22c55e !important;
        }
        .coupon-input {
            border: 1px solid #d1d5db;
            border-right: none;
            font-size: 14px;
        }
        .coupon-input:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.25);
        }
        .cart-summary-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
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
                        <h2>Shopping Cart</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Cart Section Start -->
    <section class="cart-section section-b-space">
        <div class="container-fluid-lg">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @error('quantity')
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @enderror

            <div id="cart-empty-wrapper" class="{{ $cartItems->isEmpty() ? '' : 'd-none' }}">
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i data-feather="shopping-bag" style="width: 64px; height: 64px;" class="text-muted"></i>
                        </div>
                        <h3>Your cart is empty.</h3>
                        <p class="text-muted">Explore our curated collections and find something you love.</p>
                        <a href="{{ route('shop.category') }}" class="btn theme-bg-color text-white fw-bold mt-3 px-4 py-2">
                            Start Shopping
                        </a>
                    </div>
                </div>
            </div>

            @if (!$cartItems->isEmpty())
                @php($shippingFeeCents = $cartSubtotalCents >= \App\Services\StorefrontCart::FREE_SHIPPING_THRESHOLD_CENTS ? 0 : \App\Services\StorefrontCart::SHIPPING_FEE_CENTS)
                <div class="row g-4" id="cart-active-wrapper">
                    <!-- Cart Table Column -->
                    <div class="col-xxl-9 col-lg-8">
                        <div class="cart-table-wrapper">
                            <div class="table-responsive">
                                <table class="table cart-custom-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 280px;">Product</th>
                                            <th scope="col" style="min-width: 110px;">Price</th>
                                            <th scope="col" style="min-width: 140px;">Quantity</th>
                                            <th scope="col" style="min-width: 110px;">Total</th>
                                            <th scope="col" class="text-end" style="min-width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items-tbody">
                                        @foreach ($cartItems as $item)
                                            @php($product = $item['product'])
                                            @php($safeKey = str_replace(':', '-', $item['key']))
                                            <tr class="cart-item-row" id="cart-row-{{ $safeKey }}" data-cart-key="{{ $item['key'] }}" data-slug="{{ $product->slug }}" data-has-variation="{{ !empty($item['variation']) ? '1' : '0' }}">
                                                <td class="product-info-cell">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="flex-shrink-0">
                                                            <img src="{{ asset($item['displayImage'] ?? $product->image ?: 'assets/images/vegetable/product/1.png') }}"
                                                                class="cart-thumb" alt="{{ $item['displayName'] ?? $product->name }}">
                                                        </a>
                                                        <div class="product-info-meta">
                                                            <h6 class="mb-1 fw-bold">
                                                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none text-truncate-2">
                                                                    {{ $item['displayName'] ?? $product->name }}
                                                                </a>
                                                            </h6>
                                                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                                                                <span>Sold By: <strong class="text-secondary">{{ $product->category?->name ?? 'Kenkie' }}</strong></span>
                                                                <span>•</span>
                                                                <span>SKU: {{ $item['displaySku'] ?? $product->sku }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="price-cell">
                                                    <div class="fw-semibold text-dark fs-6" id="unit-price-{{ $safeKey }}">£{{ number_format($item['effectiveUnitPriceCents'] / 100, 2) }}</div>
                                                    <div id="offer-badge-{{ $safeKey }}">
                                                        @if (!empty($item['appliedOffer']))
                                                            <span class="badge bg-warning text-dark border border-warning-subtle mt-1" style="font-size: 11px;">
                                                                <i class="fa-solid fa-tags me-1"></i> {{ $item['appliedOffer']->title ?: ('Multi-Buy ' . (int)$item['appliedOffer']->discount_percentage . '% OFF') }}
                                                            </span>
                                                        @elseif(!empty($item['variation']))
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">Variation Option</span>
                                                        @else
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">In Stock</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="qty-cell">
                                                    <div class="cart-quantity-box">
                                                        <button type="button" class="btn qty-control-btn cart-qty-decrement" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" aria-label="Decrease">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                        <input type="text" class="form-control qty-input text-center" id="qty-val-{{ $safeKey }}"
                                                            value="{{ $item['quantity'] }}" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" max="{{ $item['maxStock'] ?? $product->stock }}" readonly>
                                                        <button type="button" class="btn qty-control-btn cart-qty-increment" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}" aria-label="Increase">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="total-cell">
                                                    <div class="fw-bold theme-color fs-6" id="line-total-{{ $safeKey }}">
                                                        £{{ number_format($item['lineTotalCents'] / 100, 2) }}
                                                    </div>
                                                </td>

                                                <td class="action-cell text-end">
                                                    <div class="d-inline-flex flex-column align-items-end gap-1">
                                                        <form method="POST" action="{{ route('wishlist.store', $product->slug) }}" class="wishlist-form m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-link p-0 text-muted text-decoration-none small hover-theme">
                                                                <i class="fa-regular fa-heart me-1"></i>Save for later
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-link p-0 text-danger text-decoration-none small cart-item-remove-btn" data-slug="{{ $product->slug }}" data-key="{{ $item['key'] }}">
                                                            <i class="fa-regular fa-trash-can me-1"></i>Remove
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary Sidebar -->
                    <div class="col-xxl-3 col-lg-4">
                        <div class="cart-summary-card position-sticky" style="top: 100px;">
                            <h4 class="fw-bold mb-3 pb-2 border-bottom text-dark">Cart Total</h4>

                            <div class="coupon-block mb-4">
                                <label class="form-label text-muted small fw-semibold mb-1">Coupon Apply</label>
                                <div class="input-group">
                                    <input type="text" class="form-control coupon-input" placeholder="Enter Coupon Code...">
                                    <button class="btn theme-bg-color text-white px-3 fw-semibold" type="button">Apply</button>
                                </div>
                            </div>

                            <div class="summary-breakdown mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold text-dark" id="cart-summary-subtotal">£{{ number_format($cartSubtotalCents / 100, 2) }}</span>
                                </div>
                                <div class="justify-content-between align-items-center mb-2 text-success {{ (!empty($cartSavingsCents) && $cartSavingsCents > 0) ? 'd-flex' : 'd-none' }}" id="cart-summary-savings-row">
                                    <span><i class="fa-solid fa-tag me-1"></i> Multi-Buy Savings</span>
                                    <span class="fw-bold" id="cart-summary-savings">-£{{ number_format(($cartSavingsCents ?? 0) / 100, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Delivery</span>
                                    <span class="fw-semibold {{ $shippingFeeCents === 0 ? 'text-success' : 'text-dark' }}" id="cart-summary-delivery">
                                        @if ($shippingFeeCents === 0)
                                            FREE
                                        @else
                                            £{{ number_format($shippingFeeCents / 100, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-3 border-top border-bottom mb-4">
                                <span class="fw-bold text-dark fs-5">Total (GBP)</span>
                                <span class="fw-bold theme-color fs-4" id="cart-summary-total">£{{ number_format(($cartSubtotalCents + $shippingFeeCents) / 100, 2) }}</span>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout.index') }}" class="btn theme-bg-color text-white py-2 fw-bold text-center">
                                    Process To Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                                @guest
                                    <small class="text-muted text-center d-block">
                                        <i class="fa-solid fa-user-lock me-1 text-secondary"></i> Sign in or register is required to place your order.
                                    </small>
                                @endguest
                                <a href="{{ route('shop.category') }}" class="btn btn-light py-2 text-dark text-center fw-medium border">
                                    <i class="fa-solid fa-arrow-left-long me-2"></i>Return To Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Cart Section End -->

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
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            function updateCartBadges(count) {
                const countNum = parseInt(count, 10) || 0;

                // 1. Desktop header bag badge
                document.querySelectorAll('.bag-icon .badge-number, .header-wishlist .badge-number').forEach(function (badge) {
                    badge.textContent = countNum;
                    badge.style.display = countNum > 0 ? '' : 'none';
                });

                // 2. Mobile cart badge
                document.querySelectorAll('a[href*="/cart"].mobile-header-icon .mobile-badge, a[href*="/cart"] .mobile-badge').forEach(function (badge) {
                    badge.textContent = countNum;
                    badge.style.display = countNum > 0 ? '' : 'none';
                });

                // 3. Fallback header-cart-count badges
                document.querySelectorAll('.header-cart-count').forEach(function (badge) {
                    badge.textContent = countNum;
                    badge.style.display = countNum > 0 ? '' : 'none';
                });
            }

            function applyCartResponse(res) {
                if (!res || !res.success) {
                    return;
                }

                // Update Header Cart Count Badges
                if (res.cartCount !== undefined) {
                    updateCartBadges(res.cartCount);
                }

                // If cart is completely empty
                if (res.isEmpty) {
                    const activeWrap = document.getElementById('cart-active-wrapper');
                    const emptyWrap = document.getElementById('cart-empty-wrapper');
                    if (activeWrap) {
                        activeWrap.style.display = 'none';
                    }
                    if (emptyWrap) {
                        emptyWrap.classList.remove('d-none');
                        emptyWrap.style.display = 'block';
                    }
                    if (window.feather) {
                        feather.replace();
                    }
                    return;
                }

                // Update all items from items payload
                if (res.items) {
                    Object.keys(res.items).forEach(function (key) {
                        const itemData = res.items[key];
                        const safeKey = key.replace(/:/g, '-');

                        // Unit price
                        const priceEl = document.getElementById('unit-price-' + safeKey);
                        if (priceEl && itemData.unitPrice !== undefined) {
                            priceEl.textContent = '£' + itemData.unitPrice;
                        }

                        // Line total
                        const totalEl = document.getElementById('line-total-' + safeKey);
                        if (totalEl && itemData.lineTotal !== undefined) {
                            totalEl.textContent = '£' + itemData.lineTotal;
                        }

                        // Quantity input
                        const qtyEl = document.getElementById('qty-val-' + safeKey);
                        if (qtyEl && itemData.quantity !== undefined) {
                            qtyEl.value = itemData.quantity;
                        }

                        // Offer Badge
                        const badgeEl = document.getElementById('offer-badge-' + safeKey);
                        if (badgeEl) {
                            const row = document.getElementById('cart-row-' + safeKey);
                            const hasVar = row && row.getAttribute('data-has-variation') === '1';

                            if (itemData.hasOffer && itemData.offerTitle) {
                                badgeEl.innerHTML = '<span class="badge bg-warning text-dark border border-warning-subtle mt-1" style="font-size: 11px;"><i class="fa-solid fa-tags me-1"></i> ' + itemData.offerTitle + '</span>';
                            } else if (hasVar) {
                                badgeEl.innerHTML = '<span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">Variation Option</span>';
                            } else {
                                badgeEl.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle mt-1">In Stock</span>';
                            }
                        }
                    });

                    // Remove rows that no longer exist in response
                    document.querySelectorAll('.cart-item-row').forEach(function (row) {
                        const rowKey = row.getAttribute('data-cart-key');
                        if (rowKey && !res.items[rowKey]) {
                            row.remove();
                        }
                    });
                }

                // Update Summary Subtotal
                const subtotalEl = document.getElementById('cart-summary-subtotal');
                if (subtotalEl && res.subtotal !== undefined) {
                    subtotalEl.textContent = '£' + res.subtotal;
                }

                // Update Multi-Buy Savings Row
                const savingsRowEl = document.getElementById('cart-summary-savings-row');
                const savingsEl = document.getElementById('cart-summary-savings');
                if (savingsRowEl && savingsEl) {
                    if (res.hasSavings && parseFloat(res.savings) > 0) {
                        savingsEl.textContent = '-£' + res.savings;
                        savingsRowEl.classList.remove('d-none');
                        savingsRowEl.classList.add('d-flex');
                    } else {
                        savingsRowEl.classList.add('d-none');
                        savingsRowEl.classList.remove('d-flex');
                    }
                }

                // Update Delivery
                const deliveryEl = document.getElementById('cart-summary-delivery');
                if (deliveryEl && res.shipping !== undefined) {
                    deliveryEl.textContent = res.shipping;
                    if (res.shipping === 'FREE') {
                        deliveryEl.className = 'fw-semibold text-success';
                    } else {
                        deliveryEl.className = 'fw-semibold text-dark';
                    }
                }

                // Update Total (GBP)
                const totalEl = document.getElementById('cart-summary-total');
                if (totalEl && res.total !== undefined) {
                    totalEl.textContent = '£' + res.total;
                }
            }

            async function sendCartUpdate(slug, key, newQty, btn) {
                if (btn) {
                    btn.disabled = true;
                }
                const safeKey = key.replace(/:/g, '-');
                const row = document.getElementById('cart-row-' + safeKey);
                if (row) {
                    row.style.opacity = '0.5';
                    row.style.pointerEvents = 'none';
                }

                try {
                    const isDelete = newQty <= 0;
                    const url = '/cart/' + encodeURIComponent(slug);
                    const bodyData = isDelete
                        ? { _token: csrfToken, _method: 'DELETE', cart_key: key }
                        : { _token: csrfToken, _method: 'PATCH', quantity: newQty, cart_key: key };

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(bodyData)
                    });

                    const data = await response.json();
                    if (data && data.success) {
                        applyCartResponse(data);
                    } else {
                        alert((data && data.message) ? data.message : 'Unable to update cart.');
                    }
                } catch (err) {
                    console.error('Cart update error:', err);
                } finally {
                    if (btn) {
                        btn.disabled = false;
                    }
                    if (row) {
                        row.style.opacity = '1';
                        row.style.pointerEvents = '';
                    }
                }
            }

            // Global delegation for Cart actions
            document.addEventListener('click', function (e) {
                // 1. Decrement Button
                const decBtn = e.target.closest('.cart-qty-decrement');
                if (decBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const slug = decBtn.getAttribute('data-slug');
                    const key = decBtn.getAttribute('data-key');
                    const safeKey = key.replace(/:/g, '-');
                    const input = document.getElementById('qty-val-' + safeKey);
                    const currentVal = parseInt(input ? input.value : '1', 10) || 1;
                    const newVal = currentVal - 1;
                    sendCartUpdate(slug, key, newVal, decBtn);
                    return;
                }

                // 2. Increment Button
                const incBtn = e.target.closest('.cart-qty-increment');
                if (incBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const slug = incBtn.getAttribute('data-slug');
                    const key = incBtn.getAttribute('data-key');
                    const safeKey = key.replace(/:/g, '-');
                    const input = document.getElementById('qty-val-' + safeKey);
                    const currentVal = parseInt(input ? input.value : '1', 10) || 1;
                    const maxVal = parseInt(input ? input.getAttribute('max') : '9999', 10) || 9999;
                    if (currentVal >= maxVal) {
                        return;
                    }
                    const newVal = currentVal + 1;
                    sendCartUpdate(slug, key, newVal, incBtn);
                    return;
                }

                // 3. Remove Button
                const removeBtn = e.target.closest('.cart-item-remove-btn');
                if (removeBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const slug = removeBtn.getAttribute('data-slug');
                    const key = removeBtn.getAttribute('data-key');
                    sendCartUpdate(slug, key, 0, removeBtn);
                    return;
                }
            }, true);
        });
    </script>
@endsection
