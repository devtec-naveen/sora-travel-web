<div>
    <main class="bg-slate-50 min-h-[800px]">
        <div class="booking-progress-container py-6">
            <div class="container">
                <div class="flex items-center justify-between max-w-5xl mx-auto">
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Search</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900 text-center">Passengers</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100 font-bold text-sm">3</div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Add-ons</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">4</div>
                        <span class="text-xs md:text-sm font-medium text-slate-400 text-center whitespace-nowrap">Select Seat</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">5</div>
                        <span class="text-xs md:text-sm font-medium text-slate-400 text-center">Payment</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="back-btn container">
            <button onclick="history.back()" class="btn btn-white">
                <i data-tabler="chevron-left" data-size="16"></i>Back
            </button>
        </div>

        <div class="booking-page-content py-10 lg:py-16">
            <div class="container">
                <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

                    {{-- Left: Main Content --}}
                    <div class="flex-1 flex flex-col gap-4 md:gap-9">
                        <div class="flex flex-col items-center lg:items-start gap-2.5 px-4 lg:px-0">
                            <h1 class="font-semibold text-[24px] leading-[36px] text-slate-800">Enhance Your Journey</h1>
                            <span class="font-normal text-base text-slate-500">Select optional add-ons for a better travel experience</span>
                        </div>

                        {{-- API Fetch Error --}}
                        @if ($fetchError)
                            <div class="card p-5 flex items-start gap-3 border border-red-100 bg-red-50">
                                <i data-tabler="alert-triangle" class="text-red-500 shrink-0 mt-0.5" data-size="20"></i>
                                <div>
                                    <p class="font-semibold text-sm text-red-700">Unable to load add-ons</p>
                                    <p class="text-sm text-red-500 mt-1">Could not fetch available services from the airline. You can continue without add-ons.</p>
                                </div>
                            </div>

                        @elseif ($noServicesAvailable)
                            <div class="card p-5 flex items-start gap-3 border border-amber-100 bg-amber-50">
                                <i data-tabler="info-circle" class="text-amber-500 shrink-0 mt-0.5" data-size="20"></i>
                                <div>
                                    <p class="font-semibold text-sm text-amber-700">No add-ons available</p>
                                    <p class="text-sm text-amber-600 mt-1">This airline does not offer additional baggage or services through our platform.</p>
                                </div>
                            </div>

                        @else
                            <div class="flex flex-col gap-6">

                                {{-- Extra Baggage --}}
                                <div class="card overflow-hidden">
                                    <div class="flex flex-col justify-center gap-1.5 p-5 border-b border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <i data-tabler="luggage" class="text-slate-950" data-size="24"></i>
                                            <span class="font-semibold text-[20px] leading-[32px] text-slate-950">Extra Baggage</span>
                                        </div>
                                        <span class="font-normal text-sm text-slate-500">
                                            Select one or more baggage options for each passenger.
                                        </span>
                                    </div>

                                    <div class="p-5 lg:p-6 flex flex-col gap-8">

                                        @foreach ($passengers as $pax)
                                            @php
                                                $paxId = $pax['id'] ?? null;
                                                if (! $paxId || ($pax['type'] ?? '') === 'infant') continue;

                                                $typeLabel   = match($pax['type'] ?? 'adult') {
                                                    'adult'  => 'Adult',
                                                    'child'  => 'Child',
                                                    default  => 'Passenger',
                                                };
                                                $paxServices = $this->getServicesForPassenger($paxId);
                                                $name        = trim(($pax['given_name'] ?? $pax['first_name'] ?? '') . ' ' . ($pax['family_name'] ?? $pax['last_name'] ?? '')) ?: $typeLabel;
                                                $paxSelected = $selectedBaggage[$paxId] ?? [];
                                            @endphp

                                            <div class="flex flex-col gap-4">

                                                {{-- Passenger header --}}
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <i data-tabler="user" class="text-slate-400 shrink-0" data-size="18"></i>
                                                        <span class="font-semibold text-base text-slate-900">
                                                            {{ $name }}
                                                            <span class="font-normal text-sm text-slate-400 ml-1">({{ $typeLabel }})</span>
                                                        </span>
                                                    </div>
                                                    @if (! empty($paxSelected))
                                                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                                            {{ count($paxSelected) }} selected
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col gap-3">

                                                    @if (empty($paxServices))
                                                        <div class="text-sm text-slate-400 px-1 py-3 text-center border border-dashed border-slate-200 rounded-xl">
                                                            No additional baggage options available for this passenger.
                                                        </div>
                                                    @else
                                                        @foreach ($paxServices as $svc)
                                                            @php
                                                                $meta        = $svc['metadata'] ?? [];
                                                                $weightKg    = $meta['maximum_weight_kg'] ?? null;
                                                                $bagType     = $meta['baggage_type']      ?? 'checked';
                                                                $maxQty      = $svc['maximum_quantity']   ?? 1;
                                                                $svcCurrency = $svc['total_currency']     ?? $currency;
                                                                $svcAmount   = (float) ($svc['total_amount'] ?? 0);
                                                                $svcId       = $svc['id'];

                                                                $typeLabel2  = ucfirst(str_replace('_', ' ', $bagType));
                                                                $qtyLabel    = $maxQty > 1 ? "{$maxQty}× " : '';
                                                                $fullLabel   = trim("{$qtyLabel}" . ($weightKg ? "{$weightKg}kg " : '') . "{$typeLabel2}");

                                                                $isChecked   = $this->isSelected($paxId, $svcId);
                                                            @endphp

                                                            {{-- Checkbox card --}}
                                                            <button
                                                                wire:key="addon-{{ $paxId }}-{{ $svcId }}"
                                                                wire:click="toggleBaggage('{{ $paxId }}', '{{ $svcId }}')"
                                                                type="button"
                                                                class="w-full flex justify-between items-center p-4 rounded-2xl border transition-all text-left
                                                                    {{ $isChecked
                                                                        ? 'border-blue-500 bg-blue-50'
                                                                        : 'border-slate-200 bg-white hover:border-slate-300' }}">

                                                                <div class="flex items-center gap-3.5">
                                                                    {{-- Custom checkbox --}}
                                                                    <div class="w-5 h-5 rounded flex items-center justify-center shrink-0 border transition-all
                                                                        {{ $isChecked
                                                                            ? 'bg-[#f3b515] border-[#f3b515]'
                                                                            : 'bg-white border-slate-300' }}">
                                                                        @if ($isChecked)
                                                                            <i data-tabler="check" class="text-white" data-size="13" data-stroke="2.5"></i>
                                                                        @endif
                                                                    </div>

                                                                    <div class="flex flex-col gap-0.5">
                                                                        <span class="font-medium text-sm text-slate-950">{{ $fullLabel }}</span>
                                                                        @if ($weightKg)
                                                                            <span class="text-xs text-slate-400">Max {{ $weightKg }}kg checked baggage</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="flex flex-col items-end shrink-0 ml-3">
                                                                    <span class="font-bold text-sm {{ $isChecked ? 'text-blue-600' : 'text-slate-950' }}">
                                                                        + {{ $svcCurrency }} {{ number_format($svcAmount, 2) }}
                                                                    </span>
                                                                    @if ($isChecked)
                                                                        <span class="text-[10px] text-blue-500 font-medium mt-0.5">Added ✓</span>
                                                                    @endif
                                                                </div>
                                                            </button>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                {{-- Meals note --}}
                                <div class="card p-5 flex items-start gap-3 border border-slate-100 bg-slate-50">
                                    <i data-tabler="tools-kitchen-2" class="text-slate-400 shrink-0 mt-0.5" data-size="20"></i>
                                    <div>
                                        <p class="font-semibold text-sm text-slate-700">In-Flight Meals</p>
                                        <p class="text-sm text-slate-500 mt-1">
                                            Meal preferences are managed directly by the airline. Please contact the airline after booking to request a specific meal type.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        @endif

                        {{-- Navigation Buttons --}}
                        <div class="flex justify-between items-center self-stretch mt-2">
                            <button onclick="history.back()" class="btn btn-white min-w-[140px]">Back</button>
                            <button wire:click="continue" class="btn btn-primary min-w-[140px]">
                                <span wire:loading.remove wire:target="continue">Continue</span>
                                <span wire:loading wire:target="continue" class="loading loading-spinner loading-xs"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Right Sidebar --}}
                    <div class="w-full lg:w-[304px] shrink-0 sticky top-24">
                        <div class="flex flex-col md:gap-7 gap-2">
                            <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>

                            @if (!empty($segment))
                                @php
                                    $dep  = $segment['departing_at'] ?? null;
                                    $arr  = $segment['arriving_at']  ?? null;
                                    $orig = $segment['origin']['iata_code']               ?? '';
                                    $dest = $segment['destination']['iata_code']          ?? '';
                                    $logo = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                    $airl = $segment['operating_carrier']['name']          ?? '';
                                    $fno  = $segment['operating_carrier']['iata_code']     ?? '';
                                    $fnum = $segment['operating_carrier_flight_number']    ?? '';
                                    $dur  = $segment['duration']                          ?? '';
                                    $stps = count($slice['segments'] ?? []) - 1;
                                @endphp
                                <div class="card p-4 flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100 shrink-0">
                                        <img src="{{ $logo }}" alt="{{ $airl }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="font-semibold text-base text-slate-950 truncate">{{ $airl }}</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $fno }} {{ $fnum }}</span>
                                    </div>
                                </div>
                                <div class="card p-4 flex flex-row items-center justify-between gap-4">
                                    <div class="flex flex-col items-start">
                                        <span class="font-semibold text-sm text-slate-950">
                                            {{ $dep ? \Carbon\Carbon::parse($dep)->format('h:i A') : '' }}
                                        </span>
                                        <span class="font-normal text-xs text-slate-500">{{ $orig }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5 flex-1">
                                        <span class="font-normal text-xs text-slate-400">
                                            {{ $dur ? \Carbon\CarbonInterval::make($dur)->cascade()->forHumans() : '' }}
                                        </span>
                                        <div class="w-full h-px bg-slate-200 relative">
                                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                        </div>
                                        <span class="font-normal text-xs text-slate-400">
                                            {{ $stps === 0 ? 'Non-stop' : $stps . ' stop' . ($stps > 1 ? 's' : '') }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="font-semibold text-sm text-slate-950">
                                            {{ $arr ? \Carbon\Carbon::parse($arr)->format('h:i A') : '' }}
                                        </span>
                                        <span class="font-normal text-xs text-slate-500">{{ $dest }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Price Breakdown --}}
                            <div class="card p-5 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-normal text-sm text-slate-950">Base Fare</span>
                                    <span class="font-normal text-sm text-slate-500">{{ $currency }} {{ number_format($baseTotal, 2) }}</span>
                                </div>
                                @if ($addonsTotal > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="font-normal text-sm text-slate-950">Extra Baggage</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }} {{ number_format($addonsTotal, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="border-slate-100">
                                <div class="flex justify-between items-center pt-2">
                                    <span class="font-semibold text-lg text-slate-950">Total</span>
                                    <span class="font-bold text-xl text-slate-950">{{ $currency }} {{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>

                            {{-- Selected Addons Summary --}}
                            @php
                                $hasAny = collect($selectedBaggage)->contains(fn($ids) => ! empty($ids));
                            @endphp
                            @if ($hasAny)
                                <div class="card p-4 space-y-4">
                                    <h4 class="font-semibold text-sm text-slate-950">Selected Add-ons</h4>
                                    @foreach ($passengers as $pax)
                                        @php
                                            $paxId     = $pax['id'] ?? null;
                                            if (! $paxId || ($pax['type'] ?? '') === 'infant') continue;
                                            $serviceIds = $selectedBaggage[$paxId] ?? [];
                                            if (empty($serviceIds)) continue;
                                            $paxName = trim(($pax['given_name'] ?? $pax['first_name'] ?? '') . ' ' . ($pax['family_name'] ?? $pax['last_name'] ?? '')) ?: 'Passenger';
                                        @endphp
                                        <div class="flex flex-col gap-2">
                                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $paxName }}</span>
                                            @foreach ($serviceIds as $serviceId)
                                                @php
                                                    $svc = $availableServices[$serviceId] ?? null;
                                                    if (! $svc) continue;
                                                    $meta    = $svc['metadata'] ?? [];
                                                    $weight  = $meta['maximum_weight_kg'] ?? '';
                                                    $bagType = ucfirst(str_replace('_', ' ', $meta['baggage_type'] ?? 'bag'));
                                                    $label   = trim("{$weight}kg {$bagType}");
                                                    $amount  = (float) ($svc['total_amount'] ?? 0);
                                                    $cur     = $svc['total_currency'] ?? $currency;
                                                @endphp
                                                <div class="flex justify-between items-center text-sm">
                                                    <div class="flex items-center gap-1.5 text-slate-700">
                                                        <i data-tabler="luggage" class="text-blue-500 shrink-0" data-size="13"></i>
                                                        {{ $label }}
                                                    </div>
                                                    <span class="font-semibold text-slate-800">{{ $cur }} {{ number_format($amount, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>