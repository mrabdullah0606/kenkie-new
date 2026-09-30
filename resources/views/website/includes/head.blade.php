<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Fastkart">
<meta name="keywords" content="Fastkart">
<meta name="author" content="Fastkart">
<link rel="icon" href="@yield('favicon', asset('assets/images/favicon/5.png'))" type="image/x-icon">
<title>@yield('title', config('app.name'))</title>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

<link id="rtl-link" rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/font-awesome.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/ion.rangeSlider.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendors/slick/slick-theme.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bulk-style.css') }}">
<link id="color-link" rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<style>
:root,
body,
.theme-color-1,
.theme-color-2,
.theme-color-3,
.theme-color-4,
.theme-color-5 {
    --theme-color: #22c55e !important;
    --theme-color-rgb: 34, 197, 94 !important;
    --theme-color-hover: #16a34a !important;
}
.theme-color,
.text-theme,
a.theme-color,
.span-name,
.theme-color-3 .theme-color {
    color: #22c55e !important;
}
.theme-bg-color,
.btn-theme,
.bg-theme,
.btn-animation,
.theme-bg,
.ok-button,
.btn-apply,
.proceed-btn,
.search-button,
.search-box button {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
    color: #ffffff !important;
}

/* Modern Clean Header Styling (Crisp White with Green Accents) */
.header-3 .top-nav,
.header-2 .top-nav,
header.header-3 .sticky-header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #eef2f6 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
}
.header-3 .web-logo img,
.header-2 .web-logo img {
    height: 42px !important;
    max-height: 42px !important;
    width: auto !important;
    object-fit: contain !important;
}
.header-3 .searchbar-box-2 {
    display: flex !important;
    align-items: center !important;
    height: 48px !important;
    border: 2px solid #22c55e !important;
    border-radius: 50px !important;
    background: #ffffff !important;
    padding: 4px 4px 4px 16px !important;
    box-shadow: 0 2px 10px rgba(34, 197, 94, 0.08) !important;
    transition: all 0.25s ease !important;
    width: 100% !important;
    overflow: hidden !important;
}
.header-3 .searchbar-box-2:focus-within {
    border-color: #16a34a !important;
    box-shadow: 0 4px 16px rgba(34, 197, 94, 0.16) !important;
}
.header-3 .searchbar-box-2 .search-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: none !important;
    background: transparent !important;
    color: #22c55e !important;
    padding: 0 8px 0 0 !important;
    font-size: 18px !important;
    cursor: pointer !important;
    box-shadow: none !important;
}
.header-3 .searchbar-box-2 input.form-control,
.header-3 .searchbar-box-2 input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    font-size: 14px !important;
    color: #0f172a !important;
    padding: 0 10px !important;
    height: 100% !important;
    flex: 1 1 auto !important;
}
.header-3 .searchbar-box-2 input:focus {
    outline: none !important;
    box-shadow: none !important;
}
.header-3 .searchbar-box-2 input::placeholder {
    color: #94a3b8 !important;
    font-size: 14px !important;
}
.header-3 .searchbar-box-2 .search-button {
    background-color: #22c55e !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 50px !important;
    padding: 0 24px !important;
    height: 38px !important;
    min-height: 38px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    letter-spacing: 0.2px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    box-shadow: 0 2px 6px rgba(34, 197, 94, 0.25) !important;
    transition: all 0.2s ease !important;
    flex-shrink: 0 !important;
}
.header-3 .searchbar-box-2 .search-button:hover {
    background-color: #16a34a !important;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35) !important;
    color: #ffffff !important;
}
/* Bank & Wallet Offers Equal Sizing & Alignment */
.bank-section {
    padding: 30px 0 !important;
}
.slider-bank-3 .slick-track {
    display: flex !important;
}
.slider-bank-3 .slick-slide {
    height: auto !important;
    display: flex !important;
    padding: 0 10px !important;
}
.slider-bank-3 .slick-slide > div {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    height: 100% !important;
}
.bank-offer {
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    height: 100% !important;
    min-height: 230px !important;
    background: #f8fafc !important;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    overflow: hidden !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease !important;
}
.bank-offer:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}
.bank-offer .bank-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 22px 24px !important;
    flex: 1 1 auto !important;
    gap: 16px !important;
    background: #f8fafc !important;
}
.bank-offer .bank-header .bank-left {
    flex: 1 1 auto !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
}
.bank-offer .bank-header .bank-left .bank-image {
    margin-bottom: 12px !important;
}
.bank-offer .bank-header .bank-left .bank-image img {
    max-height: 26px !important;
    width: auto !important;
    object-fit: contain !important;
}
.bank-offer .bank-header .bank-left .bank-name h2 {
    font-size: 20px !important;
    font-weight: 800 !important;
    margin-bottom: 4px !important;
    line-height: 1.2 !important;
    color: #0f172a !important;
}
.bank-offer .bank-header .bank-left .bank-name .discount {
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    margin-bottom: 3px !important;
}
.bank-offer .bank-header .bank-left .bank-name .valid {
    font-size: 12px !important;
    color: #94a3b8 !important;
    margin-bottom: 0 !important;
}
.bank-offer .bank-header .bank-right {
    flex: 0 0 130px !important;
    width: 130px !important;
    max-width: 130px !important;
    height: 110px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.bank-offer .bank-header .bank-right img {
    max-height: 100px !important;
    max-width: 100% !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
}
.bank-offer .bank-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 12px 20px !important;
    width: 100% !important;
    margin-top: auto !important;
    min-height: 50px !important;
}
.bank-offer .bank-footer.bank-footer-1 {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
}
.bank-offer .bank-footer.bank-footer-2 {
    background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
}
.bank-offer .bank-footer.bank-footer-3 {
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
}
.bank-offer .bank-footer h4 {
    margin: 0 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
}
.bank-offer .bank-footer h4 input {
    background: transparent !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    letter-spacing: 0.5px !important;
    width: 90px !important;
    outline: none !important;
    padding: 0 !important;
    cursor: default !important;
}
.bank-offer .bank-footer .bank-coupon {
    background: rgba(255, 255, 255, 0.22) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.45) !important;
    border-radius: 6px !important;
    padding: 5px 14px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    backdrop-filter: blur(4px) !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}
