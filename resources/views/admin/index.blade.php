@extends('admin.layout.main-layout')
@section('content')
    <div class="page main-signin-wrapper">
        <div class="row signpages">
            <div class="col-md-12">
                <div class="card">
                    <div class="row row-sm">
                        <div class="col-lg-6 col-xl-5 d-none d-lg-flex bg-primary"
                            style="display:flex; flex-direction:column; justify-content:center; align-items:center;">
                            <img src="{{ asset('assets/images/logo/white-logo.png') }}" class="img-fluid mb-3" alt="logo"
                                style="max-width:200px;">
                            <h5 class="text-white text-center">Sign In To Your Account</h5>
                        </div>
                        <div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form">
                            <div class="container-fluid">
                                <div class="row row-sm">
                                    <div class="card-body mt-2 mb-2">
                                        <img src="{{ asset('theme_assets') }}/img/brand/logo.png"
                                            class="d-lg-none header-brand-img text-left float-left mb-4" alt="logo">
                                        <div class="clearfix"></div>
                                        <p class="mb-4 text-muted tx-13 ml-0 text-left">
                                            Signin to create, discover and connect with the global community
                                        </p>
                                        <livewire:backend.login />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
