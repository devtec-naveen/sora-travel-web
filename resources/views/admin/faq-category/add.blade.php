@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Faq Category', 'url' => route('admin.faqCategoryList')],
        ['label' => 'Add', 'url' => null],
    ];
@endphp
<x-backend.admin-layout :breadcrumb="$breadcrumbs">
    <div class="card custom-card">
        <div class="card-body">
            <livewire:backend.faq-category.add/>
        </div>
    </div>
</x-backend.admin-layout>
