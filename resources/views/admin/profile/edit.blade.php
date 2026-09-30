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
