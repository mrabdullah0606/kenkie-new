<!DOCTYPE html>
<html lang="en">

<head>
    @include('website.includes.head')
    @include('website.includes.tracking-pixels')
    @yield('page-styles')
</head>

<body class="@yield('body-class')">
    @yield('body')

    @include('website.includes.quick-view-modal')
    @include('website.includes.promotional-popup')
    @include('website.includes.whatsapp-chat-widget')

    @include('website.includes.storefront-scripts')
    @yield('page-scripts')
</body>

</html>
