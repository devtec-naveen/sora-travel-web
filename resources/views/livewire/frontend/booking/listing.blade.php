<div wire:init="loadData">
    <x-loader 
        message="Please Wait..."
        targets="loadData,setType,setStatus,dateRange"
     />
    <div class="py-6 lg:py-12">
        <div class="container">
            <div class="justify-start text-slate-950 text-2xl font-semibold leading-9 mb-6">My Bookings</div>
            <div class="tabs tabs-lift p-0 bg-transparent justify-start">

                {{-- ═══════════════════════════════════════════════════════
                     FLIGHTS TAB
                ══════════════════════════════════════════════════════════ --}}
                <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                    <input type="radio" name="my_tabs_booking" wire:click="setType('flight')"
                        @checked($activeType === 'flight')>
                    <i data-tabler="plane-inflight" class="size-5 md:size-7"></i>
                    Flights
                </label>

                <div class="tab-content mt-2">
                    <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-x-2">

                        @foreach (['upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $statusKey => $statusLabel)
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="flight_status_tabs"
                                    wire:click="setStatus('{{ $statusKey }}')"
                                    @checked($activeStatus === $statusKey && $activeType === 'flight')>
                                {{ $statusLabel }}
                            </label>
                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    @if ($activeType === 'flight')
                                        @forelse($parsedOrders as $item)
                                            @php
                                                $order = $item['order'];
                                                $flags = $item['flags'];
                                                $p = $item['parsed'];
                                                $priceColor = $flags['isCancelled']
                                                    ? 'text-red-600'
                                                    : ($flags['isCompleted']
                                                        ? 'text-green-600'
                                                        : 'text-blue-600');
                                            @endphp

                                            <div class="card p-4 transition-all hover:shadow-md {{ $flags['isCancelled'] ? 'opacity-75' : '' }}">
                                                <div class="flex flex-col lg:flex-row gap-3 md:gap-6">

                                                    {{-- Left: Flight Details --}}
                                                    <div class="flex-1 flex flex-col gap-3 min-w-0">

                                                        {{-- Order Number & Ref --}}
                                                        <div class="text-slate-500 text-base font-normal leading-6">
                                                            {{ $order->order_number }}
                                                            @if ($p['booking_reference'])
                                                                &nbsp;·&nbsp; Ref:
                                                                <strong>{{ $p['booking_reference'] }}</strong>
                                                            @endif
                                                        </div>

                                                        {{-- Airline --}}
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100 flex items-center justify-center p-1.5">
                                                                @if ($p['carrier']['logo_symbol_url'] ?? null)
                                                                    <img src="{{ $p['carrier']['logo_symbol_url'] }}"
                                                                        alt="{{ $p['carrier']['name'] ?? '' }}"
                                                                        class="w-full h-full object-contain">
                                                                @else
                                                                    <span class="text-xs font-bold text-slate-400">
                                                                        {{ $p['carrier']['iata_code'] ?? '?' }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="font-semibold text-base text-slate-950">
                                                                    {{ $p['carrier']['name'] ?? '—' }}
                                                                </span>
                                                                <span class="font-normal text-sm text-slate-500">
                                                                    {{ $p['flight_number'] }}
                                                                    @if ($p['aircraft'])
                                                                        &nbsp;·&nbsp; {{ $p['aircraft'] }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- Route --}}
                                                        <div class="flex flex-row items-center justify-between gap-6 sm:gap-4">

                                                            {{-- Departure --}}
                                                            <div class="flex flex-col items-start">
                                                                <span class="font-semibold text-sm lg:text-xl text-slate-950">
                                                                    {{ $p['dep_at']->format('d M, g:i A') }}
                                                                </span>
                                                                <span class="font-normal text-sm text-slate-500">
                                                                    {{ $p['origin']['city_name'] ?? '' }}
                                                                    ({{ $p['origin']['iata_code'] ?? '' }})
                                                                    @if ($p['origin_terminal'])
                                                                        · T{{ $p['origin_terminal'] }}
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            {{-- Duration --}}
                                                            <div class="flex-1 flex flex-col items-center gap-0.5 max-w-[200px] min-w-[100px]">
                                                                <span class="font-normal text-xs text-slate-500">
                                                                    {{ $p['duration'] }}
                                                                </span>
                                                                <div class="relative w-full flex items-center justify-center h-4">
                                                                    <div class="absolute w-full h-px bg-slate-200"></div>
                                                                    <div class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                                                    <div class="absolute right-0 w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                                                    <div class="relative z-10 bg-white px-2 leading-none">
                                                                        <i data-tabler="plane" class="text-slate-400" data-size="18"></i>
                                                                    </div>
                                                                </div>
                                                                <span class="font-normal text-xs text-slate-500">
                                                                    {{ $p['stop_label'] }}
                                                                </span>
                                                            </div>

                                                            {{-- Arrival --}}
                                                            <div class="flex flex-col items-end">
                                                                <span class="font-semibold text-sm lg:text-xl text-slate-950 text-end">
                                                                    {{ $p['arr_at']->format('d M, g:i A') }}
                                                                </span>
                                                                <span class="font-normal text-sm text-slate-500 text-right">
                                                                    {{ $p['destination']['city_name'] ?? '' }}
                                                                    ({{ $p['destination']['iata_code'] ?? '' }})
                                                                    @if ($p['dest_terminal'])
                                                                        · T{{ $p['dest_terminal'] }}
                                                                    @endif
                                                                </span>
                                                            </div>

                                                        </div>

                                                        {{-- Cabin & Baggage Badges --}}
                                                        <div class="flex flex-wrap gap-1.5">
                                                            @if ($p['cabin_class'])
                                                                <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                                                                    {{ $p['cabin_class'] }}
                                                                </span>
                                                            @endif
                                                            @if ($p['fare_brand'])
                                                                <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                                                                    {{ $p['fare_brand'] }}
                                                                </span>
                                                            @endif
                                                            @foreach ($p['baggages'] as $bag)
                                                                <span class="text-xs px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                                                                    {{ $bag['quantity'] }}×
                                                                    {{ $bag['type'] === 'checked' ? 'Checked bag' : 'Carry-on' }}
                                                                    @if ($bag['type'] === 'checked' && $p['checked_bag_kg'])
                                                                        ({{ $p['checked_bag_kg'] }}kg)
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>

                                                    </div>

                                                    <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                                    <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                                    {{-- Right: Price & Actions --}}
                                                    <div class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4 shrink-0">
                                                        <div class="flex flex-col lg:items-end">
                                                            <span class="font-semibold text-[24px] leading-[36px] {{ $priceColor }}">
                                                                {{ $order->currency }}
                                                                {{ number_format($order->amount, 2) }}
                                                            </span>
                                                            @if ($order->tax_amount > 0)
                                                                <span class="text-xs text-slate-400">
                                                                    Base {{ number_format($order->amount - $order->tax_amount, 2) }}
                                                                    + Tax {{ number_format($order->tax_amount, 2) }}
                                                                </span>
                                                            @endif
                                                            @if ($flags['isCancelled'])
                                                                <span class="text-xs text-red-500 font-medium">Cancelled</span>
                                                            @elseif($flags['isCompleted'])
                                                                <span class="text-xs text-slate-500">Completed</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            @if ($flags['isUpcoming'])
                                                                <button class="btn btn-red btn-sm"
                                                                    wire:click="openModal('{{ $order->external_id }}')">
                                                                    Cancel
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-primary btn-sm whitespace-nowrap"
                                                                wire:navigate href="{{ route('booking.flight.show',$order->id)}}">
                                                                {{ $flags['isUpcoming'] ? 'View' : 'View Details' }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        @empty
                                            <div class="card p-8 text-center text-slate-400 text-sm">
                                                No {{ $statusLabel }} flights found.
                                            </div>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Date Filter --}}
                        <div class="relative ml-auto md:w-auto w-full">
                            <select class="form-input appearance-none pr-10" wire:model.live="dateRange">
                                <option value="">Date Range</option>
                                <option value="7days">Last 7 days</option>
                                <option value="30days">Last 30 days</option>
                                <option value="3months">Last 3 months</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-tabler="chevron-down" data-size="16"></i>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════
                     HOTELS TAB
                ══════════════════════════════════════════════════════════ --}}
                <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                    <input type="radio" name="my_tabs_booking" wire:click="setType('hotel')"
                        @checked($activeType === 'hotel')>
                    <i data-tabler="building" class="size-5 md:size-7"></i>
                    Hotels
                </label>

                <div class="tab-content mt-2">
                    <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-x-2">

                        @foreach (['upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $statusKey => $statusLabel)
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="hotel_status_tabs"
                                    wire:click="setStatus('{{ $statusKey }}')"
                                    @checked($activeStatus === $statusKey && $activeType === 'hotel')>
                                {{ $statusLabel }}
                            </label>

                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    @if ($activeType === 'hotel')
                                        @forelse($parsedOrders as $item)
                                            @php
                                                $order = $item['order'];
                                                $flags = $item['flags'];
                                                $hotelData = $order->data ?? [];
                                                $priceColor = $flags['isCancelled']
                                                    ? 'text-red-600'
                                                    : ($flags['isCompleted']
                                                        ? 'text-green-600'
                                                        : 'text-blue-600');
                                            @endphp

                                            <div class="card p-4 transition-all hover:shadow-md {{ $flags['isCancelled'] ? 'opacity-75' : '' }}">
                                                <div class="flex flex-col lg:flex-row gap-3 md:gap-6">

                                                    {{-- Hotel Image --}}
                                                    <div class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                                        @if ($hotelData['accommodation']['photos'][0]['url'] ?? null)
                                                            <img src="{{ $hotelData['accommodation']['photos'][0]['url'] }}"
                                                                alt="{{ $hotelData['accommodation']['name'] ?? '' }}"
                                                                class="w-full h-full object-cover">
                                                        @endif
                                                    </div>

                                                    {{-- Hotel Details --}}
                                                    <div class="flex-1 flex flex-col gap-3">
                                                        <div class="text-slate-500 text-base font-normal leading-6">
                                                            {{ $order->order_number }}
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-semibold text-lg text-slate-950">
                                                                {{ $hotelData['accommodation']['name'] ?? '—' }}
                                                            </span>
                                                            <div class="flex items-center gap-1 text-slate-500">
                                                                <i data-tabler="map-pin" data-size="16"></i>
                                                                <span class="font-normal text-sm">
                                                                    {{ $hotelData['accommodation']['location']['address']['city_name'] ?? '' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i data-tabler="calendar" class="text-slate-400" data-size="18"></i>
                                                            <span class="font-normal text-sm text-slate-700">
                                                                {{ $order->booking_date ? \Carbon\Carbon::parse($order->booking_date)->format('D, d M Y') : '—' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                                    <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                                    {{-- Price & Actions --}}
                                                    <div class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                        <div class="flex flex-col lg:items-end">
                                                            <span class="font-semibold text-[24px] leading-[36px] {{ $priceColor }}">
                                                                {{ $order->currency }}
                                                                {{ number_format($order->amount, 2) }}
                                                            </span>
                                                            @if ($flags['isCancelled'])
                                                                <span class="text-xs text-red-500 font-medium">Cancelled</span>
                                                            @elseif($flags['isCompleted'])
                                                                <span class="text-xs text-slate-500">Completed</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            @if ($flags['isUpcoming'])
                                                                <button class="btn btn-red btn-sm whitespace-nowrap"
                                                                    wire:click="openModal('{{ $order->external_id }}')">
                                                                    Cancel
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-primary btn-sm whitespace-nowrap"
                                                                wire:navigate href="{{ $order->id }}">
                                                                {{ $flags['isUpcoming'] ? 'View' : 'View Details' }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        @empty
                                            <div class="card p-8 text-center text-slate-400 text-sm">
                                                No {{ $statusLabel }} hotels found.
                                            </div>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Date Filter --}}
                        <div class="relative ml-auto md:w-auto w-full">
                            <select class="form-input appearance-none pr-10" wire:model.live="dateRange">
                                <option value="">Date Range</option>
                                <option value="7days">Last 7 days</option>
                                <option value="30days">Last 30 days</option>
                                <option value="3months">Last 3 months</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-tabler="chevron-down" data-size="16"></i>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════
                     CAR RENTAL TAB
                ══════════════════════════════════════════════════════════ --}}
                <label class="tab tabs-border tabs-border-inner flex-1 md:flex-none justify-center items-center">
                    <input type="radio" name="my_tabs_booking" wire:click="setType('car')"
                        @checked($activeType === 'car')>
                    <i data-tabler="car" class="size-5 md:size-7"></i>
                    Car Rental
                </label>

                <div class="tab-content mt-2">
                    <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-x-2">

                        @foreach (['upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $statusKey => $statusLabel)
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="car_status_tabs"
                                    wire:click="setStatus('{{ $statusKey }}')"
                                    @checked($activeStatus === $statusKey && $activeType === 'car')>
                                {{ $statusLabel }}
                            </label>

                            <div class="tab-content">
                                <div class="space-y-3.5">
                                    @if ($activeType === 'car')
                                        @forelse($parsedOrders as $item)
                                            @php
                                                $order = $item['order'];
                                                $flags = $item['flags'];
                                                $carData = $order->data ?? [];
                                                $priceColor = $flags['isCancelled']
                                                    ? 'text-red-600'
                                                    : ($flags['isCompleted']
                                                        ? 'text-green-600'
                                                        : 'text-blue-600');
                                            @endphp

                                            <div class="card p-4 transition-all hover:shadow-md {{ $flags['isCancelled'] ? 'opacity-75' : '' }}">
                                                <div class="flex flex-col lg:flex-row gap-3 md:gap-6">

                                                    {{-- Car Image --}}
                                                    <div class="w-full lg:w-[200px] h-[150px] lg:h-auto shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                                                        @if ($carData['vehicle']['photo_url'] ?? null)
                                                            <img src="{{ $carData['vehicle']['photo_url'] }}"
                                                                alt="{{ $carData['vehicle']['name'] ?? '' }}"
                                                                class="w-full h-full object-cover">
                                                        @endif
                                                    </div>

                                                    {{-- Car Details --}}
                                                    <div class="flex-1 flex flex-col gap-3">
                                                        <div class="text-slate-500 text-base font-normal leading-6">
                                                            {{ $order->order_number }}
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-semibold text-lg text-slate-950">
                                                                {{ $carData['vehicle']['name'] ?? '—' }}
                                                            </span>
                                                            <span class="text-sm text-slate-500">
                                                                Provided by
                                                                <span class="font-semibold text-slate-700">
                                                                    {{ $carData['supplier']['name'] ?? '—' }}
                                                                </span>
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i data-tabler="calendar" class="text-slate-400" data-size="18"></i>
                                                            <span class="font-normal text-sm text-slate-700">
                                                                {{ $order->booking_date ? \Carbon\Carbon::parse($order->booking_date)->format('D, d M Y') : '—' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="hidden lg:block w-px bg-slate-200 h-auto self-stretch"></div>
                                                    <div class="lg:hidden h-px bg-slate-100 w-full"></div>

                                                    {{-- Price & Actions --}}
                                                    <div class="flex flex-row lg:flex-col justify-between items-center lg:items-end lg:justify-between gap-4">
                                                        <div class="flex flex-col lg:items-end">
                                                            <span class="font-semibold text-[24px] leading-[36px] {{ $priceColor }}">
                                                                {{ $order->currency }}
                                                                {{ number_format($order->amount, 2) }}
                                                            </span>
                                                            @if ($flags['isCancelled'])
                                                                <span class="text-xs text-red-500 font-medium">Cancelled</span>
                                                            @elseif($flags['isCompleted'])
                                                                <span class="text-xs text-slate-500">Completed</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            @if ($flags['isUpcoming'])
                                                                <button class="btn btn-red btn-sm"
                                                                    wire:click="openModal('{{ $order->external_id }}')">
                                                                    Cancel
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-primary btn-sm whitespace-nowrap"
                                                                wire:navigate href="{{ $order->id }}">
                                                                {{ $flags['isUpcoming'] ? 'View' : 'View Details' }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        @empty
                                            <div class="card p-8 text-center text-slate-400 text-sm">
                                                No {{ $statusLabel }} car rentals found.
                                            </div>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Date Filter --}}
                        <div class="relative ml-auto md:w-auto w-full">
                            <select class="form-input appearance-none pr-10" wire:model.live="dateRange">
                                <option value="">Date Range</option>
                                <option value="7days">Last 7 days</option>
                                <option value="30days">Last 30 days</option>
                                <option value="3months">Last 3 months</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-tabler="chevron-down" data-size="16"></i>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         CANCEL MODAL
    ══════════════════════════════════════════════════════════ --}}
    <dialog id="cancel_booking_modal" class="modal" @if($showCancelModal) open @endif>
        <div class="modal-box max-w-[500px] mx-auto flex flex-col p-0 rounded-2xl overflow-hidden bg-white shadow-md">
            <div class="px-5 pt-9 pb-5 flex flex-col gap-6">
                <div class="flex flex-col items-center gap-3.5">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 shadow-sm">
                        <i data-tabler="alert-triangle" data-stroke="2"></i>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <h3 class="text-2xl md:text-3xl font-bold text-slate-950 leading-8 md:leading-10 text-center">
                            Cancel Booking
                        </h3>
                        <p class="text-sm md:text-base font-medium text-slate-500 leading-5 md:leading-6 text-center">
                            Are you sure you want to cancel this booking? Cancellation charges may apply as per airline
                            policy, and the refund (if applicable) will be processed to your original payment method
                            within 10 days.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button class="btn btn-primary w-full bg-red-600 hover:bg-red-700 border-red-600"
                        wire:click="confirmCancel"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmCancel">Yes, Cancel</span>
                        <span wire:loading wire:target="confirmCancel">Processing...</span>
                    </button>

                    <button class="w-full text-center text-sm font-semibold text-slate-500 leading-5 hover:text-slate-700 transition-colors"
                        wire:click="$set('showCancelModal', false)">
                        No
                    </button>
                </div>
            </div>
        </div>
    </dialog>

</div>