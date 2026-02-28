@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Special offers', 'url' => route('admin.offersList')],
        ['label' => 'View', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.special-offers.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