.bank-offer .bank-footer .bank-coupon:hover {
    background: #ffffff !important;
    color: #0f172a !important;
    border-color: #ffffff !important;
}
.header-3 .support-box .support-number h2 {
    color: #0f172a !important;
    font-size: 16px !important;
    font-weight: 700 !important;
}
.header-3 .support-box .support-number h4 {
    color: #64748b !important;
    font-size: 12px !important;
}
.header-3 .main-nav {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f1f5f9 !important;
}
.header-3 .navbar-nav .nav-link {
    color: #1e293b !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    padding: 14px 16px !important;
    transition: color 0.2s ease !important;
}
.header-3 .navbar-nav .nav-link:hover,
.header-3 .navbar-nav .nav-link.active {
    color: #22c55e !important;
}
/* Remove dropdown chevron icon from non-dropdown items (Home) */
.header-3 .navbar-nav .nav-item:not(.dropdown) .nav-link::after,
.header-3 .navbar-nav .nav-item:not(.dropdown) .nav-link::before,
.header-3 .navbar-nav .nav-item:not(.dropdown)::after,
.header-3 .navbar-nav .nav-item:not(.dropdown)::before,
.header-3 .navbar-nav .nav-link-single::after,
.header-3 .navbar-nav .nav-link-single::before,
.header-2 .navbar-nav .nav-item:not(.dropdown) .nav-link::after,
.header-2 .navbar-nav .nav-item:not(.dropdown) .nav-link::before,
.main-nav .navbar-nav .nav-item:not(.dropdown) .nav-link::after,
.main-nav .navbar-nav .nav-item:not(.dropdown) .nav-link::before,
.main-nav .navbar-nav .nav-link-single::after,
.main-nav .navbar-nav .nav-link-single::before,
.navbar-nav .nav-item:not(.dropdown) .nav-link::after,
.navbar-nav .nav-item:not(.dropdown) .nav-link::before,
.navbar-nav .nav-link:not(.dropdown-toggle)::after,
.navbar-nav .nav-link:not(.dropdown-toggle)::before,
.navbar-nav .nav-link-single::after,
.navbar-nav .nav-link-single::before {
    display: none !important;
    content: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}
