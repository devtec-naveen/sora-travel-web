<div wire:init="loadFlights">
    <x-loader message="Please Wait, We are searching for the flights on this route"
        targets="loadFlights,selectFlight,sortBy,maxPrice,stops,airlines,refundableOnly,clearFilters,removeAirline" />
    <main class="bg-slate-50">
        <section class="search-panel-inner py-5 bg-gradient-to-b from-[#075fc6] to-[#0d529b]">
            <div class="container">
                <div class="search-tab-content">
                    <x-frontend.flight-search-tabs />
                </div>
            </div>
        </section>
        <div class="listing-area py-10 lg:py-16">
            <div class="container">
                <div class="md:hidden mb-6">
                    <button id="open-filter" class="w-full btn btn-primary flex items-center justify-center gap-2">
                        <i data-tabler="adjustments-horizontal" data-size="18"></i>
                        Filter & Sort
                    </button>
                </div>
                <div class="flex flex-col md:flex-row gap-6">
                    <div id="filter-backdrop" class="fixed inset-0 bg-slate-900/40 z-[99] hidden md:hidden"></div>
                    <div id="filter-sidebar"
                        class="rounded-none shadow-sm border border-slate-200 lg:rounded-lg fixed inset-y-0 left-0 z-[100]
                               w-full h-screen translate-x-[-100%] transition-transform duration-300
                               md:relative md:translate-x-0 md:z-auto md:w-[317px] md:h-fit md:block
                               bg-white overflow-hidden">
                        <div class="flex flex-col h-full md:card md:p-5 md:block">
                            <div
                                class="flex justify-between items-center p-5 border-b border-slate-100 md:p-0 md:border-none md:mb-6 shrink-0">
                                <div class="flex items-center gap-2">
                                    <i data-tabler="adjustments-horizontal" data-size="20" class="text-slate-950"></i>
                                    <h3 class="font-semibold text-lg text-slate-950">Filters</h3>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button wire:click="clearFilters"
                                        class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors uppercase">
                                        Clear All
                                    </button>
                                    <button id="close-filter" class="md:hidden text-slate-400 hover:text-slate-950">
                                        <i data-tabler="x" data-size="22"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto p-5 md:p-0 space-y-8 md:overflow-visible">
                                <div class="form-control">
                                    <label class="form-label">Sort By</label>
                                    <div class="relative mt-1">
                                        <select class="form-input appearance-none pr-10" wire:model.live="sortBy">
                                            <option value="">Default</option>
                                            <option value="price_low_high">Price (Low to High)</option>
                                            <option value="price_high_low">Price (High to Low)</option>
                                            <option value="duration">Duration (Shortest)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                            <img src="{{ asset('assets/images/dropdown.svg') }}" alt="icon"
                                                class="w-[15px]" />
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="form-label">Pricing</span>
                                    </div>
                                    <div class="px-1">
                                        <input type="range" min="{{ $minPossiblePrice }}"
                                            max="{{ $maxPossiblePrice }}" wire:model.live.debounce.400ms="maxPrice"
                                            class="range range-xs range-primary w-full" />
                                    </div>
                                    <div class="flex justify-between text-sm font-bold text-primary-950">
                                        <span>&dollar; {{ number_format($minPossiblePrice) }}</span>
                                        <span>&dollar; {{ number_format($maxPossiblePrice) }}</span>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="form-label">Stops</h4>
                                    <div class="flex flex-col gap-3">
                                        @foreach ([0 => 'Non-stop', 1 => '1 Stop', 2 => '2+ Stops'] as $val => $label)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox" value="{{ $val }}"
                                                    wire:model.live="stops" class="checkbox shrink-0" />
                                                <span
                                                    class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                @if (!empty($availableAirlines))
                                    <div class="space-y-4">
                                        <h4 class="form-label">Airlines</h4>
                                        <div class="flex flex-col gap-3">
                                            @foreach ($availableAirlines as $airline)
                                                <label class="flex items-center gap-3 cursor-pointer group">
                                                    <input type="checkbox" value="{{ $airline }}"
                                                        wire:model.live="airlines" class="checkbox shrink-0" />
                                                    <span
                                                        class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">{{ $airline }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-2 md:mt-6 mb-6">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model.live="refundableOnly"
                                            class="checkbox shrink-0" />
                                        <span
                                            class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Refundable
                                            only</span>
                                    </label>
                                </div>
                            </div>
                            <div class="p-5 border-t border-slate-100 md:hidden shrink-0">
                                <button id="apply-filter" class="w-full btn btn-primary">Apply Filters</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 space-y-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h4 class="font-semibold text-lg sm:text-xl leading-7 sm:leading-8 text-slate-950">
                                {{ $total }} flight{{ $total !== 1 ? 's' : '' }} found
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @if ($sortBy)
                                    <span class="tag tag-gray">
                                        Sort: {{ str_replace('_', ' ', $sortBy) }}
                                        <button wire:click="$set('sortBy', '')"
                                            class="ml-1 hover:text-red-500">×</button>
                                    </span>
                                @endif
                                @foreach (is_array($stops) ? $stops : [] as $s)
                                    <span class="tag tag-gray">
                                        {{ (int) $s === 0 ? 'Non-stop' : ((int) $s === 1 ? '1 Stop' : '2+ Stops') }}
                                        <button wire:click="removeStop({{ (int) $s }})"
                                            class="ml-1 hover:text-red-500">×</button>
                                    </span>
                                @endforeach
                                @foreach (is_array($airlines) ? $airlines : [] as $a)
                                    <span class="tag tag-gray">
                                        {{ $a }}
                                        <button wire:click="removeAirline('{{ addslashes($a) }}')"
                                            class="ml-1 hover:text-red-500">×</button>
                                    </span>
                                @endforeach
                                @if ($refundableOnly)
                                    <span class="tag tag-gray">
                                        Refundable
                                        <button wire:click="$set('refundableOnly', false)"
                                            class="ml-1 hover:text-red-500">×</button>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3.5">
                            <div wire:loading.block
                                wire:target="loadFlights,selectFlight,sortBy,maxPrice,stops,airlines,refundableOnly,clearFilters,removeAirline">
                                @for ($i = 0; $i < 2; $i++)
                                    <div class="card p-4 mt-5">
                                        <div class="flex flex-col gap-3">
                                            <div class="flex items-center gap-4">
                                                <div class="skeleton w-11 h-11 rounded-xl shrink-0"></div>
                                                <div class="flex flex-col gap-2 flex-1">
                                                    <div class="skeleton h-3.5 w-36 rounded"></div>
                                                    <div class="skeleton h-3 w-24 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex flex-col gap-2">
                                                    <div class="skeleton h-6 w-20 rounded"></div>
                                                    <div class="skeleton h-3 w-10 rounded"></div>
                                                </div>
                                                <div class="flex-1 flex flex-col items-center gap-2 max-w-[200px]">
                                                    <div class="skeleton h-3 w-20 rounded"></div>
                                                    <div class="skeleton h-1 w-full rounded"></div>
                                                    <div class="skeleton h-3 w-14 rounded"></div>
                                                </div>
                                                <div class="flex flex-col items-end gap-2">
                                                    <div class="skeleton h-6 w-20 rounded"></div>
                                                    <div class="skeleton h-3 w-10 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <div class="skeleton h-6 w-24 rounded-full"></div>
                                                <div class="skeleton h-6 w-20 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            @if ($total === 0)
                                <div class="card p-10 text-center" wire:loading.remove>
                                    <i data-tabler="plane-off" data-size="48"
                                        class="text-slate-300 mx-auto mb-4"></i>
                                    <p class="text-slate-500 font-medium">No flights match your filters.</p>
                                    <button wire:click="clearFilters" class="btn btn-outline btn-sm mt-4">Clear
                                        Filters</button>
                                </div>
                            @else
                                @foreach ($flights as $index => $flight)
                                    @php
                                        $slice = $flight['slices'][0];
                                        $segment = $slice['segments'][0];
                                        $airline = $segment['operating_carrier']['name'] ?? '';
                                        $airlineCode = $segment['operating_carrier']['iata_code'] ?? '';
                                        $logo = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                        $flightNumber = $segment['operating_carrier_flight_number'] ?? '';
                                        $bags = $segment['passengers'][0]['baggages'] ?? [];
                                        $aircraft = $segment['aircraft']['name'] ?? '';
                                        $isRefundable =
                                            $flight['conditions']['refund_before_departure']['allowed'] ?? false;
                                        $price = $flight['total_amount'] ?? '';
                                        $currency = $flight['total_currency'] ?? '';
                                        $totalSeconds = 0;
                                        $durationHuman = '';

                                        if ($tripType === 'multi_city') {
                                            $totalStops = 0;
                                            foreach ($flight['slices'] as $sl) {
                                                $totalStops += count($sl['segments']) - 1;
                                            }
                                            $totalStops += count($flight['slices']) - 1;
                                            $stops = $totalStops;
                                            foreach ($flight['slices'] as $sl) {
                                                foreach ($sl['segments'] as $sg) {
                                                    try {
                                                        $totalSeconds += \Carbon\CarbonInterval::make(
                                                            $sg['duration'] ?? 'PT0S',
                                                        )->totalSeconds;
                                                    } catch (\Exception $e) {
                                                    }
                                                }
                                            }
                                            $durationHuman =
                                                intdiv($totalSeconds, 3600) .
                                                'h ' .
                                                intdiv($totalSeconds % 3600, 60) .
                                                'm';
                                            $lastSlice = $flight['slices'][count($flight['slices']) - 1];
                                            $lastSegment = $lastSlice['segments'][count($lastSlice['segments']) - 1];
                                            $arrival = $lastSegment['arriving_at'];
                                            $destination = $lastSegment['destination']['iata_code'] ?? '';
                                        } else {
                                            $lastSeg = $slice['segments'][count($slice['segments']) - 1];
                                            $arrival = $lastSeg['arriving_at'];
                                            $destination = $lastSeg['destination']['iata_code'] ?? '';
                                            $stops = count($slice['segments']) - 1;
                                            try {
                                                $durationHuman = \Carbon\CarbonInterval::make(
                                                    $segment['duration'] ?? 'PT0S',
                                                )
                                                    ->cascade()
                                                    ->forHumans();
                                            } catch (\Exception $e) {
                                                $durationHuman = '';
                                            }
                                        }
                                    @endphp

                                    <div wire:loading.remove class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <div class="flex-1 flex flex-col gap-3">

                                                {{-- Airline info — same for all --}}
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="{{ $logo }}" alt="{{ $airline }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="font-semibold text-base text-slate-950">{{ $airline }}</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500">{{ $airlineCode }}
                                                            {{ $flightNumber }}</span>
                                                    </div>
                                                </div>
                                                @if ($tripType === 'multi_city')
                                                    <div class="flex flex-col divide-y divide-slate-100 -mx-4">
                                                        @foreach ($flight['slices'] as $sliceIdx => $sl)
                                                            @php
                                                                $slFirst = $sl['segments'][0];
                                                                $slLast = $sl['segments'][count($sl['segments']) - 1];
                                                                $slSecs = 0;
                                                                foreach ($sl['segments'] as $sg) {
                                                                    try {
                                                                        $slSecs += \Carbon\CarbonInterval::make(
                                                                            $sg['duration'] ?? 'PT0S',
                                                                        )->totalSeconds;
                                                                    } catch (\Exception $e) {
                                                                    }
                                                                }
                                                                $slH = intdiv($slSecs, 3600);
                                                                $slM = intdiv($slSecs % 3600, 60);
                                                                $slStops = count($sl['segments']) - 1;
                                                            @endphp
                                                            <div
                                                                class="flex flex-row items-center justify-between gap-6 sm:gap-4 px-4 py-3">
                                                                <div class="flex flex-col items-start">
                                                                    <span
                                                                        class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                                        {{ \Carbon\Carbon::parse($slFirst['departing_at'])->format('h:i A') }}
                                                                    </span>
                                                                    <span class="font-normal text-sm text-slate-500">
                                                                        {{ $slFirst['origin']['iata_code'] ?? '' }}
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                                    <span
                                                                        class="font-normal text-xs text-slate-500">{{ $slH }}h
                                                                        {{ $slM }}m</span>
                                                                    <div
                                                                        class="relative w-full flex items-center justify-center h-4">
                                                                        <div class="absolute w-full h-px bg-slate-200">
                                                                        </div>
                                                                        <div
                                                                            class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                                        </div>
                                                                        <div
                                                                            class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                                        </div>
                                                                        <div
                                                                            class="relative z-10 bg-white px-2 leading-none">
                                                                            <i data-tabler="plane"
                                                                                class="text-slate-400"
                                                                                data-size="18"></i>
                                                                        </div>
                                                                    </div>
                                                                    <span class="font-normal text-xs text-slate-500">
                                                                        {{ $slStops === 0 ? 'Non-stop' : $slStops . ' stop' . ($slStops > 1 ? 's' : '') }}
                                                                    </span>
                                                                </div>
                                                                <div class="flex flex-col items-end">
                                                                    <span
                                                                        class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                                        {{ \Carbon\Carbon::parse($slLast['arriving_at'])->format('h:i A') }}
                                                                    </span>
                                                                    <span
                                                                        class="font-normal text-sm text-slate-500 text-right">
                                                                        {{ $slLast['destination']['iata_code'] ?? '' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div
                                                        class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                        <div class="flex flex-col items-start">
                                                            <span
                                                                class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                                {{ \Carbon\Carbon::parse($segment['departing_at'])->format('h:i A') }}
                                                            </span>
                                                            <span class="font-normal text-sm text-slate-500">
                                                                {{ $segment['origin']['iata_code'] ?? '' }}
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                            <span
                                                                class="font-normal text-xs text-slate-500">{{ $durationHuman }}</span>
                                                            <div
                                                                class="relative w-full flex items-center justify-center h-4">
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
                                                            <span class="font-normal text-xs text-slate-500">
                                                                {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex flex-col items-end">
                                                            <span
                                                                class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                                {{ \Carbon\Carbon::parse($arrival)->format('h:i A') }}
                                                            </span>
                                                            <span
                                                                class="font-normal text-sm text-slate-500 text-right">{{ $destination }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="flex flex-wrap gap-2.5">
                                                    @foreach ($bags as $bag)
                                                        @php
                                                            $bagLabel =
                                                                $bag['type'] === 'checked'
                                                                    ? 'Bag for Check-in'
                                                                    : ($bag['type'] === 'carry_on'
                                                                        ? 'Cabin Bag'
                                                                        : ucfirst($bag['type']));
                                                            $bagIcon =
                                                                $bag['type'] === 'checked'
                                                                    ? asset('assets/images/checked_bag.svg')
                                                                    : asset('assets/images/carry_on.svg');
                                                        @endphp
                                                        <div class="tag tag-gray flex items-center gap-1.5">
                                                            <img src="{{ $bagIcon }}" alt="icon"
                                                                class="w-[22px]" />
                                                            <span>{{ $bag['quantity'] }} {{ $bagLabel }}</span>
                                                        </div>
                                                    @endforeach
                                                    @if ($aircraft)
                                                        <div class="tag tag-gray">
                                                            <span>Aircraft: {{ $aircraft }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($isRefundable)
                                                        <div class="tag tag-green">
                                                            <img src="{{ asset('assets/images/checked.svg') }}"
                                                                alt="icon" class="w-[15px]" />
                                                            <span>Refundable</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between lg:min-w-[153px] gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span class="font-normal text-sm text-slate-500">From</span>
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">
                                                        {{ $currency }} {{ number_format((float) $price, 2) }}
                                                    </span>
                                                    <span class="font-normal text-sm text-slate-500">
                                                        {{ $tripType === 'multi_city' ? 'total for all legs' : 'per person' }}
                                                    </span>
                                                </div>
                                                <button wire:click="selectFlight({{ $index }})"
                                                    class="btn btn-primary whitespace-nowrap btn-sm">
                                                    Select Flight
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if (count($flights) < $total)
                                    <div wire:loading.remove x-data="{ loading: false }"
                                        x-intersect="
                                            if (!loading) {
                                                loading = true;
                                                $wire.loadMore().then(() => loading = false);
                                            }
                                        "
                                        class="h-16 flex items-center justify-center">
                                        <div class="flex justify-center items-center">
                                            <div
                                                class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div>
        @php
            $sf = $selectedFlight ?? [];
            $sfSlices = $sf['slices'] ?? [];
            $sfSlice = $sfSlices[0] ?? [];
            $sfSegment = $sfSlice['segments'][0] ?? [];
            $sfAirline = $sfSegment['operating_carrier']['name'] ?? '';
            $sfCode = $sfSegment['operating_carrier']['iata_code'] ?? '';
            $sfLogo = $sfSegment['operating_carrier']['logo_symbol_url'] ?? '';
            $sfFlightNo = $sfSegment['operating_carrier_flight_number'] ?? '';
            $sfDep = $sfSegment['departing_at'] ?? null;
            $sfOrigin = $sfSegment['origin']['iata_code'] ?? null;
            $sfOriginCity = $sfSegment['origin']['city_name'] ?? $sfOrigin;
            $sfAircraft = $sfSegment['aircraft']['name'] ?? '';
            $sfPrice = $sf['total_amount'] ?? '';
            $sfCurrency = $sf['total_currency'] ?? '';
            $sfBags = $sfSegment['passengers'][0]['baggages'] ?? [];
            $sfCabin = $sfSegment['passengers'][0]['cabin_class_marketing_name'] ?? 'Economy';
            $sfRefundable = $sf['conditions']['refund_before_departure']['allowed'] ?? false;
            $sfChangeable = $sf['conditions']['change_before_departure']['allowed'] ?? false;
            $sfChangeFee = $sf['conditions']['change_before_departure']['penalty_amount'] ?? null;
            $sfChangeCurr = $sf['conditions']['change_before_departure']['penalty_currency'] ?? '';
            $sfTripType = $tripType;

            if ($sfTripType === 'multi_city' && !empty($sfSlices)) {
                $sfLastSlice = $sfSlices[count($sfSlices) - 1];
                $sfLastSegment = $sfLastSlice['segments'][count($sfLastSlice['segments']) - 1];
                $sfArr = $sfLastSegment['arriving_at'] ?? null;
                $sfDest = $sfLastSegment['destination']['iata_code'] ?? '';
                $sfDestCity = $sfLastSegment['destination']['city_name'] ?? $sfDest;
                $sfTotalStops = 0;
                foreach ($sfSlices as $sl) {
                    $sfTotalStops += count($sl['segments']) - 1;
                }
                $sfTotalStops += count($sfSlices) - 1;
                $sfStops = $sfTotalStops;
                $sfTotalSecs = 0;
                foreach ($sfSlices as $sl) {
                    foreach ($sl['segments'] as $sg) {
                        try {
                            $sfTotalSecs += \Carbon\CarbonInterval::make($sg['duration'] ?? 'PT0S')->totalSeconds;
                        } catch (\Exception $e) {
                        }
                    }
                }
                $sfDurationHuman = intdiv($sfTotalSecs, 3600) . 'h ' . intdiv($sfTotalSecs % 3600, 60) . 'm';
            } else {
                $sfArr = $sfSegment['arriving_at'] ?? null;
                $sfDest = $sfSegment['destination']['iata_code'] ?? '';
                $sfDestCity = $sfSegment['destination']['city_name'] ?? $sfDest;
                $sfStops = isset($sfSlice['segments']) ? count($sfSlice['segments']) - 1 : 0;
                try {
                    $sfDurationHuman = \Carbon\CarbonInterval::make($sfSegment['duration'] ?? 'PT0S')
                        ->cascade()
                        ->forHumans();
                } catch (\Exception $e) {
                    $sfDurationHuman = '';
                }
            }
        @endphp

        <x-frontend.modal :header="true" id="flight_details_modal" headerText="Flight Details">
            <div class="p-6 space-y-8 max-h-[70vh] overflow-y-auto">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                        <img src="{{ $sfLogo }}" alt="{{ $sfAirline }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-base text-slate-950">{{ $sfAirline }}</span>
                        <span class="font-normal text-sm text-slate-500">{{ $sfCode }}
                            {{ $sfFlightNo }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-semibold text-lg text-slate-950">Itinerary</h4>
                    @if ($sfTripType === 'multi_city' && !empty($sfSlices))
                        <div class="flex flex-col divide-y divide-slate-100">
                            @foreach ($sfSlices as $slIdx => $sl)
                                @php
                                    $slFirst = $sl['segments'][0];
                                    $slLast = $sl['segments'][count($sl['segments']) - 1];
                                    $slSecs = 0;
                                    foreach ($sl['segments'] as $sg) {
                                        try {
                                            $slSecs += \Carbon\CarbonInterval::make($sg['duration'] ?? 'PT0S')
                                                ->totalSeconds;
                                        } catch (\Exception $e) {
                                        }
                                    }
                                @endphp
                                <div class="py-3">
                                    <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Leg
                                        {{ $slIdx + 1 }}</p>
                                    <div class="flex flex-row items-center justify-between gap-4">
                                        <div class="flex flex-col items-start">
                                            <span class="font-semibold text-xl leading-8 text-slate-950">
                                                {{ \Carbon\Carbon::parse($slFirst['departing_at'])->format('h:i A') }}
                                            </span>
                                            <span class="font-normal text-sm text-slate-500">
                                                {{ $slFirst['origin']['city_name'] ?? '' }}
                                                ({{ $slFirst['origin']['iata_code'] ?? '' }})
                                            </span>
                                        </div>
                                        <div
                                            class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                            <span class="font-normal text-xs text-slate-500">
                                                {{ intdiv($slSecs, 3600) }}h {{ intdiv($slSecs % 3600, 60) }}m
                                            </span>
                                            <div class="relative w-full flex items-center justify-center h-4">
                                                <div class="absolute w-full h-px bg-slate-200"></div>
                                                <div class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                </div>
                                                <div class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-200">
                                                </div>
                                                <div class="relative z-10 bg-white px-2 leading-none">
                                                    <i data-tabler="plane" class="text-slate-400" data-size="18"></i>
                                                </div>
                                            </div>
                                            <span class="font-normal text-xs text-slate-500">
                                                {{ count($sl['segments']) - 1 === 0 ? 'Non-stop' : count($sl['segments']) - 1 . ' stop(s)' }}
                                            </span>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-xl leading-8 text-slate-950">
                                                {{ \Carbon\Carbon::parse($slLast['arriving_at'])->format('h:i A') }}
                                            </span>
                                            <span class="font-normal text-sm text-slate-500 text-right">
                                                {{ $slLast['destination']['city_name'] ?? '' }}
                                                ({{ $slLast['destination']['iata_code'] ?? '' }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                            <div class="flex flex-col items-start">
                                <span class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                    {{ $sfDep ? \Carbon\Carbon::parse($sfDep)->format('h:i A') : '' }}
                                </span>
                                <span class="font-normal text-sm text-slate-500">{{ $sfOriginCity }}
                                    {{ $sfOrigin ? "($sfOrigin)" : '' }}</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                <span class="font-normal text-xs text-slate-500">{{ $sfDurationHuman }}</span>
                                <div class="relative w-full flex items-center justify-center h-4">
                                    <div class="absolute w-full h-px bg-slate-200"></div>
                                    <div class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                    <div class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                    <div class="relative z-10 bg-white px-2 leading-none">
                                        <i data-tabler="plane" class="text-slate-400" data-size="18"></i>
                                    </div>
                                </div>
                                <span class="font-normal text-xs text-slate-500">{{ $sfStops }} stop(s)</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                    {{ $sfArr ? \Carbon\Carbon::parse($sfArr)->format('h:i A') : '' }}
                                </span>
                                <span class="font-normal text-sm text-slate-500 text-right">{{ $sfDestCity }}
                                    {{ $sfDest ? "($sfDest)" : '' }}</span>
                            </div>
                        </div>
                        @if ($sfStops > 0)
                            <div class="space-y-2 mt-2">
                                @foreach ($sfSlice['segments'] ?? [] as $seg)
                                    <div
                                        class="flex items-center gap-3 bg-slate-50 rounded-lg px-4 py-3 text-sm text-slate-600">
                                        <i data-tabler="map-pin" data-size="15" class="text-slate-400"></i>
                                        <span>
                                            {{ $seg['origin']['city_name'] ?? '' }}
                                            ({{ $seg['origin']['iata_code'] ?? '' }})
                                            → {{ $seg['destination']['city_name'] ?? '' }}
                                            ({{ $seg['destination']['iata_code'] ?? '' }})
                                        </span>
                                        <span class="ml-auto text-slate-400">
                                            {{ \Carbon\Carbon::parse($seg['departing_at'])->format('h:i A') }}
                                            – {{ \Carbon\Carbon::parse($seg['arriving_at'])->format('h:i A') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <hr />

                <div class="space-y-4">
                    <h4 class="font-semibold text-lg text-slate-950">Flight Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Aircraft</span>
                            <span class="font-semibold text-base text-slate-950">{{ $sfAircraft ?: 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Cabin Class</span>
                            <span class="font-semibold text-base text-slate-950">{{ $sfCabin }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Baggage Allowance</span>
                            <div class="flex flex-wrap gap-2">
                                @forelse($sfBags as $bag)
                                    <span class="font-semibold text-base text-slate-950">{{ $bag['quantity'] }} ×
                                        {{ $bag['type'] }}</span>
                                @empty
                                    <span class="font-semibold text-base text-slate-950">N/A</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Refundable</span>
                            <span
                                class="font-semibold text-base {{ $sfRefundable ? 'text-green-600' : 'text-red-500' }}">
                                {{ $sfRefundable ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Date Change</span>
                            <span
                                class="font-semibold text-base {{ $sfChangeable ? 'text-green-600' : 'text-red-500' }}">
                                @if ($sfChangeable)
                                    Yes {{ $sfChangeFee ? '(' . $sfChangeCurr . ' ' . $sfChangeFee . ' fee)' : '' }}
                                @else
                                    Not Allowed
                                @endif
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-normal text-sm text-slate-500">Departure Date</span>
                            <span class="font-semibold text-base text-slate-950">
                                {{ $sfDep ? \Carbon\Carbon::parse($sfDep)->format('D, d M Y') : '' }}
                            </span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="space-y-4">
                    <h4 class="font-semibold text-lg text-slate-950">Fare Rules</h4>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <img src="{{ asset('assets/images/checked.svg') }}" alt="icon" class="w-[15px]" />
                            <span class="font-normal text-sm text-slate-700 leading-relaxed">
                                {{ $sfRefundable ? 'Cancellation allowed before departure with refund' : 'Non-refundable ticket' }}
                            </span>
                        </div>
                        <div class="flex gap-3">
                            <i data-tabler="{{ $sfChangeable ? 'check' : 'x' }}"
                                class="{{ $sfChangeable ? 'text-green-600' : 'text-red-500' }} shrink-0"
                                data-size="18"></i>
                            <span class="font-normal text-sm text-slate-700 leading-relaxed">
                                @if ($sfChangeable)
                                    Date changes
                                    allowed{{ $sfChangeFee ? ' with a fee of ' . $sfChangeCurr . ' ' . $sfChangeFee : '' }}
                                @else
                                    Date changes not allowed
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex justify-between items-center gap-4">
                <div class="flex flex-col grow">
                    <span class="font-semibold text-2xl text-blue-600">{{ $sfCurrency }}
                        {{ number_format((float) $sfPrice, 2) }}</span>
                    <span class="font-normal text-sm text-slate-500">
                        {{ $sfTripType === 'multi_city' ? 'Total price for complete multi-city journey' : 'Total price for all travelers' }}
                    </span>
                </div>
                <button wire:click="proceedToPassengers" class="btn btn-primary">Continue</button>
            </div>
        </x-frontend.modal>
    </div>
</div>
