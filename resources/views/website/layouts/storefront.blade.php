<!DOCTYPE html>
<html lang="en">

<head>
    @include('website.includes.head')
    @yield('page-styles')
</head>

<body class="@yield('body-class')">
    @yield('body')

    @include('website.includes.quick-view-modal')

    @include('website.includes.storefront-scripts')
    @yield('page-scripts')
</body>

</html>
