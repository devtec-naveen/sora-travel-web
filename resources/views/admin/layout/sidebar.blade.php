<div class="main-sidebar main-sidebar-sticky side-menu" style="overflow: auto;">
    <div class="sidemenu-logo">
        <a class=" main-logo " href="">
            <img src="{{ asset('assets/images/logo/white-logo.png') }}" class="header-brand-img desktop-logo"
                alt="logo" style="width:100px">
        </a>
    </div>
    <div class="main-sidebar-body">
        <ul class="nav">
            <li class="nav-item {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                <a class="nav-link" wire:navigate href="{{ route('admin.dashboard') }}"><span class="shape1"></span>
                    <span class="shape2"></span><i class="ti-dashboard sidemenu-icon"></i>
                    <span class="sidemenu-label">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'admin.users' ? 'active' : '' }}">
                <a class="nav-link" wire:navigate href="{{ route('admin.users') }}"><span class="shape1"></span>
                    <span class="shape2"></span><i class="ti-user sidemenu-icon"></i>
                    <span class="sidemenu-label">Users</span>
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'admin.emailTemplate' ? 'active' : '' }}">
                <a class="nav-link" wire:navigate href="{{ route('admin.emailTemplate') }}"><span class="shape1"></span>
                    <span class="shape2"></span><i class="ti-file sidemenu-icon"></i>
                    <span class="sidemenu-label">Email Template</span>
                </a>
            </li>
            <li
                class="nav-item {{ Str::contains(Request::url(), ['faq', 'faq-category', 'pages']) ? 'active show' : '' }}">
                <a class="nav-link with-sub" href="#"><span class="shape2"></span><span class="shape21"></span>
                    <i class="ti-id-badge sidemenu-icon"></i><span class="sidemenu-label">CMS </span>
                    <i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    {{-- <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="">Page</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">Web Banners</a>
                    </li> --}}
                    {{-- <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">FAQ Categories</a>
                    </li> --}}
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="{{ route('admin.faqCategoryList') }}" wire:navigate>FAQ
                            Category</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="{{ route('admin.faqList') }}" wire:navigate>FAQ</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="{{ route('admin.pagesList') }}" wire:navigate>Pages</a>
                    </li>
                    {{-- <li class="nav-sub-item  ">
                        <a class="nav-sub-link" href="">Testimonials</a>
                    </li> --}}
                </ul>
            </li>
            @if (app()->environment('local'))
                <li class="nav-item {{ request()->routeIs('admin.offers*') ? 'active' : '' }}">
                    <a class="nav-link" wire:navigate href="{{ route('admin.offersList') }}">
                        <span class="shape1"></span>
                        <span class="shape2"></span><i class="ti-gift sidemenu-icon"></i>
                        <span class="sidemenu-label">Special Offers</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.destinations*') ? 'active' : '' }}">
                    <a class="nav-link" wire:navigate href="{{ route('admin.destinationsList') }}">
                        <span class="shape1"></span>
                        <span class="shape2"></span><i class="ti-location-pin sidemenu-icon"></i>
                        <span class="sidemenu-label">Popular Destinations</span>
                    </a>
                </li>
            @endif
            <li class="nav-item {{ Route::currentRouteName() == 'admin.globalSettingList' ? 'active' : '' }}">
                <a class="nav-link" wire:navigate href="{{ route('admin.globalSettingList') }}">
                    <span class="shape1"></span>
                    <span class="shape2"></span><i class="ti-settings sidemenu-icon"></i>
                    <span class="sidemenu-label">Global Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>
