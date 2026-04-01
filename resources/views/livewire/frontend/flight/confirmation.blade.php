<div>
    <main class="bg-slate-50 min-h-[800px]">
        <section class="py-10 md:py-16">
            <div class="container">
                <div class="max-w-3xl mx-auto flex flex-col gap-8">
                    <div class="card p-4 md:p-6 flex flex-col items-center text-center gap-8">

                        {{-- Success Header --}}
                        <div class="flex flex-col items-center gap-6">
                            <div
                                class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-green-600 shadow-sm">
                                <i data-tabler="check" data-size="32" data-stroke="2"></i>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h1
                                    class="font-semibold text-xl sm:text-2xl md:text-3xl lg:text-4xl leading-tight text-slate-900">
                                    Booking Confirmed!
                                </h1>
                                <p class="font-normal text-base text-slate-500 max-w-md mx-auto">
                                    Your booking is confirmed. The official ticket and payment receipt is sent to your
                                    registered email.
                                </p>
                            </div>
                        </div>

                        {{-- Booking ID --}}
                        @if (!empty($order['id']))
                            <div
                                class="w-full bg-blue-50 p-3 md:p-5 rounded-2xl border border-blue-200 flex flex-col gap-1.5">
                                <span
                                    class="font-normal text-sm md:text-base text-slate-500 uppercase tracking-wider">Booking
                                    ID</span>
                                <span
                                    class="font-bold text-xl sm:text-2xl md:text-3xl leading-none text-blue-600 break-all">
                                    {{ strtoupper($order['id']) }}
                                </span>
                            </div>
                        @endif

                        {{-- Flight Details --}}
                        @if (!empty($segment))
                            @php
                                $dep = $segment['departing_at'] ?? null;
                                $arr = $segment['arriving_at'] ?? null;
                                $orig = $segment['origin']['iata_code'] ?? '';
                                $dest = $segment['destination']['iata_code'] ?? '';
                                $origCity = $segment['origin']['city_name'] ?? $orig;
                                $destCity = $segment['destination']['city_name'] ?? $dest;
                                $logo = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                $airline = $segment['operating_carrier']['name'] ?? '';
                                $fno =
                                    ($segment['operating_carrier']['iata_code'] ?? '') .
                                    ($segment['operating_carrier_flight_number'] ?? '');
                                $dur = $segment['duration'] ?? '';
                                $stops = count($slice['segments'] ?? []) - 1;
                            @endphp

                            <div class="w-full flex flex-col gap-6 text-left">
                                <h2 class="font-semibold text-xl text-slate-950">Flight Details</h2>

                                <div class="flex items-center gap-4">
                                    @if ($logo)
                                        <img src="{{ $logo }}"
                                            class="w-[48px] h-[48px] object-contain rounded-xl border border-slate-100"
                                            alt="{{ $airline }}">
                                    @endif
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-semibold text-lg text-slate-950">{{ $airline }}</span>
                                        <span
                                            class="font-normal text-sm text-slate-500 tracking-wide">{{ $fno }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-row items-center justify-between gap-4">
                                    <div class="flex flex-col items-start">
                                        <span class="font-semibold text-base lg:text-xl text-slate-950">
                                            {{ $dep ? \Carbon\Carbon::parse($dep)->format('H:i') : '' }}
                                        </span>
                                        <span class="font-normal text-sm text-slate-500">{{ $origCity }}
                                            ({{ $orig }})</span>
                                        @if ($dep)
                                            <span
                                                class="font-normal text-sm text-slate-500">{{ \Carbon\Carbon::parse($dep)->format('d M, Y') }}</span>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px]">
                                        <span class="font-normal text-xs text-slate-500">
                                            {{ $dur? \Carbon\CarbonInterval::make($dur)->cascade()->forHumans(['parts' => 2]): '' }}
                                        </span>
                                        <div class="relative w-full flex items-center justify-center h-4">
                                            <div class="absolute w-full h-px bg-slate-200"></div>
                                            <div class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                            <div class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                            <div class="relative z-10 bg-white px-2">
                                                <i data-tabler="plane" class="text-slate-400" data-size="18"></i>
                                            </div>
                                        </div>
                                        <span class="font-normal text-xs text-slate-500">
                                            {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col items-end">
                                        <span class="font-semibold text-base lg:text-xl text-slate-950">
                                            {{ $arr ? \Carbon\Carbon::parse($arr)->format('H:i') : '' }}
                                        </span>
                                        <span class="font-normal text-sm text-slate-500 text-right">{{ $destCity }}
                                            ({{ $dest }})</span>
                                        @if ($arr)
                                            <span
                                                class="font-normal text-sm text-slate-500 text-right">{{ \Carbon\Carbon::parse($arr)->format('d M, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Important Info --}}
                        <div class="w-full flex flex-col gap-4 text-left">
                            <h2 class="font-semibold text-xl text-slate-950">Important Information</h2>
                            <div class="space-y-3">
                                @foreach (['Please arrive at the airport at least 2 hours before departure for international flights.', 'Make sure to bring a valid passport and/or government-issued ID.', 'You can check in online 24 hours before your flight.', 'Baggage allowance: 1 checked bag (23 kg) + 1 carry-on (7 kg).'] as $info)
                                    <div class="flex gap-3">
                                        <i data-tabler="check" class="text-green-600 shrink-0 mt-0.5"
                                            data-size="16"></i>
                                        <span
                                            class="font-normal text-sm text-slate-700 leading-relaxed">{{ $info }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 10 sec countdown + redirect --}}
                        <div class="w-full bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <p class="text-sm text-slate-500">
                                Redirecting to home in
                                <strong id="redirect-countdown" class="text-slate-700">10</strong>
                                seconds...
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="w-full flex flex-col sm:flex-row gap-3 pt-2">
                            <a href="{{route('my-booking')}}" class="btn btn-white flex-1">
                                <i data-tabler="calendar" data-size="16"></i>
                                View My Bookings
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-primary flex-1">
                                <i data-tabler="plane-departure" data-size="16"></i>
                                Book Another Trip
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
    @push('scripts')
        <script>
            (function() {
                let secs = 10;
                const el = document.getElementById('redirect-countdown');

                const timer = setInterval(function() {
                    secs--;
                    if (el) el.textContent = secs;
                    if (secs <= 0) {
                        clearInterval(timer);
                        window.location.href = '{{ route('home') }}';
                    }
                }, 1000);
            })();
        </script>
    @endpush
</div>
