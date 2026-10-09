@php
    $mktSettings = \App\Models\MarketingSetting::getSettings();
@endphp

@if ($mktSettings && ($mktSettings->whatsapp_enabled || $mktSettings->chat_assistant_enabled))
<div id="kenkieSupportWidget" class="kenkie-support-wrapper">
    {{-- Main Floating Trigger Button --}}
    <button type="button" class="kenkie-float-btn" id="supportToggleBtn" aria-label="Open Kenkie Customer Support">
        <div class="float-btn-icon">
            <i class="fa-brands fa-whatsapp fs-3"></i>
        </div>
        <span class="pulse-ring"></span>
        <span class="online-indicator"></span>
    </button>

    {{-- Support Chat Box / Drawer --}}
    <div class="kenkie-chat-card" id="supportChatCard">
        {{-- Header --}}
        <div class="kenkie-chat-header">
            <div class="d-flex align-items-center gap-3">
                <div class="support-avatar">
                    <i class="fa-solid fa-headset text-white fs-5"></i>
                    <span class="avatar-online"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-white">{{ $mktSettings->chat_assistant_title ?: 'Kenkie Support' }}</h6>
                    <small class="text-white-50 d-flex align-items-center gap-1" style="font-size:11px;">
                        <span class="status-dot"></span> Typically replies in minutes
                    </small>
                </div>
            </div>
            <button type="button" class="chat-close-btn" id="closeChatBtn" aria-label="Close Chat">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Chat Body --}}
        <div class="kenkie-chat-body">
            {{-- Welcome bubble --}}
            <div class="chat-bubble incoming">
                <p class="mb-0 small">
                    {{ $mktSettings->chat_greeting ?: 'Hello! 👋 Welcome to Kenkie. How can we help you today?' }}
                </p>
                <span class="bubble-time">Just now</span>
            </div>

            {{-- Quick Action Cards --}}
            <div class="quick-actions-grid mt-3 mb-3">
                <span class="quick-action-label">Quick Actions:</span>
                <div class="d-flex flex-column gap-2 mt-1">
                    <a href="{{ route('track-order.show') }}" class="quick-btn">
                        <i class="fa-solid fa-truck-fast text-success"></i>
                        <span>Track My Order (KNK Code)</span>
                    </a>
                    <a href="{{ route('shop.category') }}" class="quick-btn">
                        <i class="fa-solid fa-tags text-warning"></i>
                        <span>Browse Top Deals & Offers</span>
                    </a>
                    <a href="{{ route('return.policy') }}" class="quick-btn">
                        <i class="fa-solid fa-rotate-left text-info"></i>
                        <span>14-Day Returns & Refunds</span>
                    </a>
                </div>
            </div>

            {{-- FAQs Accordion --}}
            @if (!empty($mktSettings->chat_faqs) && is_array($mktSettings->chat_faqs))
                <div class="chat-faqs-section mb-3">
                    <span class="quick-action-label">Frequently Asked:</span>
                    <div class="d-flex flex-column gap-2 mt-1">
                        @foreach ($mktSettings->chat_faqs as $fIndex => $faq)
                            @if (!empty($faq['q']))
                                <div class="chat-faq-card">
                                    <button type="button" class="faq-question-btn" onclick="toggleFaqAnswer({{ $fIndex }})">
                                        <span>{{ $faq['q'] }}</span>
                                        <i class="fa-solid fa-chevron-down faq-arrow" id="faq-arrow-{{ $fIndex }}"></i>
                                    </button>
                                    <div class="faq-answer-text" id="faq-answer-{{ $fIndex }}">
                                        {{ $faq['a'] ?? '' }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- WhatsApp Direct Chat Box --}}
            @if ($mktSettings->whatsapp_enabled)
                <div class="whatsapp-direct-box mt-3 p-3 rounded-3">
                    <span class="d-block fw-bold text-dark small mb-1">
                        <i class="fa-brands fa-whatsapp text-success fs-6 me-1"></i> Chat with Live Agent
                    </span>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm chat-query-input" id="whatsappUserMsg" placeholder="Type your question or query here...">
                    </div>
                    <button type="button" class="btn btn-sm btn-success w-100 fw-bold d-flex align-items-center justify-content-center gap-2 py-2" onclick="launchWhatsApp()">
                        <i class="fa-brands fa-whatsapp fs-5"></i> Start WhatsApp Chat
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .kenkie-support-wrapper {
        position: fixed !important;
        bottom: 24px !important;
        right: 24px !important;
        z-index: 99990 !important;
        font-family: inherit;
    }

    .kenkie-float-btn {
        width: 56px !important;
        height: 56px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%) !important;
        color: #ffffff !important;
        border: none !important;
        outline: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45) !important;
        position: relative !important;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        padding: 0 !important;
    }

    .kenkie-float-btn:hover {
        transform: scale(1.08) !important;
        box-shadow: 0 12px 28px rgba(37, 211, 102, 0.55) !important;
        color: #ffffff !important;
    }

    .kenkie-float-btn .float-btn-icon {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff !important;
    }

    .kenkie-float-btn i {
        font-size: 28px !important;
        color: #ffffff !important;
        line-height: 1 !important;
    }

    /* Stack Back-to-Top gracefully above WhatsApp button */
    .theme-option {
        position: fixed !important;
        bottom: 92px !important;
        right: 28px !important;
        z-index: 99980 !important;
        transition: all 0.3s ease-in-out !important;
    }

    .theme-option .back-to-top {
        margin: 0 !important;
        padding: 0 !important;
        background-color: transparent !important;
    }

    .theme-option .back-to-top a,
    #back-to-top {
        width: 44px !important;
        height: 44px !important;
        border-radius: 50% !important;
        background-color: #22c55e !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        transition: all 0.25s ease !important;
        text-decoration: none !important;
    }

    .theme-option .back-to-top a:hover,
    #back-to-top:hover {
        background-color: #16a34a !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 6px 18px rgba(34, 197, 94, 0.4) !important;
        color: #ffffff !important;
    }

    .theme-option .back-to-top a i,
    #back-to-top i {
        font-size: 16px !important;
        color: #ffffff !important;
        line-height: 1 !important;
    }

    @media (max-width: 768px) {
        .kenkie-support-wrapper {
            bottom: 18px !important;
            right: 18px !important;
        }
        .kenkie-float-btn {
            width: 50px !important;
            height: 50px !important;
        }
        .kenkie-float-btn i {
            font-size: 24px !important;
        }
        .theme-option {
            bottom: 78px !important;
            right: 22px !important;
        }
        .theme-option .back-to-top a,
        #back-to-top {
            width: 40px !important;
            height: 40px !important;
        }
        .theme-option .back-to-top a i,
        #back-to-top i {
            font-size: 14px !important;
        }
    }
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #22c55e;
        border: 2.5px solid #ffffff;
    }
    .pulse-ring {
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border-radius: 50%;
        border: 2px solid #25d366;
        animation: pulseRing 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        opacity: 0;
        pointer-events: none;
    }
    @keyframes pulseRing {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.25); opacity: 0; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    /* Chat Card */
    .kenkie-chat-card {
        position: absolute;
        bottom: 75px;
        right: 0;
        width: 360px;
        max-width: calc(100vw - 32px);
        max-height: 540px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid #e2e8f0;
        opacity: 0;
        pointer-events: none;
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .kenkie-chat-card.open {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }
    .kenkie-chat-header {
        background: linear-gradient(135deg, #15803d, #166534);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .support-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .avatar-online {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #4ade80;
        border: 2px solid #166534;
    }
    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4ade80;
        display: inline-block;
    }
    .chat-close-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #ffffff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }
    .chat-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    .kenkie-chat-body {
        padding: 16px;
        overflow-y: auto;
        flex: 1;
        background: #f8fafc;
    }
    .chat-bubble.incoming {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        border-radius: 14px 14px 14px 2px;
        color: #1e293b;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .bubble-time {
        display: block;
        font-size: 10px;
        color: #94a3b8;
        margin-top: 4px;
        text-align: right;
    }
    .quick-action-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    .quick-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #1e293b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .quick-btn:hover {
        background: #f0fdf4;
        border-color: #86efac;
        color: #15803d;
        transform: translateX(3px);
    }
    .chat-faq-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }
    .faq-question-btn {
        width: 100%;
        text-align: left;
        padding: 9px 12px;
        background: none;
        border: none;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }
    .faq-arrow {
        font-size: 10px;
        color: #94a3b8;
        transition: transform 0.2s ease;
    }
    .faq-arrow.rotated {
        transform: rotate(180deg);
    }
    .faq-answer-text {
        display: none;
        padding: 8px 12px 10px;
        font-size: 12px;
        color: #475569;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        line-height: 1.4;
    }
    .whatsapp-direct-box {
        background: #ffffff;
        border: 1.5px solid #86efac;
    }
    .chat-query-input {
        border-radius: 8px;
        font-size: 12px;
    }
