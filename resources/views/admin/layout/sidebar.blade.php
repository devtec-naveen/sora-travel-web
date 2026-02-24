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
                    <span class="shape2"></span><i class="ti-dashboard sidemenu-icon"></i>
                    <span class="sidemenu-label">Users</span>
                </a>
            </li>



            {{-- <li class="nav-item">
                <a class="nav-link with-sub" href="#"><span class="shape2"></span><span class="shape21"></span>
                    <i class="ti-id-badge sidemenu-icon"></i><span class="sidemenu-label">CMS </span>
                    <i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="">Page</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">Web Banners</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">FAQ Categories</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">FAQ</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="">Email Templates</a>
                    </li>
                    <li class="nav-sub-item  ">
                        <a class="nav-sub-link" href="">Testimonials</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="">Global Setting</a>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </div>
</div>