.header-3 .option-list-2 li a.header-icon,
.header-3 .user-box .header-icon {
    color: #334155 !important;
}
.header-3 .option-list-2 li a.header-icon:hover {
    color: #22c55e !important;
}
.header-3 .badge-number {
    background-color: #22c55e !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 50% !important;
    font-size: 11px !important;
}
.header-3 .user-box .user-name h6 {
    color: #64748b !important;
    font-size: 12px !important;
}
.header-3 .user-box .user-name h4 {
    color: #0f172a !important;
    font-size: 14px !important;
    font-weight: 600 !important;
}
.theme-bg-color:hover,
.btn-theme:hover,
.btn-animation:hover,
.btn-apply:hover,
.proceed-btn:hover,
.ok-button:hover,
.search-button:hover,
.search-box button:hover {
    background-color: #16a34a !important;
    border-color: #16a34a !important;
    color: #ffffff !important;
}
.btn-theme-outline {
    border-color: #22c55e !important;
    color: #22c55e !important;
}
.btn-theme-outline:hover {
    background-color: #22c55e !important;
    color: #ffffff !important;
}

/* Product Detail & Modal Action Buttons (Wishlist, Continue Shopping, Details) */
.buy-box .btn,
.buy-box button,
.buy-box a,
.modal-button .btn-outline-secondary {
    border: 1.5px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    color: #334155 !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    padding: 10px 22px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    transition: all 0.25s ease !important;
}
.buy-box .btn:hover,
.buy-box button:hover,
.buy-box a:hover,
.modal-button .btn-outline-secondary:hover {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3) !important;
    transform: translateY(-2px) !important;
}
.buy-box .btn:hover i,
.buy-box .btn:hover svg,
.buy-box button:hover i,
.buy-box button:hover svg,
.buy-box a:hover i,
.buy-box a:hover svg,
.modal-button .btn-outline-secondary:hover i,
.modal-button .btn-outline-secondary:hover svg {
    color: #ffffff !important;
    stroke: #ffffff !important;
}
/* Add-to-cart & Quantity Stepper State Management */
.add-to-cart-box {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 38px !important;
    width: 100% !important;
}
.add-to-cart-box .btn-add-cart,
.add-to-cart-box .addcart-button {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 50px !important;
    padding: 6px 16px !important;
    color: #1e293b !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    transition: all 0.2s ease !important;
}
.add-to-cart-box .btn-add-cart:hover,
.add-to-cart-box .addcart-button:hover {
    border-color: #22c55e !important;
    background: #f0fdf4 !important;
    color: #15803d !important;
}
.add-to-cart-box .btn-add-cart .add-icon,
.add-to-cart-box .addcart-button .add-icon {
    width: 28px !important;
    height: 28px !important;
    background-color: #22c55e !important;
    color: #ffffff !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 12px !important;
    box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2) !important;
}
.add-to-cart-box .cart_qty {
    display: none !important;
    width: 100% !important;
    align-items: center !important;
    justify-content: center !important;
}
.add-to-cart-box .cart_qty.open {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.add-to-cart-box:has(.cart_qty.open) .btn-add-cart,
.add-to-cart-box:has(.cart_qty.open) .addcart-button {
    display: none !important;
}
.cart_qty .input-group {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-wrap: nowrap !important;
    gap: 4px !important;
    width: auto !important;
}
.cart_qty .btn.qty-left-minus,
.cart_qty .btn.qty-right-plus,
.qty-box .btn.qty-left-minus,
.qty-box .btn.qty-right-plus {
    background-color: #22c55e !important;
    color: #ffffff !important;
    width: 28px !important;
    min-width: 28px !important;
    max-width: 28px !important;
    height: 28px !important;
    border-radius: 50% !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: none !important;
    cursor: pointer !important;
    transition: background-color 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2) !important;
}
.cart_qty .btn.qty-left-minus:hover,
.cart_qty .btn.qty-right-plus:hover,
.qty-box .btn.qty-left-minus:hover,
.qty-box .btn.qty-right-plus:hover {
    background-color: #16a34a !important;
    color: #ffffff !important;
}
.cart_qty .btn i,
.qty-box .btn i,
.cart_qty .btn .fa-solid,
.qty-box .btn .fa-solid {
    color: #ffffff !important;
    font-size: 11px !important;
    line-height: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.cart_qty .qty-input,
.qty-box .qty-input {
    width: 36px !important;
    min-width: 36px !important;
    height: 28px !important;
    padding: 0 !important;
    text-align: center !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
}
.custom-form-check .form-check-input:checked {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
}
.pagination .page-item.active .page-link {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
    color: #ffffff !important;
}
.pagination .page-link:hover {
    color: #22c55e !important;
}
.back-to-top a {
    background-color: #22c55e !important;
}
.back-to-top a:hover {
    background-color: #16a34a !important;
}
.fullpage-loader--invisible {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}
/* Product Option Hover Action Bar (Centered in Image) */
.product-box-3 .product-header .product-image,
.product-box-4 .product-image,
.product-image {
    position: relative !important;
}
.product-box-3 .product-header .product-image .product-option,
.product-box-4 .product-image .option,
.product-option {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) scale(0.85) !important;
    bottom: auto !important;
    margin: 0 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    background-color: #ffffff !important;
    border-radius: 8px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    overflow: hidden !important;
    border: 1px solid #e2e8f0 !important;
    z-index: 10 !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    pointer-events: none !important;
}
.product-box-3:hover .product-header .product-image .product-option,
.product-box-4:hover .product-image .option,
.product-box:hover .product-option,
.product-image:hover .product-option {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translate(-50%, -50%) scale(1) !important;
    pointer-events: auto !important;
}
.product-box-3 .product-header .product-image .product-option li,
.product-box-4 .product-image .option li,
.product-option li {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 38px !important;
    height: 36px !important;
    padding: 0 !important;
    margin: 0 !important;
    position: relative !important;
    list-style: none !important;
    float: none !important;
}
.product-box-3 .product-header .product-image .product-option li + li,
.product-box-4 .product-image .option li + li,
.product-option li + li {
    border-left: 1px solid #eef0f2 !important;
    margin-left: 0 !important;
}
.product-box-3 .product-header .product-image .product-option li + li::before,
.product-box-4 .product-image .option li + li::before,
.product-option li + li::before {
    display: none !important;
}
.product-box-3 .product-header .product-image .product-option li a,
.product-box-3 .product-header .product-image .product-option li button,
.product-box-3 .product-header .product-image .product-option li form,
.product-option li a,
.product-option li button,
.product-option li form {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    background: transparent !important;
    color: #4a5568 !important;
    text-decoration: none !important;
    cursor: pointer !important;
}
.product-option li a:hover,
.product-option li button:hover {
    color: #22c55e !important;
}
.product-option li a i,
.product-option li button i,
.product-option li a svg,
.product-option li button svg {
    width: 16px !important;
    height: 16px !important;
    stroke-width: 2 !important;
}
.cookie-accepted .cookie-bar-box,
.cookie-bar-box.hide {
    display: none !important;
}

