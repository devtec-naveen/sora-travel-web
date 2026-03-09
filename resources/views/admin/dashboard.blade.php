<x-backend.admin-layout pageTitle="Dashboard" :breadcrumb="false">
    <div class="row row-sm">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <a wire:navigate href="{{ route('admin.users') }}">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="card-order">
                            <label class="main-content-label mb-3 pt-1">Users</label>
                            <h2 class="text-right"><i class="mdi mdi-cube icon-size float-left text-primary"></i><span
                                    class="font-weight-bold">{{ $userCount }}</span></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <a wire:navigate href="{{ route('admin.offersList') }}">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="card-order">
                            <label class="main-content-label mb-3 pt-1">Speical Offers</label>
                            <h2 class="text-right"><i class="mdi mdi-gift icon-size float-left text-primary"></i><span
                                    class="font-weight-bold">{{ $specialOffers }}</span></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <a wire:navigate href="{{ route('admin.destinationsList') }}">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="card-order">
                            <label class="main-content-label mb-3 pt-1">Popular Destination</label>
                            <h2 class="text-right"><i
                                    class="mdi mdi-map-marker icon-size float-left text-primary"></i><span
                                    class="font-weight-bold">{{ $popularDestination }}</span></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-backend.admin-layout>
