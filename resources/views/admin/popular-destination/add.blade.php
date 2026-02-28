@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Popular Destination', 'url' => route('admin.destinationsList')],
        ['label' => 'Add', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.popular-destination.add/>
        </div>
    </div>
</x-backend.admin-layout>
