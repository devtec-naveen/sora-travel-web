@php
    $breadcrumbs = [
        ['label' => 'Dashboard','url' => route('admin.dashboard')],
        ['label' => 'Change Password', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.change-password />
        </div>
    </div>
</x-backend.admin-layout>
