<x-frontend.main-layout>
    <main class="bg-slate-50 min-h-[800px]">
        <div class="container py-6 lg:py-10">
            <div class="flex flex-col lg:flex-row gap-4 md:gap-6">
                @include('myaccount.sidebar')
                <div class="flex-1 min-w-0">
                    <livewire:frontend.my-account.delete-account />
                </div>
            </div>
        </div>
    </main>
</x-frontend.main-layout>
