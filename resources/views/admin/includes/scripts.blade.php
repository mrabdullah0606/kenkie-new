<!-- latest js -->
<script src="{{ asset('admin-assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- Bootstrap js -->
<script src="{{ asset('admin-assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- feather icon js -->
<script src="{{ asset('admin-assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/icons/feather-icon/feather-icon.js') }}"></script>

<!-- scrollbar simplebar js -->
<script src="{{ asset('admin-assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('admin-assets/js/scrollbar/custom.js') }}"></script>

<!-- Sidebar jquery -->
<script src="{{ asset('admin-assets/js/config.js') }}"></script>

<!-- Plugins JS -->
<script src="{{ asset('admin-assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('admin-assets/js/notify/bootstrap-notify.min.js') }}"></script>

<!-- slick slider js -->
<script src="{{ asset('admin-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/custom-slick.js') }}"></script>

<!-- ratio js -->
<script src="{{ asset('admin-assets/js/ratio.js') }}"></script>

<!-- sidebar effect -->
<script src="{{ asset('admin-assets/js/sidebareffect.js') }}"></script>

<!-- Theme js -->
<script src="{{ asset('admin-assets/js/script.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.page-wrapper')?.classList.toggle('close_icon');
            document.querySelector('.sidebar-wrapper')?.classList.toggle('close_icon');
        });
    }
});
</script>
