<footer class="section-t-space">
        <div class="container-fluid-lg">
            <div class="service-section">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="service-contain">
                            <div class="service-box">
                                <div class="service-image">
                                    <img src="{{ asset('assets/svg/product.svg') }}" class="blur-up lazyload" alt="">
                                </div>

                                <div class="service-detail">
                                    <h5>Every Fresh Products</h5>
                                </div>
                            </div>

                            <div class="service-box">
                                <div class="service-image">
                                    <img src="{{ asset('assets/svg/delivery.svg') }}" class="blur-up lazyload" alt="">
                                </div>

                                <div class="service-detail">
                                    <h5>Free Delivery For Order Over $50</h5>
                                </div>
                            </div>

                            <div class="service-box">
                                <div class="service-image">
                                    <img src="{{ asset('assets/svg/discount.svg') }}" class="blur-up lazyload" alt="">
                                </div>

                                <div class="service-detail">
                                    <h5>Daily Mega Discounts</h5>
                                </div>
                            </div>

                            <div class="service-box">
                                <div class="service-image">
                                    <img src="{{ asset('assets/svg/market.svg') }}" class="blur-up lazyload" alt="">
                                </div>

                                <div class="service-detail">
                                    <h5>Best Price On The Market</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-footer section-b-space section-t-space">
                <div class="row g-md-4 gy-4 row-cols-xxl-5 row-cols-lg-5 row-cols-md-3 row-cols-sm-2 row-cols-1">
                    <!-- Column 1: KENKIE -->
                    <div class="col">
                        <div class="footer-title">
                            <h4 class="fw-bold mb-3">KENKIE</h4>
                        </div>
                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="{{ route('about') }}" class="text-content">About Us</a>
                                </li>
                                <li>
                                    <a href="https://www.ebay.co.uk/usr/kenkie-official" target="_blank" rel="noopener noreferrer" class="text-content">Kenkie Ebay</a>
                                </li>
                                <li>
                                    <a href="{{ route('privacy.policy') }}" class="text-content">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="https://step4humanity.com/" target="_blank" rel="noopener noreferrer" class="text-content">Charity</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 2: CUSTOMER SERVICE -->
                    <div class="col">
                        <div class="footer-title">
                            <h4 class="fw-bold mb-3">CUSTOMER SERVICE</h4>
                        </div>
                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="{{ route('contact.us') }}" class="text-content">Contact Us</a>
                                </li>
                                <li>
                                    <a href="{{ route('about') }}" class="text-content">Payments</a>
                                </li>
                                <li>
                                    <a href="{{ auth()->check() ? route('account.index') : route('login') }}" class="text-content">Track My Order</a>
                                </li>
                                <li>
                                    <a href="{{ route('return.policy') }}" class="text-content">Returns &amp; Refund Policy</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 3: PERSONALISE -->
                    <div class="col">
                        <div class="footer-title">
                            <h4 class="fw-bold mb-3">PERSONALISE</h4>
                        </div>
                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('home')) : route('login') }}" class="text-content">My Account</a>
                                </li>
                                <li>
                                    <a href="{{ route('wishlist.index') }}" class="text-content">My wishlist</a>
                                </li>
                                <li>
                                    <a href="{{ route('cart.index') }}" class="text-content">My Orders</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 4: TOP LINKS -->
                    <div class="col">
                        <div class="footer-title">
                            <h4 class="fw-bold mb-3">TOP LINKS</h4>
                        </div>
                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="{{ route('shop.category', ['category' => 'sound-vision']) }}" class="text-content">Electronics</a>
                                </li>
                                <li>
                                    <a href="{{ route('shop.category', ['category' => 'home-furniture-diy']) }}" class="text-content">Accessories</a>
                                </li>
                                <li>
                                    <a href="{{ route('shop.category') }}" class="text-content">New Arrivals</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 5: FOLLOW US -->
                    <div class="col">
                        <div class="footer-title">
                            <h4 class="fw-bold mb-3">FOLLOW US</h4>
                        </div>
                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="text-content d-inline-flex align-items-center gap-2">
                                        <i class="fa-brands fa-facebook-f" style="width: 16px;"></i> Facebook
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="text-content d-inline-flex align-items-center gap-2">
                                        <i class="fa-brands fa-instagram" style="width: 16px;"></i> Instagram
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="text-content d-inline-flex align-items-center gap-2">
                                        <i class="fa-brands fa-tiktok" style="width: 16px;"></i> Tiktok
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" class="text-content d-inline-flex align-items-center gap-2">
                                        <i class="fa-brands fa-youtube text-danger" style="width: 16px;"></i> Youtube
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sub-footer section-small-space text-center">
                <div class="mb-2">
                    <p class="text-content mb-1">
                        <a href="{{ route('privacy.policy') }}" class="text-content text-decoration-none">Privacy Policy</a> |
                        <a href="{{ route('return.policy') }}" class="text-content text-decoration-none">Returns &amp; Refund Policy</a> |
                        <a href="{{ route('about') }}" class="text-content text-decoration-none">About Us</a>
                    </p>
                </div>
                <div>
                    <p class="text-content mb-0">&copy; KENKIE LTD 2026. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>