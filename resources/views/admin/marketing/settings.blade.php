@extends('admin.layouts.app')

@section('title', 'Marketing, WhatsApp & Pixels Settings')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Marketing, WhatsApp & Pixels</h3>
            <p class="text-muted mb-0">Configure WhatsApp live support, instant assistant FAQs, and analytics tracking tags.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="card mb-4">
        <div class="card-header bg-white pb-0 border-bottom-0">
            <ul class="nav nav-tabs card-header-tabs" id="marketingTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp-pane" type="button" role="tab">
                        <i class="fa-brands fa-whatsapp text-success me-2"></i>WhatsApp & Instant Assistant
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="pixels-tab" data-bs-toggle="tab" data-bs-target="#pixels-pane" type="button" role="tab">
                        <i class="fa-solid fa-chart-pie text-primary me-2"></i>Tracking Pixels (Meta, TikTok, Google)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab">
                        <i class="fa-solid fa-magnifying-glass text-warning me-2"></i>SEO & Search Meta Tags
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body pt-4">
            <div class="tab-content" id="marketingTabContent">
                
                {{-- ── TAB 1: WhatsApp & AI Assistant ── --}}
                <div class="tab-pane fade show active" id="whatsapp-pane" role="tabpanel">
                    <form action="{{ route('admin.marketing.whatsapp') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- WhatsApp Card --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-brands fa-whatsapp fs-5"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Floating WhatsApp Button</h6>
                                                <small class="text-muted">1-click customer chat on WhatsApp</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled" value="1" {{ old('whatsapp_enabled', $settings->whatsapp_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="whatsapp_number" class="form-label fw-semibold">WhatsApp Number (with Country Code) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                                <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="+447123456789" required>
                                            </div>
                                            <small class="text-muted">Format: +44... without spaces or hyphens.</small>
                                            @error('whatsapp_number')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="whatsapp_agent_name" class="form-label fw-semibold">Support Agent / Team Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('whatsapp_agent_name') is-invalid @enderror" id="whatsapp_agent_name" name="whatsapp_agent_name" value="{{ old('whatsapp_agent_name', $settings->whatsapp_agent_name) }}" placeholder="Kenkie Support" required>
                                            @error('whatsapp_agent_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-0">
                                            <label for="whatsapp_default_message" class="form-label fw-semibold">Default Pre-filled Message</label>
                                            <textarea class="form-control @error('whatsapp_default_message') is-invalid @enderror" id="whatsapp_default_message" name="whatsapp_default_message" rows="3" placeholder="Hello Kenkie Support! I would like some assistance with my order / product.">{{ old('whatsapp_default_message', $settings->whatsapp_default_message) }}</textarea>
                                            <small class="text-muted">Pre-typed message when customer clicks WhatsApp.</small>
                                            @error('whatsapp_default_message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- AI & Quick Chatbot Assistant --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-solid fa-robot fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Instant Assistant & FAQ Drawer</h6>
                                                <small class="text-muted">Interactive quick questions & support</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="chat_assistant_enabled" name="chat_assistant_enabled" value="1" {{ old('chat_assistant_enabled', $settings->chat_assistant_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="chat_assistant_title" class="form-label fw-semibold">Assistant Widget Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('chat_assistant_title') is-invalid @enderror" id="chat_assistant_title" name="chat_assistant_title" value="{{ old('chat_assistant_title', $settings->chat_assistant_title) }}" placeholder="Kenkie Quick Assistant" required>
                                            @error('chat_assistant_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="chat_greeting" class="form-label fw-semibold">Welcome Greeting Message</label>
                                            <textarea class="form-control @error('chat_greeting') is-invalid @enderror" id="chat_greeting" name="chat_greeting" rows="2" placeholder="Welcome to Kenkie! 👋 How can we assist you today?">{{ old('chat_greeting', $settings->chat_greeting) }}</textarea>
                                            @error('chat_greeting')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label fw-semibold mb-0">Interactive Quick FAQs</label>
                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="addFaqBtn">
                                                    <i class="fa-solid fa-plus me-1"></i> Add FAQ
                                                </button>
                                            </div>
                                            
                                            <div id="faqsContainer" class="d-flex flex-column gap-2">
                                                @php
                                                    $faqs = old('chat_faqs', $settings->chat_faqs ?? []);
                                                @endphp
                                                @forelse($faqs as $index => $faq)
                                                    <div class="faq-item p-2 border rounded bg-light position-relative">
                                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-faq-btn" aria-label="Remove"></button>
                                                        <input type="text" name="chat_faqs[{{ $index }}][q]" class="form-control form-control-sm mb-1 fw-bold bg-white" placeholder="Question (e.g. 📦 How do I track?)" value="{{ $faq['q'] ?? '' }}">
                                                        <textarea name="chat_faqs[{{ $index }}][a]" class="form-control form-control-sm bg-white" rows="2" placeholder="Instant Answer...">{{ $faq['a'] ?? '' }}</textarea>
                                                    </div>
                                                @empty
                                                    <div class="faq-item p-2 border rounded bg-light position-relative">
                                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-faq-btn" aria-label="Remove"></button>
                                                        <input type="text" name="chat_faqs[0][q]" class="form-control form-control-sm mb-1 fw-bold bg-white" placeholder="Question" value="📦 How do I track my order?">
                                                        <textarea name="chat_faqs[0][a]" class="form-control form-control-sm bg-white" rows="2" placeholder="Instant Answer...">Visit our Track Order page and enter your KNK order code.</textarea>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save WhatsApp & Assistant Settings
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── TAB 2: Tracking & Pixels ── --}}
                <div class="tab-pane fade" id="pixels-pane" role="tabpanel">
                    <form action="{{ route('admin.marketing.pixels') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Meta (Facebook) Pixel --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-brands fa-facebook-f fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Meta (Facebook) Pixel</h6>
                                                <small class="text-muted">PageView, ViewContent & Purchases</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="meta_pixel_enabled" name="meta_pixel_enabled" value="1" {{ old('meta_pixel_enabled', $settings->meta_pixel_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0">
                                            <label for="meta_pixel_id" class="form-label fw-semibold">Meta Pixel ID</label>
                                            <input type="text" class="form-control font-monospace" id="meta_pixel_id" name="meta_pixel_id" value="{{ old('meta_pixel_id', $settings->meta_pixel_id) }}" placeholder="e.g. 123456789012345">
                                            <small class="text-muted">Found in Meta Events Manager > Data Sources.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TikTok Pixel --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-brands fa-tiktok fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">TikTok Pixel</h6>
                                                <small class="text-muted">Ad conversion and event tracking</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="tiktok_pixel_enabled" name="tiktok_pixel_enabled" value="1" {{ old('tiktok_pixel_enabled', $settings->tiktok_pixel_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0">
                                            <label for="tiktok_pixel_id" class="form-label fw-semibold">TikTok Pixel ID</label>
                                            <input type="text" class="form-control font-monospace" id="tiktok_pixel_id" name="tiktok_pixel_id" value="{{ old('tiktok_pixel_id', $settings->tiktok_pixel_id) }}" placeholder="e.g. C1234567890ABC">
                                            <small class="text-muted">Found in TikTok Ads Manager > Assets > Events.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Google Analytics (GA4) --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-brands fa-google fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Google Analytics (GA4)</h6>
                                                <small class="text-muted">GA4 measurement tracking tag</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="google_analytics_enabled" name="google_analytics_enabled" value="1" {{ old('google_analytics_enabled', $settings->google_analytics_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0">
                                            <label for="google_analytics_id" class="form-label fw-semibold">GA4 Measurement ID</label>
                                            <input type="text" class="form-control font-monospace" id="google_analytics_id" name="google_analytics_id" value="{{ old('google_analytics_id', $settings->google_analytics_id) }}" placeholder="e.g. G-XXXXXXXXXX">
                                            <small class="text-muted">Found in Google Analytics > Admin > Data Streams.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Google Ads Conversion --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-solid fa-bullseye fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Google Ads Tracking</h6>
                                                <small class="text-muted">Conversion and remarketing tag</small>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="google_ads_enabled" name="google_ads_enabled" value="1" {{ old('google_ads_enabled', $settings->google_ads_enabled) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0">
                                            <label for="google_ads_id" class="form-label fw-semibold">Google Ads Conversion ID</label>
                                            <input type="text" class="form-control font-monospace" id="google_ads_id" name="google_ads_id" value="{{ old('google_ads_id', $settings->google_ads_id) }}" placeholder="e.g. AW-123456789">
                                            <small class="text-muted">Found in Google Ads > Tools & Settings > Conversions.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save Tracking & Pixels
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── TAB 3: Global SEO & Meta Tags ── --}}
                <div class="tab-pane fade" id="seo-pane" role="tabpanel">
                    <form action="{{ route('admin.marketing.seo') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-solid fa-globe fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Global SEO Metadata</h6>
                                                <small class="text-muted">Titles and descriptions for Google & search engines</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label fw-semibold">Global Meta Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $settings->meta_title) }}" placeholder="KENKIE – Online Shopping Store | Home of the Future Gadgets." required>
                                            <small class="text-muted">Recommended length: 50–60 characters.</small>
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label fw-semibold">Global Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3" placeholder="KENKIE - Delivers all over UK | Deals in electronic devices, mobile accessories, top-notch handy gadgets and many more.">{{ old('meta_description', $settings->meta_description) }}</textarea>
                                            <small class="text-muted">Recommended length: 150–160 characters.</small>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-0">
                                            <label for="meta_keywords" class="form-label fw-semibold">Meta Keywords</label>
                                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $settings->meta_keywords) }}" placeholder="KENKIE, online shopping store, electronics, gadgets, UK delivery, mobile accessories">
                                            <small class="text-muted">Comma-separated keyword phrases.</small>
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="card h-100 border">
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                                <i class="fa-brands fa-google fs-6"></i>
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">Google Site Verification</h6>
                                                <small class="text-muted">Google Search Console verification code</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="google_site_verification" class="form-label fw-semibold">Google Verification Code</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                                <input type="text" class="form-control @error('google_site_verification') is-invalid @enderror" id="google_site_verification" name="google_site_verification" value="{{ old('google_site_verification', $settings->google_site_verification) }}" placeholder="-dGzkf4mqQ32aE6zvmSo35tzlFXXwWPjpE9YBF6jpxg">
                                            </div>
                                            <small class="text-muted">Value from <code>&lt;meta name="google-site-verification" content="..."&gt;</code></small>
                                            @error('google_site_verification')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="p-3 bg-light rounded border">
                                            <h6 class="fw-bold mb-2 small text-dark"><i class="fa-solid fa-circle-info text-primary me-1"></i> Live Head Tags Generated:</h6>
                                            <ul class="small text-muted mb-0 ps-3 d-flex flex-column gap-1">
                                                <li>Open Graph (Facebook, WhatsApp previews)</li>
                                                <li>Twitter Summary Large Image cards</li>
                                                <li>Yoast compatible JSON-LD Search Schema</li>
                                                <li>Robots & index metadata</li>
                                                <li>Dynamic canonical URLs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning fw-bold text-dark d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save SEO Meta Tags
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('faqsContainer');
        const addBtn = document.getElementById('addFaqBtn');

        if (addBtn && container) {
            addBtn.addEventListener('click', function () {
                const count = container.querySelectorAll('.faq-item').length;
                const div = document.createElement('div');
                div.className = 'faq-item p-2 border rounded bg-light position-relative';
                div.innerHTML = `
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-faq-btn" aria-label="Remove"></button>
                    <input type="text" name="chat_faqs[${count}][q]" class="form-control form-control-sm mb-1 fw-bold bg-white" placeholder="Question (e.g. 💳 Payment Methods)">
                    <textarea name="chat_faqs[${count}][a]" class="form-control form-control-sm bg-white" rows="2" placeholder="Instant Answer..."></textarea>
                `;
                container.appendChild(div);
            });

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-faq-btn')) {
                    const item = e.target.closest('.faq-item');
                    if (item) item.remove();
                }
            });
        }
    });
</script>
@endpush
