@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'User Management', 'url' => route('admin.users')],
        ['label' => 'List', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.users.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
