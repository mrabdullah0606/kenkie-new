<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Kenkie Admin Dashboard">
<meta name="author" content="Kenkie">
<link rel="icon" href="{{ asset('assets/images/favicon/5.png') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon/5.png') }}" type="image/x-icon">
<title>@yield('title', 'Admin Dashboard') | Kenkie</title>

<!-- Google font-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- FontAwesome 6 CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- RemixIcon CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

<!-- Quill Rich Text Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Linear Icon css -->
<link rel="stylesheet" href="{{ asset('admin-assets/css/linearicon.css') }}">

<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/vendors/bootstrap.css') }}">

<!-- ratio css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/ratio.css') }}">

<!-- Feather icon css-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/vendors/feather-icon.css') }}">

<!-- Plugins css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/vendors/scrollbar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/vendors/animate.css') }}">

<!-- Slick Slider Css -->
<link rel="stylesheet" href="{{ asset('admin-assets/css/vendors/slick.css') }}">

<!-- App css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/style.css') }}">

<!-- Kenkie Admin Polished Styles -->
<style>
:root {
    --theme-color: #0da487 !important;
    --theme-color-rgb: 13, 164, 135 !important;
    --theme-color-hover: #0b8f75 !important;
}

body {
    font-family: 'Public Sans', sans-serif !important;
    background-color: #f8f9fa !important;
}

.page-wrapper.compact-wrapper .page-body-wrapper .sidebar-wrapper {
    background: #0da487 !important;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
}

.sidebar-wrapper .logo-wrapper {
    padding: 18px 24px !important;
    background: #ffffff !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-wrapper .logo-wrapper img {
    max-height: 38px !important;
    width: auto !important;
    object-fit: contain !important;
}

.sidebar-links .sidebar-list {
    margin: 4px 14px;
}

.sidebar-links .sidebar-list > a {
    color: rgba(255, 255, 255, 0.9) !important;
    border-radius: 8px;
    padding: 11px 16px !important;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
}

.sidebar-links .sidebar-list > a i {
    font-size: 18px;
    width: 22px;
    text-align: center;
    color: rgba(255, 255, 255, 0.85);
}

.sidebar-links .sidebar-list > a:hover,
.sidebar-links .sidebar-list > a.active {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
}

.sidebar-links .sidebar-submenu {
    background-color: rgba(0, 0, 0, 0.1) !important;
    border-radius: 8px;
    margin: 4px 0;
    padding: 8px 0;
}

.sidebar-links .sidebar-submenu li a {
    color: rgba(255, 255, 255, 0.8) !important;
    padding: 6px 20px 6px 48px !important;
    font-size: 13px;
    display: block;
}

.sidebar-links .sidebar-submenu li a:hover,
.sidebar-links .sidebar-submenu li a.active {
    color: #ffffff !important;
    font-weight: 600;
}

/* Metric Cards */
.metric-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}

.metric-card .icon-box {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.icon-box.green {
    background: #e6f7f2;
    color: #0da487;
}

.icon-box.blue {
    background: #e8f2ff;
    color: #2563eb;
}

.icon-box.purple {
    background: #f3e8ff;
    color: #7c3aed;
}

.icon-box.amber {
    background: #fef3c7;
    color: #d97706;
}

/* Buttons */
.btn-primary,
.btn-theme {
    background-color: #0da487 !important;
    border-color: #0da487 !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 8px;
    padding: 9px 18px;
    transition: all 0.2s ease;
}

.btn-primary:hover,
.btn-theme:hover {
    background-color: #0b8f75 !important;
    border-color: #0b8f75 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 164, 135, 0.3);
}

.badge-theme {
    background-color: #0da487 !important;
    color: #ffffff !important;
}

.card {
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

/* Quill Rich Text Editor */
.ql-toolbar.ql-snow {
    border: 1px solid #ced4da !important;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    background: #f8fafc !important;
    padding: 8px !important;
}
.ql-container.ql-snow {
    border: 1px solid #ced4da !important;
    border-top: none !important;
    border-bottom-left-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
    min-height: 200px !important;
    font-size: 14px !important;
    font-family: inherit !important;
    background: #ffffff !important;
}
.ql-editor {
    min-height: 200px !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
}
.ql-editor.ql-blank::before {
    color: #94a3b8 !important;
    font-style: normal !important;
}
</style>
