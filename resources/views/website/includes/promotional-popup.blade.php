@php
    $currentPage = 'all';
    if (request()->routeIs('home')) {
        $currentPage = 'home';
    } elseif (request()->routeIs('shop.category')) {
        $currentPage = 'shop';
    } elseif (request()->routeIs('products.show')) {
        $currentPage = 'product';
    }

    $activePopup = \App\Models\PromotionalPopup::query()
        ->active()
        ->forPage($currentPage)
        ->latest()
        ->first();
@endphp

@if ($activePopup)
<div id="promotionalPopupModal" class="promo-modal-backdrop" style="display: none;">
    <div class="promo-modal-dialog">
        <div class="promo-modal-content">
            <button type="button" class="promo-close-btn" id="promoCloseBtn" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="row g-0 align-items-center">
                @if ($activePopup->image)
                    <div class="col-md-5 d-none d-md-block promo-img-col">
                        <img src="{{ asset($activePopup->image) }}" alt="{{ $activePopup->title }}" class="img-fluid promo-modal-img">
                    </div>
                @endif

                <div class="{{ $activePopup->image ? 'col-md-7' : 'col-12' }}">
                    <div class="promo-body p-4 p-md-5 text-center">
                        <div class="promo-badge mb-2">
                            <i class="fa-solid fa-gift me-1"></i> Special Offer
                        </div>

                        <h3 class="fw-bold text-dark mb-2 promo-title">{{ $activePopup->title }}</h3>

                        @if ($activePopup->subtitle)
                            <h6 class="text-success fw-bold mb-3">{{ $activePopup->subtitle }}</h6>
                        @endif

                        @if ($activePopup->content)
                            <p class="text-muted small mb-4">{{ $activePopup->content }}</p>
                        @endif

                        @if ($activePopup->discount_code)
                            <div class="promo-coupon-box mb-4">
                                <span class="promo-coupon-label">Use Promo Code</span>
                                <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                                    <span class="promo-coupon-code" id="promoCodeText">{{ $activePopup->discount_code }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-success btn-copy-coupon" id="copyCouponBtn" onclick="copyPromoCode('{{ $activePopup->discount_code }}')">
                                        <i class="fa-regular fa-copy me-1" id="copyIcon"></i> <span id="copyBtnText">Copy</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ url($activePopup->button_url) }}" class="btn btn-theme py-2 fw-bold text-white shadow-sm" style="background:#15803d; border-color:#15803d; border-radius:10px;">
                                {{ $activePopup->button_text }} <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none" id="promoDismissToday">
                                Don't show this deal again today
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .promo-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        transition: opacity 0.35s ease;
    }
    .promo-modal-backdrop.show {
        opacity: 1;
    }
    .promo-modal-dialog {
        max-width: 720px;
        width: 100%;
        position: relative;
        transform: scale(0.92);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .promo-modal-backdrop.show .promo-modal-dialog {
        transform: scale(1);
    }
    .promo-modal-content {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
    }
    .promo-close-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
    }
    .promo-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: rotate(90deg);
    }
    .promo-img-col {
        background: #f8fafc;
        height: 100%;
        min-height: 350px;
    }
    .promo-modal-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .promo-badge {
        display: inline-block;
        background: #fef08a;
        color: #854d0e;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .promo-title {
        font-size: 1.5rem;
        line-height: 1.3;
    }
    .promo-coupon-box {
        background: #f0fdf4;
        border: 2px dashed #86efac;
        border-radius: 12px;
        padding: 12px 16px;
    }
    .promo-coupon-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #166534;
        font-weight: 700;
    }
    .promo-coupon-code {
        font-family: monospace;
        font-weight: 800;
        font-size: 1.25rem;
        color: #15803d;
        letter-spacing: 1px;
    }
    .btn-copy-coupon {
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
    }
</style>

<script>
(function() {
    const popupId = {{ $activePopup->id }};
    const delaySec = {{ $activePopup->delay_seconds }};
    const storageKey = 'kenkie_popup_dismissed_' + popupId;
    const dismissedUntil = localStorage.getItem(storageKey);

    // Check if dismissed within the last 24 hours
    if (dismissedUntil && parseInt(dismissedUntil, 10) > Date.now()) {
        return;
    }

    const modal = document.getElementById('promotionalPopupModal');
    const closeBtn = document.getElementById('promoCloseBtn');
    const dismissTodayBtn = document.getElementById('promoDismissToday');

    function showPopup() {
        if (!modal) return;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 30);
    }

    function hidePopup(rememberDays = 0) {
        if (!modal) return;
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 350);

        if (rememberDays > 0) {
            const expireTime = Date.now() + (rememberDays * 24 * 60 * 60 * 1000);
            localStorage.setItem(storageKey, expireTime.toString());
        } else {
            // Dismiss for current browser session (1 hour)
            const expireTime = Date.now() + (1 * 60 * 60 * 1000);
            localStorage.setItem(storageKey, expireTime.toString());
        }
    }

    setTimeout(showPopup, delaySec * 1000);

    if (closeBtn) {
        closeBtn.addEventListener('click', () => hidePopup(1));
    }
    if (dismissTodayBtn) {
        dismissTodayBtn.addEventListener('click', () => hidePopup(1));
    }

    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hidePopup(1);
        }
    });

    // Copy Promo Code function
    window.copyPromoCode = function(code) {
        navigator.clipboard.writeText(code).then(() => {
            const textSpan = document.getElementById('copyBtnText');
            const icon = document.getElementById('copyIcon');
            if (textSpan) textSpan.innerText = 'Copied!';
            if (icon) {
                icon.className = 'fa-solid fa-check me-1';
            }
            setTimeout(() => {
                if (textSpan) textSpan.innerText = 'Copy';
                if (icon) icon.className = 'fa-regular fa-copy me-1';
            }, 2500);
        });
    };
})();
</script>
@endif
