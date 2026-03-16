<div>
    <div id="Loader" class="container_loader _newlognsecv2">
        <span class="loader"></span>
        <div class="loadtxtfl">
            Please Wait, We are searching for the flights on this route
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
                <!-- Mobile Filter Toggle Button -->
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
                            <h4 class="font-semibold text-lg sm:text-xl leading-7 sm:leading-8 text-slate-950">25
                                hotels found
                            </h4>
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
                        <div id="results-wrapper"
                            class="grid gap-1 sm:gap-4 lg:gap-6 transition-all duration-300 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Hotel Card 1 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-1.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="Budget Inn Antalya">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">
                                                        Budget Inn Antalya
                                                    </h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">Antalya, Turkey</span>
                                                    </div>
                                                </div>
                                                <div class="tag bg-green-600 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">5.0</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="wifi"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-wifi" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 18l.01 0"></path>
                                                            <path d="M9.172 15.172a4 4 0 0 1 5.656 0"></path>
                                                            <path d="M6.343 12.343a8 8 0 0 1 11.314 0"></path>
                                                            <path d="M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Free WiFi</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="glass-full"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-glass-full"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M8 21l8 0"></path>
                                                            <path d="M12 15l0 6"></path>
                                                            <path
                                                                d="M17 3l1 7c0 3.012 -2.686 5 -6 5s-6 -1.988 -6 -5l1 -7h10z">
                                                            </path>
                                                            <path d="M6 10a5 5 0 0 1 6 0a5 5 0 0 0 6 0"></path>
                                                        </svg>


                                                    </i>
                                                    <span>Bar</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€95</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Card 2 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-2.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="City Center Hotel">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">City
                                                        Center Hotel
                                                    </h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">Antalya, Turkey</span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="tag bg-orange-400 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">3.0</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="tools-kitchen-2"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-tools-kitchen-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Restaurant</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="glass-full"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-glass-full"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M8 21l8 0"></path>
                                                            <path d="M12 15l0 6"></path>
                                                            <path
                                                                d="M17 3l1 7c0 3.012 -2.686 5 -6 5s-6 -1.988 -6 -5l1 -7h10z">
                                                            </path>
                                                            <path d="M6 10a5 5 0 0 1 6 0a5 5 0 0 0 6 0"></path>
                                                        </svg>


                                                    </i>
                                                    <span>Bar</span>
                                                </div>
                                                <div class="tag tag-green">
                                                    <i data-tabler="check" data-size="14"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-check" width="14"
                                                            height="14" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M5 12l5 5l10 -10"></path>
                                                        </svg>


                                                    </i>
                                                    <span>Free cancellation</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€120</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Card 3 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-3.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="Budget Inn Antalya">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">
                                                        Budget Inn Antalya
                                                    </h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">Antalya, Turkey</span>
                                                    </div>
                                                </div>
                                                <div class="tag bg-red-500 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">1.0</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="wifi"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-wifi" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 18l.01 0"></path>
                                                            <path d="M9.172 15.172a4 4 0 0 1 5.656 0"></path>
                                                            <path d="M6.343 12.343a8 8 0 0 1 11.314 0"></path>
                                                            <path d="M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Free WiFi</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="tools-kitchen-2"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-tools-kitchen-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Restaurant</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€85</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Card 4 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-4.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="Grand Lara Resort">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">
                                                        Grand Lara Resort
                                                    </h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">Lara Coast, Turkey</span>
                                                    </div>
                                                </div>
                                                <div class="tag bg-green-600 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">4.8</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="pool"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-pool" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M2 20a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1">
                                                            </path>
                                                            <path
                                                                d="M2 16a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1">
                                                            </path>
                                                            <path d="M15 12v-7.5a1.5 1.5 0 0 1 3 0"></path>
                                                            <path d="M9 12v-7.5a1.5 1.5 0 0 0 -3 0"></path>
                                                            <path d="M15 5l-6 0"></path>
                                                            <path d="M9 10l6 0"></path>
                                                        </svg>


                                                    </i>
                                                    <span>Pool</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="tools-kitchen-2"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-tools-kitchen-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Breakfast</span>
                                                </div>
                                                <div class="tag tag-orange">
                                                    <i data-tabler="flame" data-size="14"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-flame" width="14"
                                                            height="14" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M12 12c2 -2.96 0 -7 -1 -8c0 3.038 -1.773 4.741 -3 6c-1.226 1.26 -2 3.24 -2 5a6 6 0 1 0 12 0c0 -1.532 -1.056 -3.94 -2 -5c-1.786 3 -2.791 3 -4 2z">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Member Deal</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€240</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Card 5 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-3.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="Antalya Suite Hotel">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">
                                                        Antalya Suite Hotel
                                                    </h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">City Center, Antalya</span>
                                                    </div>
                                                </div>
                                                <div class="tag bg-green-600 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">4.2</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="wifi"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-wifi" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 18l.01 0"></path>
                                                            <path d="M9.172 15.172a4 4 0 0 1 5.656 0"></path>
                                                            <path d="M6.343 12.343a8 8 0 0 1 11.314 0"></path>
                                                            <path d="M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Free WiFi</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="car"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-car" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                                            <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                                            <path
                                                                d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Parking</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€165</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Card 6 -->
                            <div
                                class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
                                <div class="card-inner flex flex-col h-full">
                                    <div
                                        class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
                                        <img src="images/hotel-1.jpg"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            alt="Blue Wave Resort">
                                    </div>
                                    <div
                                        class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="font-semibold text-lg text-slate-950 leading-tight">Blue
                                                        Wave Resort</h3>
                                                    <div class="flex items-center gap-1 text-slate-500">
                                                        <i data-tabler="map-pin" data-size="16"><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-map-pin"
                                                                width="16" height="16" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                </path>
                                                            </svg>


                                                        </i>
                                                        <span class="font-normal text-sm">Konyaalti, Turkey</span>
                                                    </div>
                                                </div>
                                                <div class="tag bg-green-600 text-white px-2 py-1 rounded-lg shrink-0">
                                                    <i data-tabler="star-filled" data-size="12"><svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-star-filled"
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                                                                stroke-width="0" fill="currentColor"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-semibold text-sm">4.5</span>
                                                </div>
                                            </div>

                                            <!-- Amenities Tags -->
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                <div class="tag tag-gray">
                                                    <i data-tabler="beach"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-beach" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M17.553 16.75a7.5 7.5 0 0 0 -10.606 0"></path>
                                                            <path
                                                                d="M18 3.804a6 6 0 0 0 -8.196 2.196l10.392 6a6 6 0 0 0 -2.196 -8.196z">
                                                            </path>
                                                            <path
                                                                d="M16.732 10c1.658 -2.87 2.225 -5.644 1.268 -6.196c-.957 -.552 -3.075 1.326 -4.732 4.196">
                                                            </path>
                                                            <path d="M15 9l-3 5.196"></path>
                                                            <path
                                                                d="M3 19.25a2.4 2.4 0 0 1 1 -.25a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 1 .25">
                                                            </path>
                                                        </svg>


                                                    </i>
                                                    <span>Private Beach</span>
                                                </div>
                                                <div class="tag tag-gray">
                                                    <i data-tabler="pool"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-pool" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M2 20a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1">
                                                            </path>
                                                            <path
                                                                d="M2 16a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1">
                                                            </path>
                                                            <path d="M15 12v-7.5a1.5 1.5 0 0 1 3 0"></path>
                                                            <path d="M9 12v-7.5a1.5 1.5 0 0 0 -3 0"></path>
                                                            <path d="M15 5l-6 0"></path>
                                                            <path d="M9 10l6 0"></path>
                                                        </svg>


                                                    </i>
                                                    <span>Pool</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">€195</span>
                                                    <span class="font-normal text-xs text-slate-500">/night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary btn-sm px-4">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="mt-8 flex flex-col md:flex-row flex-col-reverse justify-between items-center gap-6 self-stretch">
                            <span class="font-normal text-sm text-slate-600 order-2 md:order-1">100 results
                                displayed</span>
                            <div
                                class="flex items-center gap-2 md:gap-2.5 order-1 md:order-2 flex-wrap justify-center">
                                <!-- Back Button -->
                                <button
                                    class="flex items-center gap-1.5 transition-all text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-tabler="chevron-left" data-size="16"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-chevron-left" width="16"
                                            height="16" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M15 6l-6 6l6 6"></path>
                                        </svg>


                                    </i>
                                    <span class="font-normal text-sm">Back</span>
                                </button>

                                <!-- Page Numbers -->
                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-white bg-[#f3b515] p-2 rounded-lg transition-transform hover:scale-105">1</button>
                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white p-2 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">2</button>
                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white p-2 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">3</button>

                                <span
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-400">...</span>

                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white p-2 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">10</button>
                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white p-2 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">11</button>
                                <button
                                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white p-2 rounded-lg border border-slate-100 hover:bg-slate-50 transition-all lg:flex hidden">12</button>

                                <!-- Next Button -->
                                <button
                                    class="flex items-center gap-1.5 transition-all text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 hover:bg-slate-50">
                                    <span class="font-normal text-sm">Next</span>
                                    <i data-tabler="chevron-right" data-size="16"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-chevron-right" width="16"
                                            height="16" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 6l6 6l-6 6"></path>
                                        </svg>


                                    </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </main>
</div>
