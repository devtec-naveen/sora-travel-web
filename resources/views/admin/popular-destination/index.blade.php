@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Popular Destination', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.popular-destination.listing/>
        </div>
    </div>
</x-backend.admin-layout>
