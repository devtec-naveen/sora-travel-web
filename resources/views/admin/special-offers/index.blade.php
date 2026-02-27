@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Special Offers', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.special-offers.listing/>
        </div>
    </div>
</x-backend.admin-layout>
