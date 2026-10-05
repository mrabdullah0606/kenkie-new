<header class="header-3">
    <div class="top-nav sticky-header sticky-header-2">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-top">
                        <div class="d-flex align-items-center">
                            <button class="navbar-toggler d-xl-none d-block p-0 me-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#primaryMenu" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon">
                                    <i class="iconly-Category icli"></i>
                                </span>
                            </button>
                            <a href="{{ route('home') }}" class="web-logo nav-logo">
                                <img src="{{ asset('assets/images/logo/kenkie-logo.png') }}" class="img-fluid blur-up lazyload" alt="Kenkie">
                            </a>
                        </div>

                        <div class="middle-box d-none d-xl-block ms-auto">
                            <div class="center-box w-100 live-search-container position-relative">
                                <form action="{{ route('shop.category') }}" method="GET" class="searchbar-box-2 input-group w-100" autocomplete="off">
                                    <button class="btn search-icon" type="submit" aria-label="Search">
                                        <i class="iconly-Search icli"></i>
                                    </button>
                                    <input type="text" name="search" class="form-control js-live-search-input"
                                        value="{{ request('search') }}"
                                        placeholder="Search for products, styles, brands..."
                                        autocomplete="off">
                                    <button class="btn search-button" type="submit">Search</button>
                                </form>
                                <div class="js-live-search-dropdown live-search-dropdown d-none"></div>
                            </div>
                        </div>

                        <!-- Mobile Header Quick Actions -->
                        <div class="mobile-header-right d-xl-none d-flex align-items-center gap-2">
                            <a href="{{ route('wishlist.index') }}" class="mobile-header-icon" aria-label="Wishlist">
                                <i class="iconly-Heart icli"></i>
                                @if (($headerWishlistCount ?? 0) > 0)
                                    <span class="mobile-badge">{{ $headerWishlistCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('cart.index') }}" class="mobile-header-icon" aria-label="Cart">
                                <i class="iconly-Bag-2 icli"></i>
                                @if (($headerCartCount ?? 0) > 0)
                                    <span class="mobile-badge">{{ $headerCartCount }}</span>
                                @endif
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="mobile-header-icon" aria-label="My Account">
                                    <i class="iconly-Profile icli"></i>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="mobile-header-icon" aria-label="Sign In">
                                    <i class="iconly-Profile icli"></i>
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Mobile Full-Width Search Bar -->
                    <div class="mobile-search-row d-xl-none">
                        <div class="live-search-container position-relative w-100">
                            <form action="{{ route('shop.category') }}" method="GET" class="mobile-search-form" autocomplete="off">
                                <span class="mobile-search-prefix">
                                    <i class="iconly-Search icli"></i>
                                </span>
                                <input type="text" name="search" class="mobile-search-input js-live-search-input"
                                    value="{{ request('search') }}"
                                    placeholder="Search products, brands, styles..."
                                    autocomplete="off">
                                <button class="mobile-search-btn" type="submit">Search</button>
                            </form>
                            <div class="js-live-search-dropdown live-search-dropdown live-search-dropdown--mobile d-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12 position-relative">
                <div class="main-nav nav-left-align">
                    <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky p-0">
                        <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                            <div class="offcanvas-header navbar-shadow">
                                <h5>Menu</h5>
                                <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link nav-link-single ps-0" href="{{ route('home') }}">Home</a>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                            data-bs-toggle="dropdown">Shop</a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('shop.category') }}">All Products</a>
                                            </li>
                                            @foreach ($headerCategories ?? $categories ?? [] as $cat)
                                                @if (isset($cat->children) && $cat->children->isNotEmpty())
                                                    <li class="dropdown-submenu position-relative">
                                                        <div class="d-flex align-items-center justify-content-between pe-2">
                                                            <a class="dropdown-item flex-grow-1" href="{{ route('shop.category', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                                            <a class="text-muted px-2 py-1" data-bs-toggle="collapse" href="#subcat-{{ $cat->id }}" role="button" aria-expanded="false" title="Expand subcategories">
                                                                <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                                                            </a>
                                                        </div>
                                                        <ul class="collapse list-unstyled ps-3 bg-light rounded-2 py-1 mx-2" id="subcat-{{ $cat->id }}">
                                                            @foreach ($cat->children as $sub)
                                                                <li>
                                                                    <a class="dropdown-item py-1 small" href="{{ route('shop.category', ['category' => $sub->slug]) }}">
                                                                        <i class="fa-solid fa-angle-right me-1 text-muted" style="font-size: 10px;"></i> {{ $sub->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('shop.category', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link nav-link-single" href="{{ route('about') }}">About Us</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link nav-link-single" href="{{ route('contact.us') }}">Contact Us</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="rightside-menu d-none d-xl-flex">
                        <ul class="option-list-2">
                            <li>
                                <a href="javascript:void(0)" class="header-icon search-box search-icon">
                                    <i class="iconly-Search icli"></i>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('wishlist.index') }}" class="header-icon swap-icon position-relative">
                                    @if (($headerWishlistCount ?? 0) > 0)
                                        <small class="badge-number badge-light">{{ $headerWishlistCount }}</small>
                                    @endif
                                    <i class="iconly-Heart icli"></i>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cart.index') }}" class="header-icon bag-icon position-relative">
                                    @if (($headerCartCount ?? 0) > 0)
                                        <small class="badge-number badge-light">{{ $headerCartCount }}</small>
                                    @endif
                                    <i class="iconly-Bag-2 icli"></i>
                                </a>
                            </li>
                        </ul>

                        @auth
                            <a href="{{ route('dashboard') }}" class="user-box">
                                <span class="header-icon">
                                    <i class="iconly-Profile icli"></i>
                                </span>
                                <div class="user-name">
                                    <h6 class="text-content">My Account</h6>
                                    <h4 class="mt-1">{{ auth()->user()->name }}</h4>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="user-box">
                                <span class="header-icon">
                                    <i class="iconly-Profile icli"></i>
                                </span>
                                <div class="user-name">
                                    <h6 class="text-content">Hello,</h6>
                                    <h4 class="mt-1">Sign In</h4>
                                </div>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>