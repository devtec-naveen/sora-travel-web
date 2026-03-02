@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Popular Destination', 'url' => route('admin.destinationsList')],
        ['label' => 'View', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.popular-destination.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
