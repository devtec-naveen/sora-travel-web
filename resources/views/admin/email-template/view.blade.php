@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Email Template', 'url' => route('admin.emailTemplate')],
        ['label' => 'View', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.email-template.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