</style>

<script>
(function() {
    const toggleBtn = document.getElementById('supportToggleBtn');
    const chatCard = document.getElementById('supportChatCard');
    const closeBtn = document.getElementById('closeChatBtn');

    if (toggleBtn && chatCard) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            chatCard.classList.toggle('open');
        });
    }

    if (closeBtn && chatCard) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            chatCard.classList.remove('open');
        });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (chatCard && chatCard.classList.contains('open') && !chatCard.contains(e.target) && e.target !== toggleBtn) {
            chatCard.classList.remove('open');
        }
    });

    // Toggle FAQ
    window.toggleFaqAnswer = function(index) {
        const ans = document.getElementById('faq-answer-' + index);
        const arrow = document.getElementById('faq-arrow-' + index);
        if (ans) {
            const isOpen = ans.style.display === 'block';
            ans.style.display = isOpen ? 'none' : 'block';
            if (arrow) {
                arrow.classList.toggle('rotated', !isOpen);
            }
        }
    };

    // Launch WhatsApp
    window.launchWhatsApp = function() {
        const phone = '{{ preg_replace("/[^0-9]/", "", $mktSettings->whatsapp_number) }}';
        const defaultMsg = @json($mktSettings->whatsapp_default_message ?: 'Hello Kenkie Support! I have a question about products.');
        const inputField = document.getElementById('whatsappUserMsg');
        let userMsg = inputField && inputField.value.trim().length > 0 ? inputField.value.trim() : defaultMsg;

        const encodedMsg = encodeURIComponent(userMsg);
        const url = 'https://wa.me/' + phone + '?text=' + encodedMsg;
        window.open(url, '_blank');
    };
})();
</script>
@endif
