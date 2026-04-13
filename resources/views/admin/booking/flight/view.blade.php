@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Booking', 'url' =>route('admin.booking.flight')],
        ['label' => 'View', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.booking.flight.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>