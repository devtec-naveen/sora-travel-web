<div>
@if (session('booking_timeout'))
<div
    x-data="{ open: true }"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-center justify-center p-4"
    style="display: none;"
>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

    <div
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 flex flex-col items-center gap-5 z-10"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
    >
        <div class="w-16 h-16 rounded-full flex items-center justify-center
            {{ session('booking_timeout') ? 'bg-amber-50' : 'bg-red-50' }}">
            @if (session('booking_timeout'))
                <i data-tabler="clock-exclamation" class="text-amber-500" data-size="32"></i>
            @else
                <i data-tabler="lock-open" class="text-red-500" data-size="32"></i>
            @endif
        </div>

        <div class="text-center flex flex-col gap-2">
            @if (session('booking_timeout'))
                <h3 class="font-bold text-lg text-slate-900">Session Expired</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Your booking session expired due to 10 minutes of inactivity. Please search again to continue.
                </p>
            @else
                <h3 class="font-bold text-lg text-slate-900">Access Restricted</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Please start your booking from the search page. You cannot access this page directly.
                </p>
            @endif
        </div>

        <div class="flex flex-col gap-2.5 w-full">
            <a href="{{ route('home') }}" class="btn btn-primary w-full justify-center">
                <i data-tabler="search" data-size="16"></i>
                Search Flights
            </a>
            <button @click="open = false" class="btn btn-white w-full justify-center">
                Close
            </button>
        </div>
    </div>
</div>
@endif
</div>