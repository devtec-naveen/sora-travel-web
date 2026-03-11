<div>
    {{-- <div id="Loader" class="container_loader _newlognsecv2">
        <span class="loader"></span>
        <div class="loadtxtfl">
        Please Wait, We are searching for the flights on this route
        </div>
    </div> --}}
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
                <!-- Mobile Filter Toggle Button -->
                <div class="md:hidden mb-6">
                    <button id="open-filter" class="w-full btn btn-primary flex items-center justify-center gap-2">
                        <i data-tabler="adjustments-horizontal" data-size="18"></i>
                        Filter & Sort
                    </button>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Filter Overlay Backdrop -->
                    <div id="filter-backdrop" class="fixed inset-0 bg-slate-900/40 z-[99] hidden md:hidden"></div>

                    <!-- Filters Sidebar / Mobile Modal -->
                    <div id="filter-sidebar"
                        class="rounded-none shadow-sm border border-slate-200 lg:rounded-lg fixed inset-y-0 left-0 z-[100] w-full h-screen translate-x-[-100%] transition-transform duration-300 md:relative md:translate-x-0 md:z-auto md:w-[317px] md:h-fit md:block bg-white overflow-hidden">

                        <div class="flex flex-col h-full md:card md:p-5 md:block">
                            <!-- Header -->
                            <div
                                class="flex justify-between items-center p-5 border-b border-slate-100 md:p-0 md:border-none md:mb-6 shrink-0">
                                <div class="flex items-center gap-2">
                                    <i data-tabler="adjustments-horizontal" data-size="20" class="text-slate-950"></i>
                                    <h3 class="font-semibold text-lg text-slate-950">Filters</h3>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button
                                        class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors uppercase">Clear
                                        All</button>
                                    <!-- Mobile Close Button -->
                                    <button id="close-filter" class="md:hidden text-slate-400 hover:text-slate-950">
                                        <i data-tabler="x" data-size="22"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 overflow-y-auto p-5 md:p-0 space-y-8 md:overflow-visible">
                                <!-- Sort By -->
                                <div class="form-control">
                                    <label class="form-label">Sort By</label>
                                    <div class="relative mt-1">
                                        <select class="form-input appearance-none pr-10">
                                            <option>Price (Low to High)</option>
                                            <option>Price (High to Low)</option>
                                            <option>Duration (Shortest)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                            <i data-tabler="chevron-down" data-size="16" class="text-slate-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Max Price -->
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="form-label">Max Price</span>
                                        <span class="text-sm font-bold text-primary-950">€500</span>
                                    </div>
                                    <div class="px-1">
                                        <input type="range" min="0" max="1000" value="500"
                                            class="range range-xs range-primary" />
                                    </div>
                                </div>

                                <!-- Stops -->
                                <div class="space-y-4">
                                    <h4 class="form-label">Stops</h4>
                                    <div class="flex flex-col gap-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" checked class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Non-stop</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">1
                                                stop</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">2+
                                                stops</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Airlines -->
                                <div class="space-y-4">
                                    <h4 class="form-label">Airlines</h4>
                                    <div class="flex flex-col gap-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Brussels
                                                Airlines</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Turkish
                                                Airlines</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0" />
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Lufthansa</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="pt-2 md:mt-6 mb-6">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" class="checkbox shrink-0" />
                                        <span
                                            class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Refundable
                                            only</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Footer (Fixed Bottom for Mobile) -->
                            <div class="p-5 border-t border-slate-100 md:hidden shrink-0">
                                <button id="apply-filter" class="w-full btn btn-primary">Apply Filters</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 space-y-5">
                        <div class="headings">
                            <h4 class="font-semibold text-lg sm:text-xl leading-7 sm:leading-8 text-slate-950">
                                {{ $total }} flights found
                            </h4>
                        </div>

                        <div class="space-y-3.5">
                            @foreach ($flights as $flight)
                                @php
                                    $itinerary = $flight['itineraries'][0];
                                    $segment = $itinerary['segments'][0];
                                    $price = $flight['price']['grandTotal'] ?? $flight['price']['total'];
                                    $airlineCode = $segment['carrierCode'];
                                    $flightNumber = $segment['number'];
                                    $departure = $segment['departure'];
                                    $arrival = $segment['arrival'];
                                    $duration = $itinerary['duration'];
                                    $aircraft = $segment['aircraft']['code'];
                                    $bags =
                                        $flight['travelerPricings'][0]['fareDetailsBySegment'][0][
                                            'includedCheckedBags'
                                        ] ?? [];
                                @endphp

                                <div class="card p-4 transition-all hover:shadow-md">
                                    <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                        <div class="flex-1 flex flex-col gap-3">
                                            <!-- Airline -->
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                    <img src="{{ asset('images/airline/' . $airlineCode . '.png') }}"
                                                        alt="{{ $airlineCode }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-semibold text-base text-slate-950">{{ $airlineCode }}</span>
                                                    <span
                                                        class="font-normal text-sm text-slate-500">{{ $flightNumber }}</span>
                                                </div>
                                            </div>

                                            <!-- Time & Route -->
                                            <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                <div class="flex flex-col items-start">
                                                    <span
                                                        class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                        {{ \Carbon\Carbon::parse($departure['at'])->format('h:i A') }}
                                                    </span>
                                                    <span
                                                        class="font-normal text-sm text-slate-500">{{ $departure['iataCode'] }}</span>
                                                </div>

                                                <div
                                                    class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                    <span
                                                        class="font-normal text-xs text-slate-500">{{ \Carbon\CarbonInterval::make($duration)->cascade()->forHumans() }}</span>
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
                                                    <span class="font-normal text-xs text-slate-500">
                                                        {{ $segment['numberOfStops'] }} stop(s)
                                                    </span>
                                                </div>

                                                <div class="flex flex-col items-end">
                                                    <span
                                                        class="font-semibold text-sm lg:text-xl leading-8 text-slate-950">
                                                        {{ \Carbon\Carbon::parse($arrival['at'])->format('h:i A') }}
                                                    </span>
                                                    <span
                                                        class="font-normal text-sm text-slate-500 text-right">{{ $arrival['iataCode'] }}</span>
                                                </div>
                                            </div>

                                            <!-- Amenities -->
                                            <div class="flex flex-wrap gap-2.5">
                                                @if (isset($bags['quantity']))
                                                    <div class="tag tag-gray">
                                                        <i data-tabler="briefcase"></i>
                                                        <span>{{ $bags['quantity'] }}kg</span>
                                                    </div>
                                                @endif
                                                <div class="tag tag-gray">
                                                    <span>Aircraft: {{ $aircraft }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Divider -->
                                        <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                        <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                        <!-- Price & Select -->
                                        <div
                                            class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between lg:min-w-[153px] gap-4">
                                            <div class="flex flex-col lg:items-end">
                                                <span class="font-normal text-sm text-slate-500">From</span>
                                                <span
                                                    class="font-semibold text-[24px] leading-[36px] text-blue-600">€{{ $price }}</span>
                                                <span class="font-normal text-sm text-slate-500">per person</span>
                                            </div>
                                            <button onclick="flight_details_modal.showModal()"
                                                class="btn btn-primary whitespace-nowrap btn-sm">Select Flight</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div
                            class="mt-8 flex flex-col md:flex-row flex-col-reverse justify-between items-center gap-6 self-stretch">
                            <span class="font-normal text-sm text-slate-600 order-2 md:order-1">
                                Showing {{ $flights->count() }} of {{ $total }} results
                            </span>
                            <div
                                class="flex items-center gap-2 md:gap-2.5 order-1 md:order-2 flex-wrap justify-center">
                                <button wire:click="$set('page', max($page-1,1))"
                                    class="px-3 py-2 border rounded disabled:opacity-50"
                                    @if ($page == 1) disabled @endif>Back</button>

                                @for ($i = 1; $i <= ceil($total / $limit); $i++)
                                    <button wire:click="$set('page', {{ $i }})"
                                        class="px-3 py-2 border rounded {{ $page == $i ? 'bg-yellow-500 text-white' : 'bg-white text-black' }}">
                                        {{ $i }}
                                    </button>
                                @endfor

                                <button wire:click="$set('page', min($page+1, ceil($total/$limit)))"
                                    class="px-3 py-2 border rounded"
                                    @if ($page >= ceil($total / $limit)) disabled @endif>Next</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
