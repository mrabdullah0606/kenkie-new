@extends('admin.layouts.app')

@section('title', 'Profile & Settings')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Account & Store Settings</h3>
            <p class="text-muted mb-0">Manage your administrator account details, security credentials, and preferences.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Sidebar Column: Admin Overview Card -->
        <div class="col-xl-4">
            <div class="card mb-4 text-center p-4">
                <div class="mx-auto mb-3">
                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 80px; height: 80px; font-size: 30px; background-color: #0da487;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2 small">{{ $user->email }}</p>
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-semibold">
                        <i class="fa-solid fa-shield-halved me-1"></i> {{ ucfirst($user->role ?? 'Admin') }}
                    </span>
                </div>

                <hr class="my-4">

                <div class="text-start">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Account Type:</span>
                        <span class="fw-semibold small text-dark">Administrator</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Registered:</span>
                        <span class="fw-semibold small text-dark">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Email Status:</span>
                        <span class="badge bg-success-subtle text-success small">Verified</span>
                    </div>
                </div>
            </div>

            <!-- Store WhatsApp Contact Quick Card -->
            <div class="card p-4 mb-4 border-0 shadow-sm" style="background: #f0fdf4; border-left: 4px solid #25d366 !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fa-brands fa-whatsapp text-success fs-4"></i>
                    <h6 class="fw-bold text-dark mb-0">Store WhatsApp Number</h6>
                </div>
                <div class="fw-bold text-dark fs-5 font-monospace mb-1">
                    {{ $marketingSettings->whatsapp_number }}
                </div>
                <p class="text-muted small mb-3">
                    {{ $marketingSettings->whatsapp_enabled ? 'Active on Storefront' : 'Currently Disabled' }} &bull; Powers floating chat widget & customer support.
                </p>
                <a href="{{ $marketingSettings->whatsapp_url }}" target="_blank" class="btn btn-sm btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-brands fa-whatsapp"></i> Test WhatsApp Link
                </a>
            </div>

            <!-- Quick Storefront Link Card -->
            <div class="card p-4 bg-light border-0">
                <h6 class="fw-bold text-dark mb-2">Live Storefront</h6>
                <p class="text-muted small mb-3">Open your customer-facing store in a new tab to see changes in real-time.</p>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-store"></i> Open Storefront
                </a>
            </div>
        </div>

        <!-- Right Content Column: Forms -->
        <div class="col-xl-8">

            <!-- Store WhatsApp & Phone Number Settings Card -->
            <div class="card mb-4 border-0 shadow-sm" id="store-contact">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded p-2 text-white d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #25d366;">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Store WhatsApp &amp; Contact Number</h5>
                            <small class="text-muted">Updates the floating chat widget on storefront &amp; support pages</small>
                        </div>
                    </div>
                    <div>
                        @if($marketingSettings->whatsapp_enabled)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                <i class="fa-solid fa-circle-check me-1"></i> Active on Storefront
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.profile.store-contact') }}">
                        @csrf
                        @method('PUT')

                        {{-- Toggle Enable / Disable --}}
                        <div class="p-3 mb-4 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <label class="form-check-label fw-bold text-dark fs-6 d-block mb-1" for="whatsapp_enabled">
                                    Enable Floating WhatsApp Button on Storefront
                                </label>
                                <span class="text-muted small">When turned on, the green WhatsApp assistant widget appears on the bottom right of the store.</span>
                            </div>
                            <div class="form-check form-switch form-switch-lg m-0">
                                <input class="form-check-input" type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" {{ old('whatsapp_enabled', $marketingSettings->whatsapp_enabled) ? 'checked' : '' }} style="cursor: pointer; width: 3em; height: 1.6em;">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="whatsapp_number">
                                    WhatsApp / Support Phone Number <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-brands fa-whatsapp text-success fs-5"></i></span>
                                    <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $marketingSettings->whatsapp_number) }}" placeholder="e.g. +44 7898 346397 or 07898346397" required>
                                </div>
                                <div class="form-text text-muted small">
                                    You can enter UK format (e.g. <code>07898 346397</code>) or international format (e.g. <code>+44 7898 346397</code>).
                                </div>
                                @error('whatsapp_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="whatsapp_agent_name">
                                    Support Agent / Team Name
                                </label>
                                <input class="form-control @error('whatsapp_agent_name') is-invalid @enderror" id="whatsapp_agent_name" name="whatsapp_agent_name" value="{{ old('whatsapp_agent_name', $marketingSettings->whatsapp_agent_name) }}" placeholder="e.g. Kenkie Support">
                                <div class="form-text text-muted small">Displayed on the chat widget title header.</div>
                                @error('whatsapp_agent_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="whatsapp_default_message">
                                    Default Pre-typed Message (Optional)
                                </label>
                                <textarea class="form-control @error('whatsapp_default_message') is-invalid @enderror" id="whatsapp_default_message" name="whatsapp_default_message" rows="2" placeholder="Hello Kenkie Support! I would like some assistance with my order / product.">{{ old('whatsapp_default_message', $marketingSettings->whatsapp_default_message) }}</textarea>
                                <div class="form-text text-muted small">This message is automatically pre-filled in WhatsApp when customers click to chat.</div>
                                @error('whatsapp_default_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-3 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <a href="{{ $marketingSettings->whatsapp_url }}" target="_blank" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1">
                                        <i class="fa-brands fa-whatsapp"></i> Test Live Link ({{ $marketingSettings->clean_whatsapp_number }})
                                    </a>
                                </div>
                                <button class="btn btn-success d-inline-flex align-items-center gap-2" type="submit" style="background-color: #0da487; border-color: #0da487;">
                                    <i class="fa-solid fa-floppy-disk"></i> Save &amp; Update Store Number
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Profile Details Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="name">Full Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="email">Email Address <span class="text-danger">*</span></label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Details
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security & Password Update -->
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Update Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="current_password">Current Password <span class="text-danger">*</span></label>
                                <input class="form-control @error('current_password') is-invalid @enderror" type="password" id="current_password" name="current_password" placeholder="••••••••" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="password">New Password <span class="text-danger">*</span></label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa-solid fa-key me-2"></i> Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
