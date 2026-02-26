@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Faq', 'url' => route('admin.faqList')],
        ['label' => 'Add', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.faq.add/>
        </div>
    </div>
</x-backend.admin-layout>