/* Dynamic Live Search Dropdown Styling */
.live-search-container {
    position: relative !important;
}
.live-search-dropdown {
    position: absolute !important;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12), 0 4px 12px rgba(15, 23, 42, 0.06) !important;
    z-index: 99999 !important;
    overflow: hidden !important;
    max-height: 480px !important;
    display: flex !important;
    flex-direction: column !important;
    animation: liveSearchFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
@keyframes liveSearchFadeIn {
    from {
        opacity: 0;
        transform: translateY(-6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.live-search-dropdown .live-search-list {
    list-style: none !important;
    padding: 6px 0 !important;
    margin: 0 !important;
    overflow-y: auto !important;
    max-height: 380px !important;
}
.live-search-dropdown .live-search-item {
    display: flex !important;
    align-items: center !important;
    padding: 10px 18px !important;
    gap: 14px !important;
    text-decoration: none !important;
    color: inherit !important;
    transition: background 0.15s ease !important;
    border-bottom: 1px solid #f8fafc !important;
}
.live-search-dropdown .live-search-item:last-child {
    border-bottom: none !important;
}
.live-search-dropdown .live-search-item:hover,
.live-search-dropdown .live-search-item.active {
    background-color: #f0fdf4 !important;
}
.live-search-dropdown .live-search-thumb {
    width: 48px !important;
    height: 48px !important;
    min-width: 48px !important;
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
    object-fit: contain !important;
    background-color: #ffffff !important;
    padding: 2px !important;
}
.live-search-dropdown .live-search-info {
    flex: 1 1 auto !important;
    min-width: 0 !important;
}
.live-search-dropdown .live-search-name {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    margin: 0 0 3px 0 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.live-search-dropdown .live-search-meta {
    font-size: 12px !important;
    color: #64748b !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}
.live-search-dropdown .live-search-cat {
    background: #f1f5f9 !important;
    padding: 2px 8px !important;
    border-radius: 4px !important;
    font-size: 11px !important;
    color: #475569 !important;
    font-weight: 500 !important;
}
.live-search-dropdown .live-search-price-wrap {
    text-align: right !important;
    flex-shrink: 0 !important;
}
.live-search-dropdown .live-search-price {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #22c55e !important;
    margin: 0 !important;
}
.live-search-dropdown .live-search-stock {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #16a34a !important;
}
.live-search-dropdown .live-search-stock.out-of-stock {
    color: #ef4444 !important;
}
.live-search-dropdown .live-search-footer {
    padding: 10px 18px !important;
    background: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    font-size: 13px !important;
}
.live-search-dropdown .live-search-footer a {
    color: #22c55e !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    transition: color 0.15s ease !important;
}
.live-search-dropdown .live-search-footer a:hover {
    color: #16a34a !important;
    text-decoration: underline !important;
}
.live-search-dropdown .live-search-state {
    padding: 24px 20px !important;
    text-align: center !important;
    color: #64748b !important;
    font-size: 14px !important;
}
.live-search-dropdown .live-search-state i {
    font-size: 22px !important;
    margin-bottom: 6px !important;
    display: block !important;
    color: #94a3b8 !important;
}
.live-search-dropdown--mobile {
    top: 100% !important;
    position: absolute !important;
    width: 100% !important;
    border-radius: 0 0 14px 14px !important;
}
</style>
<script>
(function() {
    if (localStorage.getItem('cookie_accepted') === 'true') {
        document.documentElement.classList.add('cookie-accepted');
    }
    function hideLoader() {
        var loader = document.querySelector('.fullpage-loader');
        if (loader) {
            loader.classList.add('fullpage-loader--invisible');
            loader.style.display = 'none';
        }
    }
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(hideLoader, 200);
    } else {
        window.addEventListener('DOMContentLoaded', function() { setTimeout(hideLoader, 200); });
        window.addEventListener('load', function() { setTimeout(hideLoader, 100); });
    }
    setTimeout(hideLoader, 1500);
})();
</script>