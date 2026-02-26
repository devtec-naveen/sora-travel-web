@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Faq', 'url' => route('admin.faqList')],
        ['label' => 'View', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.faq-category.view :id="$id"/>
        </div>
    </div>
</x-backend.admin-layout>
