<div
    class="flex flex-col justify-center gap-4 self-stretch bg-white p-2 md:p-4 rounded-xl shadow-sm border border-slate-100">
    <div class="flex items-center gap-2">
        <button type="button" class="trip-tab tabs active" data-trip="oneway">One-way</button>
        <button type="button" class="trip-tab tabs" data-trip="roundtrip">Round trip</button>
        <button type="button" class="trip-tab tabs" data-trip="multicity">Multi city</button>
    </div>
    <div class="subTabs-content">
        <input type="hidden" name="trip_type" id="trip_type" value="oneway">
        {{-- ====================================== One Way Trip ======================================= --}}
        <div class="">
            <form method="get" action="{{ route('front.flightSearch') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 w-full" id="flightOneWay">
                    <x-frontend.autocomplete
                        label="Leaving from"
                        name="origin"
                        value="{{ request('origin', 'JAI') }}"
                        display="{{ request('origin') ? request('origin').' – '.request('origin_city') : 'JAI – Jaipur' }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="takeoff.svg"
                        cityInputName="origin_city"
                        cityValue="{{ request('origin_city', 'jaipur') }}"
                    />

                    <x-frontend.autocomplete
                        label="Going to"
                        name="destination"
                        value="{{ request('destination', 'BLR') }}"
                        display="{{ request('destination') ? request('destination').' – '.request('departure_city') : 'BLR – Bangalore' }}"
                        placeholder="Search airport or city…"
                        type="airport"
                        icon="dropoff.svg"
                        cityInputName="departure_city"
                        cityValue="{{ request('departure_city', 'bangalore') }}"
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
        <div class="hidden">
            <form method="get" action="{{ route('front.flightSearch') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 w-full" id="flightRoundTrip">
                    <x-frontend.autocomplete label="Leaving from" name="from_destination" value="JAI"
                        display="JAI – Jaipur" placeholder="Search airport or city…" type="airport"
                        icon="takeoff.svg" />

                    <x-frontend.autocomplete label="Leaving from" name="to_destination" value="BLR"
                        display="BLR – Bangalore" placeholder="Search airport or city…" type="airport"
                        icon="dropoff.svg" />

                    <x-frontend.date-picker id="round_fl_dep" name="departure_date" label="Departure Date"
                        placeholder="Select date" />

                    <x-frontend.date-picker id="round_fl_return" name="return_date" label="Return Date"
                        placeholder="Select date" data-default-today />

                    <x-frontend.travelers id="FlightRoundtrip" />

                    <button type="submit" class="btn btn-primary h-full md:col-span-2 lg:col-span-1">
                        <i data-tabler="search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        {{-- ====================================== Muti Trip ======================================= --}}
        <div class="hidden">
            <form method="get" action="{{ route('front.flightSearch') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 w-full" id="flightMultitrip">
                    <x-frontend.autocomplete label="Leaving from" name="from_destination" value="JAI"
                        display="JAI – Jaipur" placeholder="Search airport or city…" type="airport"
                        icon="takeoff.svg" />

                    <x-frontend.autocomplete label="Leaving from" name="to_destination" value="BLR"
                        display="BLR – Bangalore" placeholder="Search airport or city…" type="airport"
                        icon="dropoff.svg" />

                    <x-frontend.date-picker id="mul_fl_dep" name="departure_date" label="Departure Date"
                        placeholder="Select date" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 w-full mt-2" id="flightMultitrip">
                    <x-frontend.autocomplete label="Leaving from" name="from_destination" value="JAI"
                        display="JAI – Jaipur" placeholder="Search airport or city…" type="airport"
                        icon="takeoff.svg" />

                    <x-frontend.autocomplete label="Leaving from" name="to_destination" value="BLR"
                        display="BLR – Bangalore" placeholder="Search airport or city…" type="airport"
                        icon="dropoff.svg" />

                    <x-frontend.date-picker id="mul_fl_dep" name="departure_date" label="Departure Date"
                        placeholder="Select date" />

                    <x-frontend.travelers id="FlightMultrip" />

                    <button type="submit" class="btn btn-primary h-full md:col-span-2 lg:col-span-1">
                        <i data-tabler="search"></i> Search
                    </button>
                    <button type="submit" class="btn btn-secondary border-none h-full md:col-span-2 lg:col-span-1">
                        <i data-tabler="plus"></i> Add City
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>