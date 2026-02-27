@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Pages', 'url' => route('admin.pagesList')],
        ['label' => 'Edit', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.pages.edit :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
