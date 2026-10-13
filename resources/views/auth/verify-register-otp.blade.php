<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kenkie - Verify Email Code">
    <link rel="icon" href="{{ asset('assets/images/logo/kenkie-favicon-32.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/kenkie-favicon-32.png') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Kenkie') }} - Verify Email Code</title>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/vendors/bootstrap.css') }}">

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/style.css') }}">

    <style>
        body,
        .log-in-section {
            background: #0da487 !important;
            background-image: none !important;
            min-height: 100vh;
        }

        .log-in-section::after,
        .log-in-section::before {
            display: none !important;
            background: none !important;
        }

        .log-in-box {
            background: #ffffff !important;
            border-radius: 16px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18) !important;
            padding: 40px 35px !important;
            border: none !important;
        }

        .otp-input {
            letter-spacing: 12px;
            font-size: 28px !important;
            font-weight: 700 !important;
            text-align: center !important;
            height: 60px !important;
            border: 2px solid #0da487 !important;
            border-radius: 10px !important;
            color: #0da487 !important;
            font-family: 'Courier New', Courier, monospace;
        }

        .otp-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 164, 135, 0.25) !important;
            border-color: #087d66 !important;
        }

        .btn-theme-submit {
            background-color: #0da487 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            border: 1px solid #0da487 !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            transition: all 0.25s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            cursor: pointer !important;
        }

        .btn-theme-submit:hover,
        .btn-theme-submit:focus {
            background-color: #087d66 !important;
            border-color: #087d66 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(8, 125, 102, 0.35) !important;
        }

        .auth-theme-link {
            color: #0da487 !important;
            text-decoration: none !important;
            font-weight: 600;
        }

        .auth-theme-link:hover {
            color: #075848 !important;
            text-decoration: underline !important;
        }
    </style>
</head>

<body>
    <section class="log-in-section section-b-space d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
                    <div class="log-in-box">
                        <div class="text-center mb-4">
                            <a href="{{ route('home') }}" class="d-inline-block mb-3">
                                <img src="{{ asset('assets/images/logo/kenkie-logo.png') }}" alt="{{ config('app.name', 'Kenkie') }}" class="img-fluid" style="max-height: 52px; object-fit: contain;">
                            </a>
                            <h3 class="fw-bold text-dark mb-1">Verify Your Email</h3>
                            <p class="text-muted mb-0 small">Enter the 6-digit code sent to</p>
                            <span class="badge bg-light text-dark border mt-1 px-3 py-2 fw-semibold fs-6">{{ $email }}</span>
                        </div>

                        <!-- Status Alert -->
                        @if (session('status'))
                            <div class="alert alert-success py-2 px-3 mb-3 text-sm text-center" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif



                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 px-3 mb-3 text-sm" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="input-box">
                            <form method="POST" action="{{ route('register.otp') }}" class="row g-3">
                                @csrf

                                <div class="col-12 text-center">
                                    <label for="otp_code" class="form-label text-muted small fw-semibold mb-2">6-Digit Verification Code</label>
                                    <input type="text"
                                           class="form-control otp-input @error('otp_code') is-invalid @enderror"
                                           id="otp_code"
                                           name="otp_code"
                                           placeholder="••••••"
                                           maxlength="6"
                                           inputmode="numeric"
                                           pattern="[0-9]{6}"
                                           required
                                           autofocus
                                           autocomplete="one-time-code">
                                    @error('otp_code')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-2">Code expires in 10 minutes</small>
                                </div>

                                <div class="col-12 mt-4">
                                    <button class="btn btn-theme-submit" type="submit">
                                        Verify &amp; Create Account
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-2 small">Didn't receive the email code?</p>
                            <form method="POST" action="{{ route('register.resend-otp') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 auth-theme-link border-0 align-baseline">
                                    Click here to resend code
                                </button>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('register') }}" class="text-muted small text-decoration-none">
                                &larr; Back to Registration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
