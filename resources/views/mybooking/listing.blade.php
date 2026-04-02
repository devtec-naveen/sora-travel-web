<x-frontend.main-layout>
         @php
        // Blade tak request aa rahi hai ya nahi
        \Log::info('Blade file loaded for /my-booking route');
        dd('Blade loaded'); // test
    @endphp
     <livewire:frontend.mybooking.listing/>
</x-frontend.main-layout>
