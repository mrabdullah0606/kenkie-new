<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper">
            <a href="{{ route('admin.dashboard') }}">
                <img class="img-fluid" src="{{ asset('assets/images/logo/kenkie-logo.png') }}" alt="Kenkie Admin">
            </a>
            <div class="back-btn d-lg-none">
                <i class="fa fa-angle-left text-white fs-5"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="ri-apps-line status_toggle middle sidebar-toggle text-white"></i>
            </div>
        </div>

        <nav class="sidebar-main">
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="fa-solid fa-box-open"></i>
                            <span>Products</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">All Products</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.create') }}" class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">Add New Product</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="fa-solid fa-tags"></i>
                            <span>Categories</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">All Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.create') }}" class="{{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">Add New Category</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Orders</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fa-solid fa-users"></i>
                            <span>Users & Roles</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">All Users</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">Add New User</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.home.*') ? 'active' : '' }}" href="{{ route('admin.home.index') }}">
                            <i class="fa-solid fa-images"></i>
                            <span>Home & Banners</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}" href="{{ route('admin.offers.index') }}">
                            <i class="fa-solid fa-ticket"></i>
                            <span>Bank & Wallet Offers</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Settings & Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-list mt-3 pt-3 border-top border-white-50">
                        <a class="sidebar-link text-white-50" href="{{ route('home') }}" target="_blank">
                            <i class="fa-solid fa-store"></i>
                            <span>View Storefront</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
