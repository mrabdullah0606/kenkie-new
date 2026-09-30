<footer class="section-t-space footer-section-2 footer-color-2">
    <div class="container-fluid-lg">
        <!-- Top Payment Icons Bar -->
        <div class="payment-top-bar text-center py-3 border-bottom border-secondary border-opacity-25 mb-4">
            <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-4 text-white-50 fs-5">
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-paypal text-primary fs-4"></i> <strong class="text-white fs-6">PayPal</strong></span>
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-cc-visa text-info fs-4"></i> <strong class="text-white fs-6">VISA</strong></span>
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-cc-mastercard text-warning fs-4"></i> <strong class="text-white fs-6">Mastercard</strong></span>
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-apple text-white fs-4"></i> <strong class="text-white fs-6">Pay</strong></span>
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-cc-amex text-primary fs-4"></i> <strong class="text-white fs-6">Amex</strong></span>
                <span class="d-inline-flex align-items-center gap-1"><i class="fa-brands fa-google-pay text-white fs-4"></i> <strong class="text-white fs-6">Pay</strong></span>
            </div>
        </div>

        <div class="main-footer">
            <div class="row g-md-4 gy-4 row-cols-xxl-5 row-cols-lg-5 row-cols-md-3 row-cols-sm-2 row-cols-1">
                <!-- Column 1: KENKIE -->
                <div class="col">
                    <div class="footer-title">
                        <h4 class="text-white fw-bold mb-3">KENKIE</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="{{ route('about') }}" class="light-text">About Us</a>
                        </li>
                        <li>
                            <a href="https://www.ebay.co.uk/usr/kenkie-official" target="_blank" rel="noopener noreferrer" class="light-text">Kenkie Ebay</a>
                        </li>
                        <li>
                            <a href="{{ route('privacy.policy') }}" class="light-text">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="https://step4humanity.com/" target="_blank" rel="noopener noreferrer" class="light-text">Charity</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 2: CUSTOMER SERVICE -->
                <div class="col">
                    <div class="footer-title">
                        <h4 class="text-white fw-bold mb-3">CUSTOMER SERVICE</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="{{ route('contact.us') }}" class="light-text">Contact Us</a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}" class="light-text">Payments</a>
                        </li>
                        <li>
                            <a href="{{ auth()->check() ? route('account.index') : route('login') }}" class="light-text">Track My Order</a>
                        </li>
                        <li>
                            <a href="{{ route('return.policy') }}" class="light-text">Returns &amp; Refund Policy</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: PERSONALISE -->
                <div class="col">
                    <div class="footer-title">
                        <h4 class="text-white fw-bold mb-3">PERSONALISE</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('home')) : route('login') }}" class="light-text">My Account</a>
                        </li>
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="light-text">My wishlist</a>
                        </li>
                        <li>
                            <a href="{{ route('cart.index') }}" class="light-text">My Orders</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: TOP LINKS -->
                <div class="col">
                    <div class="footer-title">
                        <h4 class="text-white fw-bold mb-3">TOP LINKS</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="{{ route('shop.category', ['category' => 'sound-vision']) }}" class="light-text">Electronics</a>
                        </li>
                        <li>
                            <a href="{{ route('shop.category', ['category' => 'home-furniture-diy']) }}" class="light-text">Accessories</a>
                        </li>
                        <li>
                            <a href="{{ route('shop.category') }}" class="light-text">New Arrivals</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 5: FOLLOW US -->
                <div class="col">
                    <div class="footer-title">
                        <h4 class="text-white fw-bold mb-3">FOLLOW US</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="light-text d-inline-flex align-items-center gap-2">
                                <i class="fa-brands fa-facebook-f text-white" style="width: 16px;"></i> Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="light-text d-inline-flex align-items-center gap-2">
                                <i class="fa-brands fa-instagram text-white" style="width: 16px;"></i> Instagram
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="light-text d-inline-flex align-items-center gap-2">
                                <i class="fa-brands fa-tiktok text-white" style="width: 16px;"></i> Tiktok
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" class="light-text d-inline-flex align-items-center gap-2">
                                <i class="fa-brands fa-youtube text-danger" style="width: 16px;"></i> Youtube
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sub-footer sub-footer-lite section-b-space section-t-space border-top border-secondary border-opacity-25 mt-4 pt-4 text-center">
            <div class="mb-2">
                <p class="light-text mb-1">
                    <a href="{{ route('privacy.policy') }}" class="light-text text-decoration-none">Privacy Policy</a> |
                    <a href="{{ route('return.policy') }}" class="light-text text-decoration-none">Returns &amp; Refund Policy</a> |
                    <a href="{{ route('about') }}" class="light-text text-decoration-none">About Us</a>
                </p>
            </div>
            <div>
                <p class="light-text mb-0">&copy; KENKIE LTD 2026. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>