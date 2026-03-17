<div>
    <div id="Loader" class="container_loader _newlognsecv2">
        <span class="loader"></span>
        <div class="loadtxtfl">
            Please Wait, We are searching for the Hotels on this City
        </div>
    </div>
    <main class="bg-slate-50">
        <section class="search-panel-inner py-5 bg-gradient-to-b from-[#075fc6] to-[#0d529b]">
            <div class="container">
                <div class="search-tab-content">
                    <x-frontend.hotel-search-tabs :hidden="false"/>
                </div>
            </div>
        </section>
        <div class="listing-area py-10 lg:py-16">
            <div class="container">
                <div class="md:hidden mb-6">
                    <button id="open-filter" class="w-full btn btn-primary flex items-center justify-center gap-2">
                        <i data-tabler="adjustments-horizontal" data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-adjustments-horizontal" width="18"
                                height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M4 6l8 0"></path>
                                <path d="M16 6l4 0"></path>
                                <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M4 12l2 0"></path>
                                <path d="M10 12l10 0"></path>
                                <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M4 18l11 0"></path>
                                <path d="M19 18l1 0"></path>
                            </svg>


                        </i>
                        Filter &amp; Sort
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
                                    <i data-tabler="adjustments-horizontal" data-size="20" class="text-slate-950"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-adjustments-horizontal" width="20"
                                            height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round"
                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M4 6l8 0"></path>
                                            <path d="M16 6l4 0"></path>
                                            <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M4 12l2 0"></path>
                                            <path d="M10 12l10 0"></path>
                                            <path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M4 18l11 0"></path>
                                            <path d="M19 18l1 0"></path>
                                        </svg>


                                    </i>
                                    <h3 class="font-semibold text-lg text-slate-950">Filters</h3>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button
                                        class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors uppercase">Clear
                                        All</button>
                                    <!-- Mobile Close Button -->
                                    <button id="close-filter" class="md:hidden text-slate-400 hover:text-slate-950">
                                        <i data-tabler="x" data-size="22"><svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-x" width="22" height="22"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>


                                        </i>
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
                                            <i data-tabler="chevron-down" data-size="16" class="text-slate-400"><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-chevron-down" width="16"
                                                    height="16" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M6 9l6 6l6 -6"></path>
                                                </svg>


                                            </i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Max Price -->
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="form-label">Max Price</span>
                                        <span class="text-sm font-bold text-primary-950">€0 - €500</span>
                                    </div>
                                    <div class="px-1">
                                        <input type="range" min="0" max="1000" value="500"
                                            class="range range-xs range-primary">
                                    </div>
                                </div>

                                <!-- Star Rating -->
                                <div class="space-y-4">
                                    <h4 class="form-label">Star Rating</h4>
                                    <div class="flex flex-col gap-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" checked="" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">1
                                                Star</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">2
                                                Star</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">3
                                                Star</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">4
                                                Star</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">5
                                                Star</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Amenities -->
                                <div class="space-y-4">
                                    <h4 class="form-label">Amenities</h4>
                                    <div class="flex flex-col gap-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Pool</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Spa</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Restaurant</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Free
                                                WiFi</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Beach
                                                Access</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Gym</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Bar</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Board Basis -->
                                <div class="space-y-4">
                                    <h4 class="form-label">Board Basis</h4>
                                    <div class="flex flex-col gap-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">All
                                                Inclusive</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Breakfast
                                                Included</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Room
                                                Only</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="checkbox shrink-0">
                                            <span
                                                class="text-sm font-medium text-slate-700 group-hover:text-slate-950 transition-colors">Half
                                                Board</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Options -->
                                <div class="pt-2 md:mt-6 mb-6">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" class="checkbox shrink-0">
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
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                            <h4 class="font-semibold text-lg sm:text-xl leading-7 sm:leading-8 text-slate-950">{{ $total }} Hotels Found</h4>
                            <div
                                class="flex items-center gap-2 bg-white p-1 rounded-xl border border-slate-100 shadow-sm w-fit">
                                <button id="list-view-btn" class="tabs">
                                    <i data-tabler="list" data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-list" width="18" height="18"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round"
                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 6l11 0"></path>
                                            <path d="M9 12l11 0"></path>
                                            <path d="M9 18l11 0"></path>
                                            <path d="M5 6l0 .01"></path>
                                            <path d="M5 12l0 .01"></path>
                                            <path d="M5 18l0 .01"></path>
                                        </svg>
                                    </i>
                                    <span>List</span>
                                </button>
                                <button id="grid-view-btn" class="tabs active">
                                    <i data-tabler="layout-grid" data-size="18"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-layout-grid" width="18"
                                            height="18" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                            </path>
                                            <path
                                                d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                            </path>
                                            <path
                                                d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                            </path>
                                            <path
                                                d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                            </path>
                                        </svg>
                                    </i>
                                    <span>Grid</span>
                                </button>
                            </div>
                        </div>

                        {{-- Cards grid --}}
                        <div id="results-wrapper" class="grid gap-1 sm:gap-4 lg:gap-6 transition-all duration-300 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3">
                            @forelse($hotels as $hotel)
                                <x-frontend.hotel-card :hotel="$hotel" />
                            @empty
                                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center text-slate-600">
                                    <img src="{{asset('assets/images/hotel-not-found.svg')}}" alt="icon" width="100"/>
                                    <h3 class="text-xl font-semibold mb-2 mt-3">Hotel Not Found</h3>
                                    <p class="text-sm text-slate-500">We couldn't find any hotels matching your search criteria.</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- Pagination --}}
                        {{-- <x-frontend.hotel-pagination
                            :current-page="$currentPage"
                            :total-pages="$totalPages"
                            :total="$total"
                            :per-page="$perPage"
                        /> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
