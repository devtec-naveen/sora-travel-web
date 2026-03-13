<div
    class="flex flex-col justify-center gap-4 self-stretch bg-white p-2 md:p-4 rounded-xl shadow-sm border border-slate-100">
    <div class="flex items-center gap-2">
        <button type="button" class="trip-tab tabs {{ request('trip_type','oneway') == 'oneway' ? 'active' : '' }}" data-trip="oneway">One way</button>
        <button type="button" class="trip-tab tabs {{ request('trip_type') == 'roundtrip' ? 'active' : '' }}" data-trip="roundtrip">Round trip</button>
        <button type="button" class="trip-tab tabs {{ request('trip_type') == 'multicity' ? 'active' : '' }}" data-trip="multicity">Multi city</button>
    </div>
    <div class="subTabs-content">
        {{-- ====================================== One Way Trip ======================================= --}}
        <div class="{{ request('trip_type','oneway') == 'oneway' ? '' : 'hidden' }}">
            <form method="get" action="{{ route('front.flightSearch') }}" id="flightOneWayForm">
               <input type="hidden" name="trip_type" id="trip_type" value="oneway">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 w-full" id="flightOneWay">
                    <x-frontend.autocomplete
                        label="Leaving from"
                        name="origin"
                        value="{{ request('origin.0','JAI') }}"
                        display="{{ request('origin.0','JAI') }} – {{ request('origin_city.0','Jaipur') }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="takeoff.svg"
                        cityInputName="origin_city"
                        cityValue="{{ request('origin_city.0','jaipur') }}"
                    />

                    <x-frontend.autocomplete
                        label="Going to"
                        name="destination"
                        value="{{ request('destination.0','BLR') }}"
                        display="{{ request('destination.0','BLR') }} – {{ request('departure_city.0','Bangalore') }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="dropoff.svg"
                        cityInputName="departure_city"
                        cityValue="{{ request('departure_city.0','bangalore') }}"
                    />

                    <x-frontend.date-picker 
                        id="fl_dep"
                        name="departureDate"
                        label="Departure Date"
                        placeholder="Select date"
                        value="{{ request('departureDate', '') }}"
                    />

                    <x-frontend.travelers id="FlightOneway" />

                    <button type="submit" class="btn btn-primary h-full md:col-span-2 lg:col-span-1">
                        <i data-tabler="search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        {{-- ====================================== Round Trip ======================================= --}}
        <div class="{{ request('trip_type') == 'roundtrip' ? 'block' : 'hidden' }}">
            <form method="get" action="{{ route('front.flightSearch') }}">
                <input type="hidden" name="trip_type" id="trip_type" value="roundtrip">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 w-full" id="flightRoundTrip">
                    <x-frontend.autocomplete
                        label="Leaving from"
                        name="origin"
                        value="{{ is_array(request('origin')) ? request('origin')[0] : request('origin', 'JAI') }}"
                        display="{{ request('origin.0') ? request('origin.0').' – '.request('origin_city.0') : 'JAI – Jaipur' }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="takeoff.svg"
                        cityInputName="origin_city"
                        cityValue="{{ request('origin_city.0', 'jaipur') }}"
                    />

                    <x-frontend.autocomplete
                        label="Going to"
                        name="destination"
                        value="{{ is_array(request('destination')) ? request('destination')[0] : request('destination', 'BLR') }}"
                        display="{{ request('destination.0') ? request('destination.0').' – '.request('departure_city.0') : 'BLR – Bangalore' }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="dropoff.svg"
                        cityInputName="departure_city"
                        cityValue="{{ request('departure_city.0', 'bangalore') }}"
                    />

                    <x-frontend.date-picker 
                        id="round_fl_dep"
                        name="departureDate"
                        label="Departure Date"
                        placeholder="Select date"
                        value="{{ request('departureDate', '') }}"
                    />

                    <x-frontend.date-picker 
                        id="round_fl_return"
                        name="returnDate"
                        label="Return Date"
                        placeholder="Select date"
                        value="{{ request('returnDate', '') }}"
                    />

                    <x-frontend.travelers id="FlightRoundtrip" />

                    <button type="submit" class="btn btn-primary h-full md:col-span-2 lg:col-span-1">
                        <i data-tabler="search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        {{-- ====================================== Muti Trip ======================================= --}}
        <div class="{{ request('trip_type') == 'multicity' ? 'block' : 'hidden' }}">
            <form method="get" action="{{ route('front.flightSearch') }}" id="multicity-form">
                <input type="hidden" name="trip_type" id="trip_type" value="multicity">
                <div class="rounded-xl border border-base-200 bg-base-50 p-3 md:p-0 md:border-0 md:bg-transparent md:rounded-none">
                    <div class="flex items-center gap-2 mb-3 md:hidden">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">
                            <i data-tabler="plane-departure" class="w-3 h-3"></i> Flight 1
                        </span>
                        <span class="text-xs text-base-content/40 font-medium">Base Flight</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 w-full">
                        <x-frontend.autocomplete
                            label="Leaving from"
                            name="origin[]"
                            value="{{ request('origin.0', 'JAI') }}"
                            display="{{ request('origin.0') ? request('origin.0').' – '.request('origin_city.0') : 'JAI – Jaipur' }}"
                            placeholder="Search airport or city…"
                            type="airport"
                            icon="takeoff.svg"
                            cityInputName="origin_city[]"
                            cityValue="{{ request('origin_city.0', 'jaipur') }}"
                        />

                        <x-frontend.autocomplete
                            label="Going to"
                            name="destination[]"
                            value="{{ request('destination.0', 'BLR') }}"
                            display="{{ request('destination.0') ? request('destination.0').' – '.request('departure_city.0') : 'BLR – Bangalore' }}"
                            placeholder="Search airport or city…"
                            type="airport"
                            icon="dropoff.svg"
                            cityInputName="departure_city[]"
                            cityValue="{{ request('departure_city.0', 'bangalore') }}"
                        />

                        <x-frontend.date-picker
                            id="mul_fl_dep_0"
                            name="departure_date[]"
                            label="Departure Date"
                            placeholder="Select date"
                        />
                        <x-frontend.travelers id="FlightMultrip_0" />
                    </div>
                </div>        
                {{-- ═══ ADDON ROWS ═══ --}}
                <div id="addon-rows-container" class="flex flex-col gap-3 mt-3">        
                    <div class="addon-city-row" data-row="1">
                        <div class="flex items-center gap-2 mb-3 md:hidden addon-mobile-badge">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-secondary/10 text-secondary addon-flight-label">
                                <i data-tabler="plane" class="w-3 h-3"></i> Flight 2
                            </span>
                        </div>
                        <div class="rounded-xl border border-base-200 bg-base-50 p-3 md:p-0 md:border-0 md:bg-transparent md:rounded-none">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 w-full items-end">
                                <x-frontend.autocomplete
                                    label="Leaving from"
                                    name="origin[]"
                                    value="{{ request('origin.0', 'JAI') }}"
                                    display="{{ request('origin.0') ? request('origin.0').' – '.request('origin_city.0') : 'BLR – Bangalore' }}"
                                    placeholder="Search airport or city…"
                                    type="airport"
                                    icon="takeoff.svg"
                                    cityInputName="origin_city[]"
                                    cityValue="{{ request('origin_city.0', 'jaipur') }}"
                                />

                                <x-frontend.autocomplete
                                    label="Going to"
                                    name="destination[]"
                                    value="{{ request('destination.0', 'BLR') }}"
                                    display="{{ request('destination.0') ? request('destination.0').' – '.request('departure_city.0') : 'DEL - Delhi' }}"
                                    placeholder="Search airport or city…"
                                    type="airport"
                                    icon="dropoff.svg"
                                    cityInputName="departure_city[]"
                                    cityValue="{{ request('departure_city.0', 'bangalore') }}"
                                />
                                <x-frontend.date-picker
                                    id="mul_fl_dep_1"
                                    name="departure_date[]"
                                    label="Departure Date"
                                    placeholder="Select date"
                                />
                                <div class="addon-action-col flex items-end gap-2 lg:col-span-1"></div>
                            </div>
                        </div>
                    </div>        
                </div>        
            </form>
        </div> 
    </div>
</div>