@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => ''],
        ['label' => 'User Management', 'url' => ''],
        ['label' => 'List', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.users.listing/>
        </div>
    </div>
</x-backend.admin-layout>
