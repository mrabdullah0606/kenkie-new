<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kenkie - Forgot Password">
    <link rel="icon" href="{{ asset('admin-assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.png') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Kenkie') }} - Forgot Password</title>

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
        .btn-theme-submit:focus,
        .btn-theme-submit:active {
            background-color: #087d66 !important;
            border-color: #087d66 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(8, 125, 102, 0.35) !important;
        }

        .log-in-box a,
        .auth-theme-link,
        .text-theme,
        .forgot-password {
            color: #0da487 !important;
            text-decoration: none !important;
            font-weight: 600;
            transition: color 0.2s ease-in-out;
        }

        .log-in-box a:hover,
        .log-in-box a:focus,
        .auth-theme-link:hover,
        .auth-theme-link:focus,
        .text-theme:hover,
        .text-theme:focus,
        .forgot-password:hover,
        .forgot-password:focus {
            color: #075848 !important;
            text-decoration: underline !important;
        }

        .auth-back-link {
            color: #6c757d !important;
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
        }

        .auth-back-link:hover,
        .auth-back-link:focus {
            color: #212529 !important;
            text-decoration: underline !important;
        }

        .theme-form-floating > .form-control:focus {
            border-color: #0da487 !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 164, 135, 0.2) !important;
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
                            <h3 class="fw-bold text-dark mb-1">Forgot Password</h3>
                            <p class="text-muted mb-0 small">Enter your email and we'll send a password reset link</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success py-2 px-3 mb-3 text-sm" role="alert">
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
                            <form method="POST" action="{{ route('password.email') }}" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                                        <label for="email">Email Address</label>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button class="btn btn-theme-submit" type="submit">
                                        Email Password Reset Link
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <a href="{{ route('login') }}" class="auth-theme-link small">&larr; Back to Log In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
