@extends('admin.layouts.app')

@section('title', 'Payment Integration & Gateway Settings')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-8">
            <h3 class="fw-bold mb-1">
                <i class="fa-solid fa-credit-card text-primary me-2"></i>Payment Gateways &amp; Integration
            </h3>
            <p class="text-muted mb-0">Manage Stripe API keys, environment modes, and Cash On Delivery settings stored securely in your database.</p>
        </div>
        <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                <div>{{ session('status') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info fs-5 me-2"></i>
                <div>{{ session('info') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="fw-bold mb-1"><i class="fa-solid fa-circle-xmark me-2"></i>Please fix the following validation errors:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left Column: Main Gateway Configuration Forms --}}
        <div class="col-xl-8">

            {{-- 1. STRIPE GATEWAY CARD --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-2 rounded bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-brands fa-stripe-s fs-5"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Stripe Payment Gateway</h5>
                            <small class="text-muted">Debit/Credit cards, Apple Pay, Google Pay</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($settings->stripe_enabled)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="fa-solid fa-circle-check me-1"></i> Enabled
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                            </span>
                        @endif

                        @if($settings->stripe_mode === 'live')
                            <span class="badge bg-danger text-white border border-danger px-2.5 py-1">
                                <i class="fa-solid fa-bolt me-1"></i> LIVE MODE
                            </span>
                        @else
                            <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold">
                                <i class="fa-solid fa-flask-vial me-1"></i> TEST MODE
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.payments.stripe') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Toggle Enable / Disable --}}
                        <div class="p-3 mb-4 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <label class="form-check-label fw-bold text-dark fs-6 d-block mb-1" for="stripe_enabled">
                                    Enable Stripe Payments on Checkout
                                </label>
                                <span class="text-muted small">When active, customers can pay securely via Credit/Debit card during checkout.</span>
                            </div>
                            <div class="form-check form-switch form-switch-lg m-0">
                                <input class="form-check-input" type="checkbox" name="stripe_enabled" id="stripe_enabled" value="1" {{ $settings->stripe_enabled ? 'checked' : '' }} style="cursor: pointer; width: 3em; height: 1.6em;">
                            </div>
                        </div>

                        {{-- Environment Mode Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Environment Mode <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 position-relative mode-card {{ $settings->stripe_mode === 'test' ? 'border-primary bg-primary-subtle' : '' }}" style="cursor: pointer;" onclick="document.getElementById('mode_test').checked = true;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stripe_mode" id="mode_test" value="test" {{ $settings->stripe_mode === 'test' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark ms-2" for="mode_test" style="cursor: pointer;">
                                                <i class="fa-solid fa-flask-vial text-warning me-1"></i> Test Sandbox Mode
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2 ps-4">
                                            Use Stripe test keys (<code>pk_test_...</code> / <code>sk_test_...</code>) for simulating card payments without real money.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 position-relative mode-card {{ $settings->stripe_mode === 'live' ? 'border-danger bg-danger-subtle' : '' }}" style="cursor: pointer;" onclick="document.getElementById('mode_live').checked = true;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stripe_mode" id="mode_live" value="live" {{ $settings->stripe_mode === 'live' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark ms-2" for="mode_live" style="cursor: pointer;">
                                                <i class="fa-solid fa-fire-flame-curved text-danger me-1"></i> Live Production Mode
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2 ps-4">
                                            Use Stripe live keys (<code>pk_live_...</code> / <code>sk_live_...</code>) to process real customer card payments.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Publishable Key --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="stripe_key" class="form-label fw-bold text-dark mb-0">
                                    Stripe Publishable Key
                                </label>
                                @if(!empty($settings->stripe_key))
                                    <span class="badge bg-success-subtle text-success small">DB Stored</span>
                                @elseif(!empty(config('services.stripe.key')))
                                    <span class="badge bg-info-subtle text-info small">.ENV Fallback Active</span>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa-solid fa-key"></i>
                                </span>
                                <input type="text" 
                                       class="form-control font-monospace" 
                                       id="stripe_key" 
                                       name="stripe_key" 
                                       value="{{ old('stripe_key', $settings->stripe_key ?? config('services.stripe.key')) }}" 
                                       placeholder="pk_test_... or pk_live_...">
                            </div>
                            <small class="text-muted d-block mt-1">
                                Public key used by frontend Stripe Elements to safely tokenize payment details.
                            </small>
                        </div>

                        {{-- Secret Key --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="stripe_secret" class="form-label fw-bold text-dark mb-0">
                                    Stripe Secret Key
                                </label>
                                @if($hasStripeSecret)
                                    <span class="badge bg-success-subtle text-success small">
                                        <i class="fa-solid fa-lock me-1"></i>Encrypted in DB
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger small">Not Configured</span>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </span>
                                <input type="password" 
                                       class="form-control font-monospace" 
                                       id="stripe_secret" 
                                       name="stripe_secret" 
                                       placeholder="{{ $hasStripeSecret ? 'Enter new key to replace (' . $settings->masked_stripe_secret . ')' : 'sk_test_... or sk_live_...' }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('stripe_secret')">
                                    <i class="fa-regular fa-eye" id="stripe_secret_eye"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Sensitive key kept encrypted in database. Leave blank to keep existing configured secret key.
                            </small>
                        </div>

                        {{-- Webhook Secret --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="stripe_webhook_secret" class="form-label fw-bold text-dark mb-0">
                                    Stripe Webhook Signing Secret (Optional)
                                </label>
                                @if(!empty($settings->stripe_webhook_secret) || !empty(config('services.stripe.webhook_secret')))
                                    <span class="badge bg-success-subtle text-success small">
                                        <i class="fa-solid fa-lock me-1"></i>Configured
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary small">Optional</span>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa-solid fa-satellite-dish"></i>
                                </span>
                                <input type="password" 
                                       class="form-control font-monospace" 
                                       id="stripe_webhook_secret" 
                                       name="stripe_webhook_secret" 
                                       placeholder="{{ !empty($settings->stripe_webhook_secret) ? 'Enter new secret to replace (' . $settings->masked_webhook_secret . ')' : 'whsec_...' }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('stripe_webhook_secret')">
                                    <i class="fa-regular fa-eye" id="stripe_webhook_secret_eye"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Generated when you create an endpoint in Stripe Dashboard &rarr; Developers &rarr; Webhooks.
                            </small>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save Stripe Settings
                            </button>
                    </form>

                            {{-- Live Test Button --}}
                            <form action="{{ route('admin.payments.stripe.test') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark d-inline-flex align-items-center gap-2 py-2" {{ ! $hasStripeSecret ? 'disabled' : '' }} title="Send test API ping to Stripe with configured secret key">
                                    <i class="fa-solid fa-plug-circle-check text-success"></i> Test Stripe Connection
                                </button>
                            </form>
                        </div>
                </div>
            </div>

            {{-- 2. PAYPAL GATEWAY CARD --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-2 rounded bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #003087 !important;">
                            <i class="fa-brands fa-paypal fs-5"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">PayPal Gateway</h5>
                            <small class="text-muted">Accept payments via PayPal balance, cards &amp; Pay in 3</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($settings->paypal_enabled)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="fa-solid fa-circle-check me-1"></i> Enabled
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                            </span>
                        @endif

                        @if($settings->paypal_mode === 'live')
                            <span class="badge bg-danger text-white border border-danger px-2.5 py-1">
                                <i class="fa-solid fa-bolt me-1"></i> LIVE MODE
                            </span>
                        @else
                            <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold">
                                <i class="fa-solid fa-flask-vial me-1"></i> SANDBOX MODE
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.payments.paypal') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Toggle Enable / Disable --}}
                        <div class="p-3 mb-4 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <label class="form-check-label fw-bold text-dark fs-6 d-block mb-1" for="paypal_enabled">
                                    Enable PayPal on Checkout
                                </label>
                                <span class="text-muted small">When active, customers in the UK can choose PayPal / Pay in 3 during checkout.</span>
                            </div>
                            <div class="form-check form-switch form-switch-lg m-0">
                                <input class="form-check-input" type="checkbox" name="paypal_enabled" id="paypal_enabled" value="1" {{ $settings->paypal_enabled ? 'checked' : '' }} style="cursor: pointer; width: 3em; height: 1.6em;">
                            </div>
                        </div>

                        {{-- Mode Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">PayPal Mode <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 position-relative {{ $settings->paypal_mode === 'sandbox' ? 'border-primary bg-primary-subtle' : '' }}" style="cursor: pointer;" onclick="document.getElementById('pp_sandbox').checked = true;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paypal_mode" id="pp_sandbox" value="sandbox" {{ $settings->paypal_mode === 'sandbox' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark ms-2" for="pp_sandbox" style="cursor: pointer;">
                                                <i class="fa-solid fa-flask-vial text-warning me-1"></i> Sandbox (Testing)
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2 ps-4">
                                            Test with PayPal sandbox developer accounts without charging real funds.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 h-100 position-relative {{ $settings->paypal_mode === 'live' ? 'border-danger bg-danger-subtle' : '' }}" style="cursor: pointer;" onclick="document.getElementById('pp_live').checked = true;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paypal_mode" id="pp_live" value="live" {{ $settings->paypal_mode === 'live' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark ms-2" for="pp_live" style="cursor: pointer;">
                                                <i class="fa-solid fa-fire-flame-curved text-danger me-1"></i> Live (Production)
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2 ps-4">
                                            Process real live PayPal transactions to your business account.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Client ID --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="paypal_client_id" class="form-label fw-bold text-dark mb-0">
                                    PayPal Client ID
                                </label>
                                @if(!empty($settings->paypal_client_id))
                                    <span class="badge bg-success-subtle text-success small">DB Stored</span>
                                @elseif(!empty(config('services.paypal.client_id')))
                                    <span class="badge bg-info-subtle text-info small">.ENV Fallback Active</span>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa-brands fa-paypal"></i>
                                </span>
                                <input type="text" 
                                       class="form-control font-monospace" 
                                       id="paypal_client_id" 
                                       name="paypal_client_id" 
                                       value="{{ old('paypal_client_id', $settings->paypal_client_id ?? config('services.paypal.client_id')) }}" 
                                       placeholder="PayPal REST App Client ID">
                            </div>
                        </div>

                        {{-- Secret Key --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="paypal_secret" class="form-label fw-bold text-dark mb-0">
                                    PayPal Secret Key
                                </label>
                                @if($hasPayPalSecret)
                                    <span class="badge bg-success-subtle text-success small">
                                        <i class="fa-solid fa-lock me-1"></i>Encrypted in DB
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger small">Not Configured</span>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </span>
                                <input type="password" 
                                       class="form-control font-monospace" 
                                       id="paypal_secret" 
                                       name="paypal_secret" 
                                       placeholder="{{ $hasPayPalSecret ? 'Enter new secret to replace (' . $settings->masked_paypal_secret . ')' : 'Enter PayPal Secret Key' }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('paypal_secret')">
                                    <i class="fa-regular fa-eye" id="paypal_secret_eye"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Kept encrypted in database. Leave blank to keep existing configured secret.
                            </small>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2" style="background-color: #003087 !important; border-color: #003087 !important;">
                                <i class="fa-solid fa-floppy-disk"></i> Save PayPal Settings
                            </button>
                    </form>

                            {{-- Live Test PayPal Button --}}
                            <form action="{{ route('admin.payments.paypal.test') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark d-inline-flex align-items-center gap-2 py-2" {{ ! $hasPayPalSecret ? 'disabled' : '' }} title="Test authentication with PayPal API">
                                    <i class="fa-brands fa-paypal text-primary"></i> Test PayPal Connection
                                </button>
                            </form>
                        </div>
                </div>
            </div>

            {{-- 3. CASH ON DELIVERY (COD) CARD (DISABLED / OPTIONAL IN UK) --}}
            <div class="card mb-4 border-0 shadow-sm opacity-75">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-2 rounded bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-truck-ramp-box fs-5"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Cash On Delivery (COD)</h5>
                            <small class="text-muted">Not applicable / not recommended for UK domestic delivery</small>
                        </div>
                    </div>
                    <div>
                        @if($settings->cod_enabled)
                            <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Active (UK: Not Recommended)
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled (UK Standard)
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-light border small mb-3 text-muted">
                        <i class="fa-solid fa-info-circle text-primary me-1"></i> In the United Kingdom, couriers (Evri, Royal Mail, DPD) do not support Cash On Delivery. It is recommended to leave this option <strong>Disabled</strong> so customers pay securely via <strong>Stripe</strong> or <strong>PayPal</strong>.
                    </div>

                    <form action="{{ route('admin.payments.cod') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Toggle Enable / Disable --}}
                        <div class="p-3 mb-4 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <label class="form-check-label fw-bold text-dark fs-6 d-block mb-1" for="cod_enabled">
                                    Enable Cash On Delivery
                                </label>
                                <span class="text-muted small">Only enable if you offer local pickup/cash collection.</span>
                            </div>
                            <div class="form-check form-switch form-switch-lg m-0">
                                <input class="form-check-input" type="checkbox" name="cod_enabled" id="cod_enabled" value="1" {{ $settings->cod_enabled ? 'checked' : '' }} style="cursor: pointer; width: 3em; height: 1.6em;">
                            </div>
                        </div>

                        {{-- Instructions --}}
                        <div class="mb-4">
                            <label for="cod_instructions" class="form-label fw-bold text-dark">
                                Instructions to Customer
                            </label>
                            <textarea class="form-control" 
                                      id="cod_instructions" 
                                      name="cod_instructions" 
                                      rows="2" 
                                      placeholder="e.g. Exact cash required upon in-person collection.">{{ old('cod_instructions', $settings->cod_instructions) }}</textarea>
                        </div>

                        <div class="pt-2 border-top">
                            <button type="submit" class="btn btn-secondary d-inline-flex align-items-center gap-2 px-4 py-2">
                                <i class="fa-solid fa-floppy-disk"></i> Save COD Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Right Column: Setup Instructions, Webhook URL, Security Tips --}}
        <div class="col-xl-4">

            {{-- Status & Summary Card --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-chart-pie text-primary me-2"></i>Gateway Status Summary
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Storage Engine:</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                <i class="fa-solid fa-database me-1"></i> Database First
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Stripe Status:</span>
                            @if($settings->stripe_enabled)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Stripe Environment:</span>
                            @if($settings->stripe_mode === 'live')
                                <span class="badge bg-danger text-white fw-bold">Live Mode</span>
                            @else
                                <span class="badge bg-warning text-dark border border-warning fw-bold">Test Sandbox</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Keys Authenticated:</span>
                            @if($stripeConfigured)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="fa-solid fa-check me-1"></i>Configured
                                </span>
                            @else
                                <span class="badge bg-warning text-dark border border-warning">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>Incomplete
                                </span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">PayPal Status:</span>
                            @if($settings->paypal_enabled)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">PayPal Mode:</span>
                            @if($settings->paypal_mode === 'live')
                                <span class="badge bg-danger text-white fw-bold">Live Mode</span>
                            @else
                                <span class="badge bg-warning text-dark border border-warning fw-bold">Sandbox</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Cash On Delivery:</span>
                            @if($settings->cod_enabled)
                                <span class="badge bg-warning text-dark border border-warning">Active (Non-Standard UK)</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Disabled (UK Standard)</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted">Default Currency:</span>
                            <span class="fw-bold text-dark">GBP (£)</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Where to get PayPal Keys Guide --}}
            <div class="card mb-4 border-0 shadow-sm bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fa-brands fa-paypal text-primary fs-4 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Where to get PayPal keys?</h6>
                            <ol class="small text-muted ps-3 mb-0">
                                <li>Log into <a href="https://developer.paypal.com" target="_blank" class="fw-bold text-primary text-decoration-underline">developer.paypal.com</a>.</li>
                                <li>Go to <strong>Apps &amp; Credentials</strong>.</li>
                                <li>Select <strong>Sandbox</strong> or <strong>Live</strong> mode.</li>
                                <li>Copy your <strong>Client ID</strong> and <strong>Secret Key</strong>.</li>
                                <li>Paste them in the PayPal form on the left and save.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Webhook Configuration Card --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-satellite-dish text-success me-2"></i>Stripe Webhook Endpoint
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Add this Webhook URL into your <strong>Stripe Dashboard &rarr; Developers &rarr; Webhooks</strong> so Stripe can automatically send order payment events:
                    </p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm font-monospace" id="webhook_url_input" value="{{ $webhookUrl }}" readonly>
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="copyWebhookUrl()">
                            <i class="fa-regular fa-copy" id="copy_icon"></i> Copy
                        </button>
                    </div>

                    <div class="p-2 rounded bg-light border">
                        <span class="fw-bold text-dark d-block small mb-1">Recommended Events to select:</span>
                        <ul class="mb-0 ps-3 text-muted" style="font-size: 11px;">
                            <li><code>checkout.session.completed</code></li>
                            <li><code>payment_intent.succeeded</code></li>
                            <li><code>payment_intent.payment_failed</code></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- How to get Stripe Keys Guide --}}
            <div class="card border-0 shadow-sm bg-primary-subtle border-primary-subtle">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fa-solid fa-circle-question text-primary fs-5 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-primary mb-1">Where to get Stripe keys?</h6>
                            <ol class="small text-dark ps-3 mb-2">
                                <li>Log into <a href="https://dashboard.stripe.com" target="_blank" class="fw-bold text-primary text-decoration-underline">dashboard.stripe.com</a>.</li>
                                <li>Navigate to <strong>Developers &rarr; API Keys</strong>.</li>
                                <li>Toggle the <em>"Test mode"</em> switch if you want test keys (starts with <code>pk_test_</code> and <code>sk_test_</code>).</li>
                                <li>Copy your <strong>Publishable key</strong> and <strong>Secret key</strong> and paste them in the form on the left.</li>
                                <li>Click <em>Save Stripe Settings</em>.</li>
                            </ol>
                            <span class="badge bg-primary text-white">Zero Downtime: Changes take effect immediately.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '_eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

function copyWebhookUrl() {
    const copyText = document.getElementById('webhook_url_input');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    const icon = document.getElementById('copy_icon');
    icon.classList.remove('fa-copy');
    icon.classList.add('fa-check');
    setTimeout(() => {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-copy');
    }, 2000);
}
</script>
@endsection

