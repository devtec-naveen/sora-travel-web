<x-frontend.main-layout>
    <main class="bg-slate-50 min-h-[800px]">

        <div class="py-6 lg:py-12">
            <div class="container">
                <div class="justify-start text-slate-950 text-2xl font-semibold leading-9 mb-6">My Bookings</div>

                <!-- name of each tab group should be unique -->
                <div class="tabs tabs-lift p-0 bg-transparent justify-start">
                    <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                        <input type="radio" name="my_tabs_4" checked="checked">
                        <i data-tabler="plane-inflight" class="size-5 md:size-7"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-plane-inflight" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round"
                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M15 11.085h5a2 2 0 1 1 0 4h-15l-3 -6h3l2 2h3l-2 -7h3l4 7z"></path>
                                <path d="M3 21h18"></path>
                            </svg>


                        </i>
                        Flights
                    </label>
                    <div class="tab-content mt-2">
                        <!-- Inner Tabs Navigation -->
                        <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-4 gap-x-2">
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="flight_status_tabs" checked="checked">
                                Upcoming
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1234</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 1:30 PM</span>
                                                        <span class="font-normal text-sm text-slate-500">Brussels
                                                            (BRU)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">5h 15m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">1 stop</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">Feb
                                                            13, 2026 2:30 PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Antalya
                                                            (AYT)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1234</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 1:30 PM</span>
                                                        <span class="font-normal text-sm text-slate-500">Brussels
                                                            (BRU)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">5h 15m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">1 stop</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">Feb
                                                            13, 2026 2:30 PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Antalya
                                                            (AYT)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1234</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 1:30 PM</span>
                                                        <span class="font-normal text-sm text-slate-500">Brussels
                                                            (BRU)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">5h 15m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">1 stop</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">Feb
                                                            13, 2026 2:30 PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Antalya
                                                            (AYT)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1234</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 1:30 PM</span>
                                                        <span class="font-normal text-sm text-slate-500">Brussels
                                                            (BRU)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">5h 15m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">1 stop</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">Feb
                                                            13, 2026 2:30 PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Antalya
                                                            (AYT)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="flight_status_tabs">
                                Completed
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465347</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1235</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 10:00 AM</span>
                                                        <span class="font-normal text-sm text-slate-500">Paris
                                                            (CDG)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">3h 30m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">Direct</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 1:30 PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">London
                                                            (LHR)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-green-600">€220</span>
                                                    <span class="font-normal text-xs text-slate-500">Completed</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465348</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1236</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 8:15 AM</span>
                                                        <span class="font-normal text-sm text-slate-500">Berlin
                                                            (BER)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">4h 45m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">1 stop</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">1:00
                                                            PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Rome
                                                            (FCO)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-green-600">€195</span>
                                                    <span class="font-normal text-xs text-slate-500">Completed</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="flight_status_tabs">
                                Cancelled
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465349</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1237</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 3:45 PM</span>
                                                        <span class="font-normal text-sm text-slate-500">Amsterdam
                                                            (AMS)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">6h 20m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">2 stops</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">10:05
                                                            PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Barcelona
                                                            (BCN)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-red-600">€280</span>
                                                    <span class="font-normal text-xs text-red-600">Cancelled</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Flight Result Card -->
                                    <div class="card p-4 transition-all hover:shadow-md opacity-75">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Flight Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465350</div>
                                                <!-- Airline Info -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100">
                                                        <img src="images/air-india.png" alt="Air India"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-base text-slate-950">Air
                                                            India</span>
                                                        <span class="font-normal text-sm text-slate-500">LH1238</span>
                                                    </div>
                                                </div>

                                                <!-- Time & Route -->
                                                <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">
                                                    <!-- Departure -->
                                                    <div class="flex flex-col items-start">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950">Feb
                                                            13, 2026 11:20 AM</span>
                                                        <span class="font-normal text-sm text-slate-500">Vienna
                                                            (VIE)</span>
                                                    </div>

                                                    <!-- Duration & Path -->
                                                    <div
                                                        class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                        <span class="font-normal text-xs text-slate-500">2h 55m</span>
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
                                                                    data-size="18"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-plane"
                                                                        width="18" height="18"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M16 10h4a2 2 0 0 1 0 4h-4l-4 7h-3l2 -7h-4l-2 2h-3l2 -4l-2 -4h3l2 2h4l-2 -7h3z">
                                                                        </path>
                                                                    </svg>


                                                                </i>
                                                            </div>
                                                        </div>
                                                        <span class="font-normal text-xs text-slate-500">Direct</span>
                                                    </div>

                                                    <!-- Arrival -->
                                                    <div class="flex flex-col items-end">
                                                        <span
                                                            class="font-semibold text-sm lg:text-xl text-slate-950 text-end">2:15
                                                            PM</span>
                                                        <span
                                                            class="font-normal text-sm text-slate-500 text-right">Prague
                                                            (PRG)</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between  gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-red-600">€150</span>
                                                    <span class="font-normal text-xs text-red-600">Cancelled</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="relative ml-auto md:w-auto w-full">
                                <select class="form-input appearance-none pr-10">
                                    <option>Date Range</option>
                                    <option>Ms</option>
                                    <option>Mrs</option>
                                </select>
                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <i data-tabler="chevron-down" data-size="16"><svg
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
                    </div>

                    <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                        <input type="radio" name="my_tabs_4">
                        <i data-tabler="building" class="size-5 md:size-7"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-building" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round"
                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 21l18 0"></path>
                                <path d="M9 8l1 0"></path>
                                <path d="M9 12l1 0"></path>
                                <path d="M9 16l1 0"></path>
                                <path d="M14 8l1 0"></path>
                                <path d="M14 12l1 0"></path>
                                <path d="M14 16l1 0"></path>
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"></path>
                            </svg>


                        </i>
                        Hotels
                    </label>
                    <div class="tab-content mt-2">
                        <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-4 gap-x-2">
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="hotel_status_tabs" checked="checked">
                                Upcoming
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-1.jpg" alt="Budget Inn Antalya"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Budget Inn
                                                        Antalya</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-1.jpg" alt="Budget Inn Antalya"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Budget Inn
                                                        Antalya</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-1.jpg" alt="Budget Inn Antalya"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Budget Inn
                                                        Antalya</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-1.jpg" alt="Budget Inn Antalya"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Budget Inn
                                                        Antalya</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="hotel_status_tabs">
                                Completed
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-2.jpg" alt="City Center Hotel"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465347
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">City Center
                                                        Hotel</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Sun, Feb 15,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-green-600">€220</span>
                                                    <span class="font-normal text-xs text-slate-500">Completed</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="hotel_status_tabs">
                                Cancelled
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Hotel Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Hotel Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden">
                                                <img src="images/hotel-3.jpg" alt="Antalya Suite Hotel"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Hotel Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465348
                                                </div>

                                                <!-- Hotel Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Antalya Suite
                                                        Hotel</span>
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

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Sat, Feb 14,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-red-600">€195</span>
                                                    <span class="font-normal text-xs text-red-600">Cancelled</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative ml-auto md:w-auto w-full">
                                <select class="form-input appearance-none pr-10">
                                    <option>Date Range</option>
                                    <option>Last 7 days</option>
                                    <option>Last 30 days</option>
                                    <option>Last 3 months</option>
                                </select>
                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <i data-tabler="chevron-down" data-size="16"><svg
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
                    </div>

                    <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                        <input type="radio" name="my_tabs_4">
                        <i data-tabler="car" class="size-5 md:size-7"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-car" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round"
                                style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                            </svg>


                        </i>
                        Car
                        Rental
                    </label>
                    <div class="tab-content mt-2">
                        <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-4 gap-x-2">
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="car_status_tabs" checked="checked">
                                Upcoming
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Toyota Fortuner"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Toyota
                                                        Fortuner</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Toyota Fortuner"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Toyota
                                                        Fortuner</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Toyota Fortuner"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Toyota
                                                        Fortuner</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Toyota Fortuner"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465346
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Toyota
                                                        Fortuner</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Mon, Feb 16,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-blue-600">€175</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-red whitespace-nowrap btn-sm">
                                                        Cancel
                                                    </button>
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="car_status_tabs">
                                Completed
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Toyota Camry"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465347
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Toyota
                                                        Camry</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Sun, Feb 15,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-green-600">€220</span>
                                                    <span class="font-normal text-xs text-slate-500">Completed</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="car_status_tabs">
                                Cancelled
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    <!-- Car Rental Booking Card -->
                                    <div class="card p-4 transition-all hover:shadow-md">
                                        <div class="flex flex-col lg:flex-row gap-3 md:gap-6">
                                            <!-- Left Section: Car Image -->
                                            <div
                                                class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                <img src="images/toyota-camry.png" alt="Honda Civic"
                                                    class="w-full h-full object-cover">
                                            </div>

                                            <!-- Middle Section: Car Details -->
                                            <div class="flex-1 flex flex-col gap-3">
                                                <div
                                                    class="self-stretch justify-start text-slate-500 text-base font-normal font-['Inter'] leading-6">
                                                    #23523465348
                                                </div>

                                                <!-- Car Name -->
                                                <div class="flex flex-col gap-1">
                                                    <span class="font-semibold text-lg text-slate-950">Honda
                                                        Civic</span>
                                                    <span class="text-sm text-slate-500">Provided by <span
                                                            class="font-semibold text-slate-700">Elite
                                                            Rentals</span></span>
                                                </div>

                                                <!-- Date -->
                                                <div class="flex items-center gap-2">
                                                    <i data-tabler="calendar" class="text-slate-400"
                                                        data-size="18"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-calendar"
                                                            width="18" height="18" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor"
                                                            fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            style="display: inline-block; vertical-align: middle; stroke: currentcolor;">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                            </path>
                                                            <path d="M16 3v4"></path>
                                                            <path d="M8 3v4"></path>
                                                            <path d="M4 11h16"></path>
                                                            <path d="M11 15h1"></path>
                                                            <path d="M12 15v3"></path>
                                                        </svg>


                                                    </i>
                                                    <span class="font-normal text-sm text-slate-700">Sat, Feb 14,
                                                        2026</span>
                                                </div>
                                            </div>

                                            <!-- Divider -->
                                            <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                            <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                            <!-- Right Section: Pricing & Action -->
                                            <div
                                                class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                <div class="flex flex-col lg:items-end">
                                                    <span
                                                        class="font-semibold text-[24px] leading-[36px] text-red-600">€195</span>
                                                    <span class="font-normal text-xs text-red-600">Cancelled</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="btn btn-primary whitespace-nowrap btn-sm">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-auto md:w-auto w-full flex items-center gap-2">
                                <div class="relative flex-1 md:flex-none">
                                    <select class="form-input appearance-none pr-10">
                                        <option>Date Range</option>
                                        <option>Last 7 days</option>
                                        <option>Last 30 days</option>
                                        <option>Last 3 months</option>
                                    </select>
                                    <div
                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i data-tabler="chevron-down" data-size="16"><svg
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
                                <div class="relative flex-1 md:flex-none">
                                    <select class="form-input appearance-none pr-10">
                                        <option>Date Range</option>
                                        <option>Last 7 days</option>
                                        <option>Last 30 days</option>
                                        <option>Last 3 months</option>
                                    </select>
                                    <div
                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i data-tabler="chevron-down" data-size="16"><svg
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-frontend.main-layout>
