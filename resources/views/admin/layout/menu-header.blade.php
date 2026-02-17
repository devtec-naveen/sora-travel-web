<div class="main-header side-header sticky">
    <div class="container-fluid">
        <div class="main-header-left">
            <a class="main-header-menu-icon" href="#" id="mainSidebarToggle"><span></span></a>
        </div>
        <div class="main-header-center">
            <div class="responsive-logo">
                <a href="">
                    {{-- <img src="{{ __('app.logo_path')}}" class="mobile-logo" alt="logo"> --}}
                </a>
                <a href="">
                    {{-- <img src="{{ __('app.logo_path')}}" class="mobile-logo-dark" alt="logo"> --}}
                </a>
            </div>
        </div>
        <div class="main-header-right">
            <div class="dropdown header-search">
                <a class="nav-link icon header-search">
                    <i class="fe fe-search header-icons"></i>
                </a>
                <div class="dropdown-menu">
                    <div class="main-form-search p-2">
                        <div class="input-group">
                            <div class="input-group-btn search-panel">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown main-profile-menu">
                <a class="d-flex" href="#">
                    <span class="main-img-user bg-primary">
                        <img alt="avatar" src="" alt="image">
                    </span>
                </a>
                <div class="dropdown-menu">
                    <div class="header-navheading">
                        <h6 class="main-notification-title text-capitalize">
                            {{auth()->guard('admin')->user()->name}}
                        </h6>
                        <p class="main-notification-text"></p>
                    </div>
                    <a class="dropdown-item border-top" href="">
                        <i class="fe fe-user"></i> Change Password
                    </a>
                    <a class="dropdown-item border-top" href="">
                        <i class="fe fe-user"></i> Edit Profile
                    </a>
                    <a href="javascript:void(0)" onclick="return logout(event)" class="dropdown-item"><i class="fe fe-power"></i>Log Out</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
            <button class="navbar-toggler navresponsive-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
            </button>
        </div>
    </div>
</div>
