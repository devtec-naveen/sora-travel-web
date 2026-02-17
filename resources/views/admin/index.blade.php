@extends('admin.layout.main-layout')
@section('content')
    <div class="page main-signin-wrapper">
        <div class="row signpages">
            <div class="col-md-12">
                <div class="card">
                    <div class="row row-sm">
                        <div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
                            <div class="mt-5 pt-4 p-2 pos-absolute">
                                <div class="clearfix"></div>
                                <img src="" class="header-brand-img desktop-logo" alt="logo" width="180px">
                                <h5 class="mt-4 text-white">Sign In To Your Account</h5>
                            </div>
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
                                        <livewire:admin.login />
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
