<div class="page-header">
    <div class="header-wrapper m-0">
        <div class="header-logo-wrapper p-0">
            <div class="logo-wrapper">
                <a href="{{ route('admin.dashboard') }}">
                    <img class="img-fluid main-logo" src="{{ asset('assets/images/logo/kenkie-logo.png') }}" alt="Kenkie Admin">
                </a>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle fa-solid fa-bars" id="sidebarToggle"></i>
            </div>
        </div>

        <div class="header-search-box d-none d-md-flex align-items-center">
            <i class="fa-solid fa-magnifying-glass me-2 text-muted"></i>
            <form action="{{ route('admin.products.index') }}" method="GET" class="w-100">
                <input type="text" name="search" placeholder="Quick search products, orders..." class="form-control border-0 bg-light">
            </form>
        </div>

        <div class="nav-right ms-auto d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2">
                <i class="fa-solid fa-store"></i>
                <span class="d-none d-sm-inline">View Storefront</span>
            </a>

            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center gap-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; font-size: 15px; background-color: #0da487;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-start d-none d-lg-block">
                        <span class="d-block fw-bold text-dark lh-1" style="font-size: 14px;">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <small class="text-muted" style="font-size: 11px;">Admin</small>
                    </div>
                    <i class="fa-solid fa-chevron-down text-muted ms-1" style="font-size: 11px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 220px; border-radius: 12px;">
                    <li class="px-3 py-2 border-bottom">
                        <p class="mb-0 fw-bold text-dark">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <small class="text-muted">{{ auth()->user()->email ?? 'admin@kenkie.com' }}</small>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('admin.profile.edit') }}">
                            <i class="fa-solid fa-user-gear me-2 text-muted"></i> Account Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('home') }}" target="_blank">
                            <i class="fa-solid fa-store me-2 text-muted"></i> Live Storefront
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
