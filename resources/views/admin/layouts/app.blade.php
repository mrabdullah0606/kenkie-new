<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <header class="border-bottom bg-white">
        <nav class="container-fluid-lg navbar navbar-expand-lg">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">Kenkie Admin</a>
            <div class="ms-auto d-flex gap-3">
                <a href="{{ route('admin.products.index') }}">Products</a>
                <a href="{{ route('home') }}">View storefront</a>
            </div>
        </nav>
    </header>

    <main class="container-fluid-lg py-4">
        @yield('content')
    </main>
</body>

</html>