<div wire:init="loadData">
    <x-loader message="Please Wait..." targets="loadData,setType,setStatus,dateRange" />
    @if (!$isLoading && $o)
        {{-- Breadcrumb --}}
        <div class="bg-slate-100 py-3 border-b border-slate-200/60">
            <div class="container">
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}"
                        class="font-normal text-sm text-slate-500 hover:text-blue-600 transition-colors">
                        Home
                    </a>
                    <i data-tabler="chevron-right" class="text-slate-500" data-size="14" data-stroke="2"></i>
                    <a href="{{ route('my-booking') }}" wire:navigate
                        class="font-normal text-sm text-slate-500 hover:text-blue-600 transition-colors">
                        My Bookings
                    </a>
                    <i data-tabler="chevron-right" class="text-slate-500" data-size="14" data-stroke="2"></i>
                    <span class="font-semibold text-sm text-slate-500">Flight Booking Details</span>
                </div>
            </div>
        </div>

        <div class="py-6 lg:py-12">
            <div class="container">
                <div class="flex flex-col gap-6">

                    {{-- ── Header ── --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h1 class="text-2xl md:text-3xl font-semibold text-slate-950 leading-9">
                            Booking Detail
                        </h1>
                        @if ($flags['isUpcoming'])
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                                <button class="btn btn-secondary w-full sm:w-auto"
                                    wire:click="$set('showRescheduleModal', true)">
                                    Reschedule
                                </button>
                                <button class="btn btn-red w-full sm:w-auto" wire:click="$set('showCancelModal', true)">
                                    Cancel
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- ── Main Content ── --}}
                    <div class="flex flex-col gap-6 md:gap-9">

                        {{-- ── Flight Details + Pricing ── --}}
                        <div class="flex flex-col lg:flex-row gap-4 md:gap-6">

                            {{-- Flight Details Card --}}
                            <div class="flex-1 card p-4 md:p-5">
                                <div class="flex flex-col gap-4 md:gap-6">

                                    {{-- Booking ID & Status --}}
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-sm md:text-base font-normal text-slate-500 leading-6">
                                            {{ $o->order_number }}
                                            @if ($p['booking_reference'] ?? null)
                                                &nbsp;·&nbsp; Ref: <strong>{{ $p['booking_reference'] }}</strong>
                                            @endif
                                        </div>
                                        @if ($flags['isCancelled'])
                                            <span class="badge badge-error badge-soft">Cancelled</span>
                                        @elseif($flags['isCompleted'])
                                            <span class="badge badge-success badge-soft">Completed</span>
                                        @else
                                            <span class="badge badge-info badge-soft">Upcoming</span>
                                        @endif
                                    </div>

                                    {{-- All Slices Loop --}}
                                    @php
                                        $orderData = $o->data ?? [];
                                        $allSlices = $orderData['slices'] ?? [];
                                        $sliceCount = count($allSlices);
                                    @endphp

                                    @foreach ($allSlices as $sIdx => $sSlice)
                                        @php
                                            $sSeg = $sSlice['segments'][0] ?? [];
                                            $sCarrier = $sSeg['operating_carrier'] ?? [];
                                            $sOrigin = $sSeg['origin'] ?? [];
                                            $sDest = $sSeg['destination'] ?? [];
                                            $sDep = $sSeg['departing_at'] ?? null;
                                            $sArr = $sSeg['arriving_at'] ?? null;
                                            $sDur = $sSeg['duration'] ?? ($sSlice['duration'] ?? '');
                                            $sStops = count($sSlice['segments'] ?? []) - 1;
                                            $sLogo = $sCarrier['logo_symbol_url'] ?? '';
                                            $sAirline = $sCarrier['name'] ?? '—';
                                            $sFno =
                                                ($sCarrier['iata_code'] ?? '') .
                                                ($sSeg['operating_carrier_flight_number'] ?? '');
                                            $sAircraft = $sSeg['aircraft']['name'] ?? null;
                                            $sCabin = $sSeg['passengers'][0]['cabin_class_marketing_name'] ?? null;
                                            $sFareBrand = $sSlice['fare_brand_name'] ?? null;
                                            $sOrigTerm = $sSeg['origin_terminal'] ?? null;
                                            $sDestTerm = $sSeg['destination_terminal'] ?? null;

                                            $sPaxBags = $sSeg['passengers'][0]['baggages'] ?? [];
                                            $sCabinBag = collect($sPaxBags)->firstWhere('type', 'carry_on');
                                            $sCheckedBag = collect($sPaxBags)->firstWhere('type', 'checked');

                                            if ($sliceCount === 1) {
                                                $sLabel = null;
                                            } elseif ($sliceCount > 2) {
                                                $sLabel =
                                                    'Flight ' .
                                                    ($sIdx + 1) .
                                                    ' · ' .
                                                    ($sOrigin['iata_code'] ?? '') .
                                                    ' → ' .
                                                    ($sDest['iata_code'] ?? '');
                                            } else {
                                                $sLabel = $sIdx === 0 ? 'Outbound Flight' : 'Return Flight';
                                            }
                                        @endphp

                                        <div
                                            class="flex flex-col gap-4 @if (!$loop->last) pb-5 border-b border-slate-100 @endif">

                                            {{-- Slice Label Badge --}}
                                            @if ($sLabel)
                                                <span
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-full px-2.5 py-0.5 w-fit">
                                                    <i data-tabler="plane-departure" data-size="11"></i>
                                                    {{ $sLabel }}
                                                </span>
                                            @endif

                                            {{-- Airline Info --}}
                                            <div class="flex items-center gap-3 md:gap-3.5">
                                                <div
                                                    class="w-10 h-10 md:w-[42px] md:h-[42px] rounded-lg overflow-hidden border border-slate-100 shrink-0 flex items-center justify-center bg-slate-50 p-1">
                                                    @if ($sLogo)
                                                        <img src="{{ $sLogo }}" alt="{{ $sAirline }}"
                                                            class="w-full h-full object-contain">
                                                    @else
                                                        <span class="text-xs font-bold text-slate-400">
                                                            {{ $sCarrier['iata_code'] ?? '?' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    <div
                                                        class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                        {{ $sAirline }}
                                                    </div>
                                                    <div
                                                        class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                        {{ $sFno }}
                                                        @if ($sAircraft)
                                                            &nbsp;·&nbsp; {{ $sAircraft }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Flight Route --}}
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 md:gap-6">

                                                {{-- Departure --}}
                                                <div class="flex-1 flex flex-col gap-1">
                                                    <div
                                                        class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                                        {{ $sDep ? \Carbon\Carbon::parse($sDep)->format('M d, Y g:i A') : '—' }}
                                                    </div>
                                                    <div class="text-sm font-normal text-slate-950 leading-5">
                                                        {{ $sOrigin['city_name'] ?? '' }}
                                                        ({{ $sOrigin['iata_code'] ?? '' }})
                                                    </div>
                                                    @if ($sOrigTerm)
                                                        <div class="text-sm font-normal text-slate-500 leading-5">
                                                            Terminal {{ $sOrigTerm }}
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Duration --}}
                                                <div
                                                    class="flex flex-col items-center gap-2 w-full sm:w-auto sm:min-w-[180px]">
                                                    <div class="text-sm font-normal text-slate-500 leading-5">
                                                        {{ $sDur? \Carbon\CarbonInterval::make($sDur)->cascade()->forHumans(['parts' => 2]): '' }}
                                                    </div>
                                                    <div class="relative w-full flex items-center justify-center h-4">
                                                        <div class="absolute w-full h-px bg-slate-200"></div>
                                                        <div
                                                            class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                        </div>
                                                        <div
                                                            class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                        </div>
                                                        <div class="relative z-10 bg-white px-2 leading-none">
                                                            <i data-tabler="plane" class="text-slate-400"
                                                                data-size="18"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm font-normal text-slate-500 leading-5">
                                                        {{ $sStops === 0 ? 'Non-stop' : $sStops . ' stop' . ($sStops > 1 ? 's' : '') }}
                                                    </div>
                                                </div>

                                                {{-- Arrival --}}
                                                <div
                                                    class="flex-1 flex flex-col items-start sm:items-end gap-1 text-left sm:text-right">
                                                    <div
                                                        class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                                        {{ $sArr ? \Carbon\Carbon::parse($sArr)->format('M d, Y g:i A') : '—' }}
                                                    </div>
                                                    <div class="text-sm font-normal text-slate-950 leading-5">
                                                        {{ $sDest['city_name'] ?? '' }}
                                                        ({{ $sDest['iata_code'] ?? '' }})
                                                    </div>
                                                    @if ($sDestTerm)
                                                        <div class="text-sm font-normal text-slate-500 leading-5">
                                                            Terminal {{ $sDestTerm }}
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>

                                            {{-- Cabin & Baggage --}}
                                            <div class="flex flex-col sm:flex-row flex-wrap gap-4 md:gap-6">
                                                @if ($sCabin)
                                                    <div
                                                        class="flex-1 min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Cabin Class</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ $sCabin }}
                                                            @if ($sFareBrand)
                                                                · {{ $sFareBrand }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($sCabinBag || $sCheckedBag)
                                                    <div
                                                        class="flex-1 min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Baggage</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            @if ($sCabinBag)
                                                                {{ $sCabinBag['quantity'] }}× Cabin
                                                            @endif
                                                            @if ($sCabinBag && $sCheckedBag)
                                                                +
                                                            @endif
                                                            @if ($sCheckedBag)
                                                                {{ $sCheckedBag['quantity'] }}× Check-in
                                                                @php
                                                                    $sCheckedKg = null;
                                                                    foreach ($orderData['services'] ?? [] as $svc) {
                                                                        if (($svc['type'] ?? '') === 'baggage') {
                                                                            $sCheckedKg =
                                                                                $svc['metadata']['maximum_weight_kg'] ??
                                                                                null;
                                                                            break;
                                                                        }
                                                                    }
                                                                @endphp
                                                                @if ($sCheckedKg)
                                                                    {{ $sCheckedKg }}kg
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            {{-- Pricing Summary Card --}}
                            <div class="w-full lg:w-[350px] card flex flex-col">
                                <div class="p-4 md:p-5 border-b border-slate-200">
                                    <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                        Pricing Summary
                                    </h2>
                                </div>
                                <div class="flex-1 p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                                    <div class="flex flex-col gap-3 md:gap-3.5">

                                        @php
                                            $baggageServices = collect($services ?? [])->where('type', 'baggage');
                                            $seatServices = collect($services ?? [])->where('type', 'seat');
                                            $otherServices = collect($services ?? [])->whereNotIn('type', [
                                                'baggage',
                                                'seat',
                                            ]);
                                            $servicesTotal = collect($services ?? [])->sum(
                                                fn($s) => (float) ($s['amount'] ?? 0),
                                            );
                                            $grandTotal = $o->amount;
                                            $baseFare = $grandTotal - $servicesTotal;
                                        @endphp

                                        {{-- Base Fare --}}
                                        <div class="flex justify-between items-center gap-2">
                                            <div class="text-xs md:text-sm font-normal text-slate-950 leading-5">Base
                                                Fare</div>
                                            <div
                                                class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">
                                                {{ $o->currency }} {{ number_format($baseFare, 2) }}
                                            </div>
                                        </div>

                                        {{-- Taxes & Fees --}}
                                        @if ($o->tax_amount > 0)
                                            <div class="flex justify-between items-center gap-2">
                                                <div class="text-xs md:text-sm font-normal text-slate-950 leading-5">
                                                    Taxes & Fees</div>
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">
                                                    {{ $o->currency }} {{ number_format($o->tax_amount, 2) }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Baggage --}}
                                        @foreach ($baggageServices as $svc)
                                            <div class="flex justify-between items-center gap-2">
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-950 leading-5 flex items-center gap-1.5">
                                                    <i data-tabler="luggage" data-size="14" class="text-blue-500"></i>
                                                    Extra Baggage
                                                    @if (!empty($svc['weight_kg']))
                                                        <span class="text-slate-400">({{ $svc['weight_kg'] }}kg)</span>
                                                    @endif
                                                    @if (($svc['quantity'] ?? 1) > 1)
                                                        <span class="text-slate-400">× {{ $svc['quantity'] }}</span>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">
                                                    @if ($svc['amount'])
                                                        {{ $svc['currency'] }} {{ number_format($svc['amount'], 2) }}
                                                    @else
                                                        Included
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Seat Selection --}}
                                        @foreach ($seatServices as $svc)
                                            <div class="flex justify-between items-center gap-2">
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-950 leading-5 flex items-center gap-1.5">
                                                    <i data-tabler="armchair" data-size="14"
                                                        class="text-amber-500"></i>
                                                    Seat Selection
                                                    @if (($svc['quantity'] ?? 1) > 1)
                                                        <span class="text-slate-400">× {{ $svc['quantity'] }}</span>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">
                                                    @if ($svc['amount'])
                                                        {{ $svc['currency'] }} {{ number_format($svc['amount'], 2) }}
                                                    @else
                                                        Included
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Other Add-ons --}}
                                        @foreach ($otherServices as $svc)
                                            <div class="flex justify-between items-center gap-2">
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-950 leading-5 flex items-center gap-1.5">
                                                    <i data-tabler="package" data-size="14"
                                                        class="text-slate-400"></i>
                                                    {{ ucfirst($svc['type'] ?? 'Add-on') }}
                                                    @if (($svc['quantity'] ?? 1) > 1)
                                                        <span class="text-slate-400">× {{ $svc['quantity'] }}</span>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">
                                                    @if ($svc['amount'])
                                                        {{ $svc['currency'] }} {{ number_format($svc['amount'], 2) }}
                                                    @else
                                                        Included
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="h-px bg-slate-200"></div>

                                        {{-- Total --}}
                                        <div class="flex justify-between items-center gap-2">
                                            <div
                                                class="text-base md:text-lg font-semibold text-slate-950 leading-6 md:leading-7">
                                                Total
                                            </div>
                                            <div
                                                class="text-base md:text-lg font-semibold leading-6 md:leading-7 whitespace-nowrap {{ $priceColor }}">
                                                {{ $o->currency }} {{ number_format($grandTotal, 2) }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ── Contact Information ── --}}
                        @if (!empty($order['contact']))
                            <div class="card flex flex-col">
                                <div class="p-4 md:p-5 border-b border-slate-200">
                                    <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                        Contact Information</h2>
                                </div>
                                <div class="p-4 md:p-5">
                                    <div class="flex flex-col sm:flex-row flex-wrap gap-4 md:gap-6">
                                        @if (!empty($order['contact']['email']))
                                            <div class="flex-1 min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                                                <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                    Email address</div>
                                                <div
                                                    class="text-sm md:text-base font-semibold text-slate-950 leading-6 break-words">
                                                    {{ $order['contact']['email'] }}
                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($order['contact']['phone']))
                                            <div class="flex-1 min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                                                <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                    Phone number</div>
                                                <div
                                                    class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                    {{ $order['contact']['phone'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- ── Passenger Details ── --}}
                        @if (!empty($order['passengers']))
                            <div class="card flex flex-col">
                                <div class="p-4 md:p-5 border-b border-slate-200">
                                    <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                        Passenger Details</h2>
                                </div>
                                <div class="p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                                    @foreach ($order['passengers'] as $pax)
                                        @if (!$loop->first)
                                            <div class="h-px bg-slate-200"></div>
                                        @endif
                                        <div class="flex flex-col gap-2.5">
                                            <h3
                                                class="text-base md:text-lg font-semibold text-slate-950 leading-6 md:leading-7">
                                                Passenger {{ $loop->iteration }}
                                                <span
                                                    class="text-sm font-normal text-slate-400 capitalize">({{ $pax['type'] ?? 'adult' }})</span>
                                            </h3>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 md:gap-x-6 gap-y-5">
                                                @if (!empty($pax['title']) || !empty($pax['first_name']))
                                                    <div class="flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Name</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ trim(ucfirst($pax['title'] ?? '') . ' ' . ($pax['first_name'] ?? '') . ' ' . ($pax['last_name'] ?? '')) }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($pax['gender']))
                                                    <div class="flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Gender</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ ucfirst($pax['gender']) }}</div>
                                                    </div>
                                                @endif
                                                @if (!empty($pax['born_on']))
                                                    <div class="flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Date of Birth</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ $pax['born_on'] }}</div>
                                                    </div>
                                                @endif
                                                @if (!empty($pax['nationality']))
                                                    <div class="flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Nationality</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ $pax['nationality'] }}</div>
                                                    </div>
                                                @endif
                                                @if (!empty($pax['passport_number']))
                                                    <div class="flex flex-col gap-1">
                                                        <div
                                                            class="text-xs md:text-sm font-normal text-slate-500 leading-5">
                                                            Passport</div>
                                                        <div
                                                            class="text-sm md:text-base font-semibold text-slate-950 leading-6">
                                                            {{ $pax['passport_number'] }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- ── Cancellation Policy ── --}}
                        @if (!empty($order['conditions']))
                            <div class="card flex flex-col">
                                <div class="p-4 md:p-5 border-b border-slate-200">
                                    <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">
                                        Cancellation Policy</h2>
                                </div>
                                <div class="p-4 md:p-5">
                                    <div class="flex flex-col gap-3 md:gap-3.5">
                                        @foreach ($order['conditions'] as $condition)
                                            <div class="flex items-start gap-2.5">
                                                <i data-tabler="check" class="w-5 h-5 text-green-700 shrink-0 mt-0.5"
                                                    data-size="20"></i>
                                                <div class="text-xs md:text-sm font-normal text-slate-900 leading-5">
                                                    {{ $condition }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Modal --}}
        <dialog class="modal" @if ($showCancelModal) open @endif>
            <div
                class="modal-box max-w-[500px] mx-auto flex flex-col p-0 rounded-2xl overflow-hidden bg-white shadow-md">
                <div class="px-5 pt-9 pb-5 flex flex-col gap-6">
                    <div class="flex flex-col items-center gap-3.5">
                        <div
                            class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 shadow-sm">
                            <i data-tabler="alert-triangle" data-stroke="2"></i>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <h3
                                class="text-2xl md:text-3xl font-bold text-slate-950 leading-8 md:leading-10 text-center">
                                Cancel Booking</h3>
                            <p
                                class="text-sm md:text-base font-medium text-slate-500 leading-5 md:leading-6 text-center">
                                Are you sure you want to cancel this booking? Cancellation charges may apply as per
                                airline policy, and the refund (if applicable) will be processed to your original
                                payment method within 10 days.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button class="btn btn-primary w-full bg-red-600 hover:bg-red-700 border-red-600"
                            wire:click="confirmCancel" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="confirmCancel">Yes, Cancel</span>
                            <span wire:loading wire:target="confirmCancel">Processing...</span>
                        </button>
                        <button
                            class="w-full text-center text-sm font-semibold text-slate-500 leading-5 hover:text-slate-700 transition-colors"
                            wire:click="$set('showCancelModal', false)">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </dialog>

        {{-- Reschedule Modal --}}
        <dialog class="modal" @if ($showRescheduleModal) open @endif>
            <div
                class="modal-box max-w-[500px] mx-auto flex flex-col p-0 rounded-2xl overflow-hidden bg-white shadow-md">
                <div class="px-5 pt-9 pb-5 flex flex-col gap-6">
                    <div class="flex flex-col items-center gap-3.5">
                        <div
                            class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                            <i data-tabler="calendar-event" data-stroke="2"></i>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <h3
                                class="text-2xl md:text-3xl font-bold text-slate-950 leading-8 md:leading-10 text-center">
                                Reschedule Flight</h3>
                            <p
                                class="text-sm md:text-base font-medium text-slate-500 leading-5 md:leading-6 text-center">
                                To reschedule your flight, please contact our support team. Rescheduling fees
                                may apply as per the airline's fare conditions.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button
                            class="w-full text-center text-sm font-semibold text-slate-500 leading-5 hover:text-slate-700 transition-colors"
                            wire:click="$set('showRescheduleModal', false)">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </dialog>
    @else
        {{-- ════════════════════════════════════════
             SKELETON LOADER
        ════════════════════════════════════════ --}}
        <div class="py-6 lg:py-12">
            <div class="container">
                <div class="flex flex-col gap-6">

                    {{-- Header Skeleton --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="skeleton h-8 w-48 rounded-lg"></div>
                        <div class="flex gap-3">
                            <div class="skeleton h-9 w-28 rounded-lg"></div>
                            <div class="skeleton h-9 w-24 rounded-lg"></div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-6 md:gap-9">

                        {{-- Flight Details + Pricing Skeleton --}}
                        <div class="flex flex-col lg:flex-row gap-4 md:gap-6">

                            {{-- Flight Card Skeleton --}}
                            <div class="flex-1 card p-4 md:p-5">
                                <div class="flex flex-col gap-4 md:gap-6">

                                    {{-- Order number + badge --}}
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="skeleton h-4 w-48 rounded"></div>
                                        <div class="skeleton h-6 w-20 rounded-full"></div>
                                    </div>

                                    {{-- Airline --}}
                                    <div class="flex items-center gap-3">
                                        <div class="skeleton w-10 h-10 rounded-lg shrink-0"></div>
                                        <div class="flex flex-col gap-2 flex-1">
                                            <div class="skeleton h-4 w-32 rounded"></div>
                                            <div class="skeleton h-3 w-20 rounded"></div>
                                        </div>
                                    </div>

                                    {{-- Route --}}
                                    <div class="flex flex-row items-center justify-between gap-4">
                                        <div class="flex flex-col gap-2 flex-1">
                                            <div class="skeleton h-6 w-40 rounded"></div>
                                            <div class="skeleton h-4 w-28 rounded"></div>
                                            <div class="skeleton h-3 w-20 rounded"></div>
                                        </div>
                                        <div class="flex flex-col items-center gap-2 min-w-[120px]">
                                            <div class="skeleton h-3 w-14 rounded"></div>
                                            <div class="skeleton h-px w-full rounded"></div>
                                            <div class="skeleton h-3 w-10 rounded"></div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2 flex-1">
                                            <div class="skeleton h-6 w-40 rounded"></div>
                                            <div class="skeleton h-4 w-28 rounded"></div>
                                            <div class="skeleton h-3 w-20 rounded"></div>
                                        </div>
                                    </div>

                                    {{-- Cabin & Baggage --}}
                                    <div class="flex gap-6">
                                        <div class="flex flex-col gap-2 flex-1">
                                            <div class="skeleton h-3 w-20 rounded"></div>
                                            <div class="skeleton h-5 w-28 rounded"></div>
                                        </div>
                                        <div class="flex flex-col gap-2 flex-1">
                                            <div class="skeleton h-3 w-16 rounded"></div>
                                            <div class="skeleton h-5 w-36 rounded"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Pricing Card Skeleton --}}
                            <div class="w-full lg:w-[350px] card flex flex-col">
                                <div class="p-4 md:p-5 border-b border-slate-200">
                                    <div class="skeleton h-6 w-36 rounded"></div>
                                </div>
                                <div class="flex-1 p-4 md:p-5 flex flex-col gap-4">
                                    <div class="flex justify-between items-center">
                                        <div class="skeleton h-4 w-24 rounded"></div>
                                        <div class="skeleton h-4 w-16 rounded"></div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="skeleton h-4 w-24 rounded"></div>
                                        <div class="skeleton h-4 w-16 rounded"></div>
                                    </div>
                                    <div class="h-px bg-slate-100"></div>
                                    <div class="flex justify-between items-center">
                                        <div class="skeleton h-5 w-16 rounded"></div>
                                        <div class="skeleton h-5 w-20 rounded"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Contact Skeleton --}}
                        <div class="card flex flex-col">
                            <div class="p-4 md:p-5 border-b border-slate-200">
                                <div class="skeleton h-6 w-44 rounded"></div>
                            </div>
                            <div class="p-4 md:p-5">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <div class="flex flex-col gap-2 flex-1">
                                        <div class="skeleton h-3 w-24 rounded"></div>
                                        <div class="skeleton h-5 w-40 rounded"></div>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <div class="skeleton h-3 w-24 rounded"></div>
                                        <div class="skeleton h-5 w-36 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Passenger Skeleton --}}
                        <div class="card flex flex-col">
                            <div class="p-4 md:p-5 border-b border-slate-200">
                                <div class="skeleton h-6 w-40 rounded"></div>
                            </div>
                            <div class="p-4 md:p-5 flex flex-col gap-5">
                                <div class="skeleton h-5 w-32 rounded"></div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-5">
                                    @for ($i = 0; $i < 5; $i++)
                                        <div class="flex flex-col gap-2">
                                            <div class="skeleton h-3 w-20 rounded"></div>
                                            <div class="skeleton h-5 w-28 rounded"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Conditions Skeleton --}}
                        <div class="card flex flex-col">
                            <div class="p-4 md:p-5 border-b border-slate-200">
                                <div class="skeleton h-6 w-44 rounded"></div>
                            </div>
                            <div class="p-4 md:p-5 flex flex-col gap-3">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="flex items-center gap-2.5">
                                        <div class="skeleton w-5 h-5 rounded-full shrink-0"></div>
                                        <div class="skeleton h-4 rounded"
                                            style="width: {{ $i === 0 ? '70%' : ($i === 1 ? '55%' : '45%') }}"></div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endif

</div>
