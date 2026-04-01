<div wire:init="loadData">
   <x-loader
        message="Please Wait..."
        targets="loadData"
    />
    <main class="bg-slate-50 min-h-[800px]">
        <div class="booking-progress-container py-4 md:py-6">
            <div class="container">
                <div class="flex items-center justify-between max-w-5xl mx-auto px-2">
                    @php $steps = ['Search','Passengers','Add-ons','Seat','Payment']; @endphp
                    @foreach ($steps as $si => $step)
                        <div class="flex flex-col items-center gap-1.5 shrink-0">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100 text-xs sm:text-sm font-bold">
                                @if ($si < 4)
                                    <i data-tabler="check" data-size="14"></i>
                                @else
                                    5
                                @endif
                            </div>
                            <span class="hidden sm:block text-xs md:text-sm font-medium text-slate-900 text-center whitespace-nowrap">{{ $step }}</span>
                            <span class="block sm:hidden text-[10px] font-medium text-slate-900 text-center leading-tight w-10 truncate">{{ $step }}</span>
                        </div>
                        @if (! $loop->last)
                            <div class="grow h-0.5 bg-blue-600 rounded-full mx-0.5 sm:mx-1"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="back-btn container px-4">
            <button onclick="history.back()" class="btn btn-white">
                <i data-tabler="chevron-left" data-size="16"></i>Back
            </button>
        </div>
        <div class="booking-page-content py-8 lg:py-16">
            <div class="container px-4">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 lg:items-start">
                    <div class="flex-1 flex flex-col gap-5 md:gap-6 min-w-0">
                        <div class="flex flex-col gap-1.5">
                            <h1 class="font-semibold text-xl md:text-[24px] leading-tight text-slate-800">Review Your Booking</h1>
                            <span class="font-normal text-sm md:text-base text-slate-500">Please review all details before proceeding to payment</span>
                        </div>
                        @foreach ($slices as $sliceIdx => $slice)
                            @php
                                $segment  = $slice['segments'][0] ?? [];
                                $dep      = $segment['departing_at'] ?? null;
                                $arr      = $segment['arriving_at']  ?? null;
                                $orig     = $segment['origin']['iata_code']               ?? '';
                                $dest     = $segment['destination']['iata_code']          ?? '';
                                $origCity = $segment['origin']['city_name']               ?? $orig;
                                $destCity = $segment['destination']['city_name']          ?? $dest;
                                $origTerm = $segment['origin']['terminal']                ?? null;
                                $destTerm = $segment['destination']['terminal']           ?? null;
                                $logo     = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                $airline  = $segment['operating_carrier']['name']          ?? '';
                                $fno      = ($segment['operating_carrier']['iata_code'] ?? '') . ($segment['operating_carrier_flight_number'] ?? '');
                                $dur      = $segment['duration'] ?? '';
                                $cabin    = ucfirst($slice['fare_brand_name'] ?? ($selectedFlight['cabin_class'] ?? 'Economy'));
                                $stops    = count($slice['segments'] ?? []) - 1;

                                $paxBaggages = $segment['passengers'][0]['baggages'] ?? [];
                                $cabinBag    = collect($paxBaggages)->firstWhere('type', 'carry_on');
                                $checkedBag  = collect($paxBaggages)->firstWhere('type', 'checked');
                                $baggageStr  = '';
                                if ($cabinBag)   $baggageStr .= ($cabinBag['quantity'] ?? 1)   . ' × cabin bag';
                                if ($checkedBag) $baggageStr .= ($baggageStr ? ' + ' : '') . ($checkedBag['quantity'] ?? 1) . ' × ' . ($checkedBag['maximum_weight_kg'] ?? '') . 'kg';
                                if (! $baggageStr) $baggageStr = 'Per fare conditions';

                                $conditions   = $selectedFlight['conditions'] ?? [];
                                $isRefundable = ($conditions['refund_before_departure']['allowed'] ?? false);
                            @endphp
                            <div class="card overflow-hidden">
                                <div class="flex items-center gap-3 px-4 py-4 md:p-5 border-b border-slate-100">
                                    <i data-tabler="plane-departure" class="text-slate-600 shrink-0" data-size="18"></i>
                                    <h2 class="font-semibold text-base md:text-lg text-slate-950">
                                        {{ count($slices) > 1 ? ($sliceIdx === 0 ? 'Outbound Flight' : 'Return Flight') : 'Flight Details' }}
                                    </h2>
                                </div>
                                <div class="flex flex-col gap-5 p-4 md:p-5">
                                    <div class="flex items-center gap-3">
                                        @if ($logo)
                                            <img src="{{ $logo }}"
                                                class="w-10 h-10 md:w-11 md:h-11 object-contain rounded-xl border border-slate-100 shrink-0"
                                                alt="{{ $airline }}">
                                        @endif
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-semibold text-sm md:text-base text-slate-950 truncate">{{ $airline }}</span>
                                            <span class="text-xs md:text-sm text-slate-500">{{ $fno }}</span>
                                        </div>
                                        <div class="ml-auto shrink-0">
                                            @if ($isRefundable)
                                                <span class="tag tag-green text-xs">
                                                    <i data-tabler="check" data-size="12"></i>Refundable
                                                </span>
                                            @else
                                                <span class="tag tag-red text-xs">
                                                    <i data-tabler="x" data-size="12"></i>Non-refundable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2 sm:gap-4 self-stretch">
                                        <div class="flex flex-col items-start shrink-0 min-w-0 max-w-[30%]">
                                            <span class="font-bold text-lg sm:text-xl text-slate-950 leading-tight">
                                                {{ $dep ? \Carbon\Carbon::parse($dep)->format('H:i') : '—' }}
                                            </span>
                                            <span class="font-semibold text-xs sm:text-sm text-slate-700 truncate w-full">{{ $origCity }}</span>
                                            <span class="font-medium text-xs text-blue-600">({{ $orig }})</span>
                                            @if ($origTerm)
                                                <span class="text-[11px] text-slate-400 leading-tight">T{{ $origTerm }}</span>
                                            @endif
                                            @if ($dep)
                                                <span class="text-[11px] text-slate-400 leading-tight mt-0.5">
                                                    {{ \Carbon\Carbon::parse($dep)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-center gap-1 flex-1 pt-1.5">
                                            <span class="text-[10px] sm:text-xs text-slate-400 whitespace-nowrap">
                                                {{ $dur ? \Carbon\CarbonInterval::make($dur)->cascade()->forHumans(['parts' => 2]) : '' }}
                                            </span>
                                            <div class="w-full flex items-center gap-0.5 sm:gap-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></div>
                                                <div class="flex-1 h-px bg-slate-300"></div>
                                                @if ($stops > 0)
                                                    <div class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></div>
                                                    <div class="flex-1 h-px bg-slate-300"></div>
                                                @endif
                                                <i data-tabler="plane" class="text-blue-600 shrink-0" data-size="14"></i>
                                            </div>
                                            <span class="text-[10px] sm:text-xs text-slate-400 whitespace-nowrap">
                                                {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                            </span>
                                        </div>
                                        <div class="flex flex-col items-end shrink-0 min-w-0 max-w-[30%]">
                                            <span class="font-bold text-lg sm:text-xl text-slate-950 leading-tight">
                                                {{ $arr ? \Carbon\Carbon::parse($arr)->format('H:i') : '—' }}
                                            </span>
                                            <span class="font-semibold text-xs sm:text-sm text-slate-700 truncate w-full text-right">{{ $destCity }}</span>
                                            <span class="font-medium text-xs text-blue-600">({{ $dest }})</span>
                                            @if ($destTerm)
                                                <span class="text-[11px] text-slate-400 leading-tight">T{{ $destTerm }}</span>
                                            @endif
                                            @if ($arr)
                                                <span class="text-[11px] text-slate-400 leading-tight mt-0.5">
                                                    {{ \Carbon\Carbon::parse($arr)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Info pills — mobile scrollable row --}}
                                    <div class="flex flex-wrap gap-2 pt-1 border-t border-slate-100">
                                        <div class="flex flex-col gap-0.5 bg-slate-50 rounded-xl px-3 py-2 min-w-[90px]">
                                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">Cabin</span>
                                            <span class="font-semibold text-xs text-slate-800">{{ $cabin }}</span>
                                        </div>
                                        <div class="flex flex-col gap-0.5 bg-slate-50 rounded-xl px-3 py-2 min-w-[120px]">
                                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">Baggage</span>
                                            <span class="font-semibold text-xs text-slate-800">{{ $baggageStr }}</span>
                                        </div>
                                        <div class="flex flex-col gap-0.5 bg-slate-50 rounded-xl px-3 py-2 min-w-[80px]">
                                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">Stops</span>
                                            <span class="font-semibold text-xs text-slate-800">
                                                {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        <div class="card overflow-hidden">
                            <div class="flex items-center gap-3 px-4 py-4 md:p-5 border-b border-slate-100">
                                <i data-tabler="users" class="text-slate-600 shrink-0" data-size="18"></i>
                                <h2 class="font-semibold text-base md:text-lg text-slate-950">Passenger Details</h2>
                            </div>

                            <div class="flex flex-col divide-y divide-slate-100">
                                @foreach ($passengers as $idx => $pax)
                                    @php
                                        $typeLabel = match($pax['type'] ?? 'adult') {
                                            'adult'               => 'Adult',
                                            'child'               => 'Child',
                                            'infant_without_seat' => 'Infant',
                                            default               => 'Passenger',
                                        };
                                        $title    = ucfirst($pax['title'] ?? '');
                                        $fname    = $pax['first_name']      ?? $pax['given_name']  ?? '';
                                        $lname    = $pax['last_name']       ?? $pax['family_name'] ?? '';
                                        $gender   = $pax['gender']          ?? '';
                                        $dob      = $pax['dob']             ?? $pax['born_on']     ?? '';
                                        $passport = $pax['passport_no']     ?? $pax['identity_documents'][0]['unique_identifier'] ?? '';
                                        $expiry   = $pax['passport_expiry'] ?? $pax['identity_documents'][0]['expires_on']        ?? '';
                                        $national = $pax['nationality']     ?? $pax['identity_documents'][0]['issuing_country_code'] ?? '';

                                        $paxId   = $pax['id'] ?? "pax_{$idx}";
                                        $paxSeat = null;
                                        foreach ($selectedSeats as $segSeats) {
                                            foreach ($segSeats as $pk => $seat) {
                                                if (($pk === $paxId || str_ends_with($pk, "_{$idx}")) && $seat) {
                                                    $paxSeat = $seat; break 2;
                                                }
                                            }
                                        }
                                        $paxAddon = $addons[$paxId] ?? null;
                                    @endphp
                                    <div class="flex flex-col gap-4 p-4 md:p-5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                                                <span class="text-xs font-bold text-blue-600">{{ $idx + 1 }}</span>
                                            </div>
                                            <span class="font-semibold text-base text-slate-950">
                                                {{ trim("$title $fname $lname") ?: 'Passenger ' . ($idx + 1) }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-medium shrink-0">
                                                {{ $typeLabel }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-4">
                                            @if ($fname || $lname)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Full Name</span>
                                                    <span class="font-semibold text-sm text-slate-900 break-words">{{ trim("$title $fname $lname") }}</span>
                                                </div>
                                            @endif
                                            @if ($gender)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Gender</span>
                                                    <span class="font-semibold text-sm text-slate-900">
                                                        {{ $gender === 'm' ? 'Male' : ($gender === 'f' ? 'Female' : ucfirst($gender)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($dob)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Date of Birth</span>
                                                    <span class="font-semibold text-sm text-slate-900">{{ \Carbon\Carbon::parse($dob)->format('d M Y') }}</span>
                                                </div>
                                            @endif
                                            @if ($national)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Nationality</span>
                                                    <span class="font-semibold text-sm text-slate-900">{{ strtoupper($national) }}</span>
                                                </div>
                                            @endif
                                            @if ($passport)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Passport No.</span>
                                                    <span class="font-semibold text-sm text-slate-900 tracking-wide">{{ $passport }}</span>
                                                </div>
                                            @endif
                                            @if ($expiry)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Expiry Date</span>
                                                    <span class="font-semibold text-sm text-slate-900">{{ \Carbon\Carbon::parse($expiry)->format('d M Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if ($paxSeat || ($paxAddon && ($paxAddon['baggage_service_id'] ?? null)))
                                            <div class="flex flex-wrap gap-2">
                                                @if ($paxSeat)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                                        <i data-tabler="armchair" data-size="12"></i>
                                                        Seat {{ $paxSeat['designator'] }}
                                                        · {{ ($paxSeat['amount'] ?? 0) > 0 ? $paxSeat['currency'] . ' ' . number_format($paxSeat['amount'], 2) : 'Free' }}
                                                    </span>
                                                @endif
                                                @if ($paxAddon && ($paxAddon['baggage_service_id'] ?? null))
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold">
                                                        <i data-tabler="luggage" data-size="12"></i>
                                                        {{ $paxAddon['baggage_label'] ?? 'Extra Baggage' }}
                                                        · {{ $paxAddon['baggage_currency'] }} {{ number_format($paxAddon['baggage_price'] ?? 0, 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card overflow-hidden">
                            <div class="flex items-center gap-3 px-4 py-4 md:p-5 border-b border-slate-100">
                                <i data-tabler="mail" class="text-slate-600 shrink-0" data-size="18"></i>
                                <h2 class="font-semibold text-base md:text-lg text-slate-950">Contact Information</h2>
                            </div>
                            <div class="p-4 md:p-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Email Address</span>
                                        <span class="font-semibold text-sm text-slate-900 break-all">{{ $contact['email'] ?? '—' }}</span>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Phone Number</span>
                                        <span class="font-semibold text-sm text-slate-900">{{ $contact['phone'] ?? $contact['phone_number'] ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $conditions = $selectedFlight['conditions'] ?? [];
                            $refund     = $conditions['refund_before_departure'] ?? [];
                            $change     = $conditions['change_before_departure'] ?? [];
                            $rules = [];

                            if (! empty($refund['allowed'])) {
                                $p = $refund['penalty_amount'] ?? null;
                                $rules[] = ['icon' => 'check', 'color' => 'text-green-600', 'bg' => 'bg-green-50',
                                    'text' => 'Cancellation allowed before departure' . ($p ? ' (Penalty: ' . ($refund['penalty_currency'] ?? '') . ' ' . $p . ')' : ' — no penalty')];
                            } else {
                                $rules[] = ['icon' => 'x', 'color' => 'text-red-500', 'bg' => 'bg-red-50',
                                    'text' => 'Non-refundable — cancellation not permitted'];
                            }
                            if (! empty($change['allowed'])) {
                                $p = $change['penalty_amount'] ?? null;
                                $rules[] = ['icon' => 'check', 'color' => 'text-green-600', 'bg' => 'bg-green-50',
                                    'text' => 'Date changes allowed before departure' . ($p ? ' (Fee: ' . ($change['penalty_currency'] ?? '') . ' ' . $p . ')' : '')];
                            } else {
                                $rules[] = ['icon' => 'x', 'color' => 'text-red-500', 'bg' => 'bg-red-50',
                                    'text' => 'Date changes not allowed'];
                            }
                        @endphp
                        <div class="card overflow-hidden">
                            <div class="flex items-center gap-3 px-4 py-4 md:p-5 border-b border-slate-100">
                                <i data-tabler="file-description" class="text-slate-600 shrink-0" data-size="18"></i>
                                <h2 class="font-semibold text-base md:text-lg text-slate-950">Fare Rules & Policies</h2>
                            </div>
                            <div class="flex flex-col gap-3 p-4 md:p-5">
                                @foreach ($rules as $rule)
                                    <div class="flex items-start gap-3 p-3 rounded-xl {{ $rule['bg'] }}">
                                        <i data-tabler="{{ $rule['icon'] }}" class="{{ $rule['color'] }} shrink-0 mt-0.5" data-size="15"></i>
                                        <span class="text-sm text-slate-700 leading-relaxed">{{ $rule['text'] }}</span>
                                    </div>
                                @endforeach
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50">
                                    <i data-tabler="info-circle" class="text-slate-400 shrink-0 mt-0.5" data-size="15"></i>
                                    <span class="text-sm text-slate-500 leading-relaxed">No-show charges may apply as per airline policy.</span>
                                </div>
                            </div>
                        </div>
                        <div class="block lg:hidden card p-4 space-y-3">
                            <h3 class="font-semibold text-base text-slate-800">Price Summary</h3>
                            @php
                                $paxTypes = [];
                                foreach ($passengers as $p) {
                                    $t = $p['type'] ?? 'adult';
                                    $paxTypes[$t] = ($paxTypes[$t] ?? 0) + 1;
                                }
                                $totalPax   = max(1, $adults + $children);
                                $perPaxFare = round($baseTotal / $totalPax, 2);
                            @endphp
                            @if (($paxTypes['adult'] ?? 0) > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Base Fare ({{ $paxTypes['adult'] }} {{ $paxTypes['adult'] > 1 ? 'Adults' : 'Adult' }})</span>
                                    <span class="text-slate-700 font-medium">{{ $currency }} {{ number_format($perPaxFare * $paxTypes['adult'], 2) }}</span>
                                </div>
                            @endif
                            @if (($paxTypes['child'] ?? 0) > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Base Fare ({{ $paxTypes['child'] }} {{ $paxTypes['child'] > 1 ? 'Children' : 'Child' }})</span>
                                    <span class="text-slate-700 font-medium">{{ $currency }} {{ number_format($perPaxFare * $paxTypes['child'], 2) }}</span>
                                </div>
                            @endif
                            @if ($addonsTotal > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Add-ons</span>
                                    <span class="text-slate-700 font-medium">{{ $currency }} {{ number_format($addonsTotal, 2) }}</span>
                                </div>
                            @endif
                            @if ($seatTotal > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Seat Selection</span>
                                    <span class="text-slate-700 font-medium">{{ $currency }} {{ number_format($seatTotal, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                <span class="font-semibold text-base text-slate-950">Total</span>
                                <span class="font-bold text-lg text-slate-950">{{ $currency }} {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center gap-4 py-2">
                            <button onclick="history.back()" class="btn btn-white min-w-[110px] sm:min-w-[130px]">Back</button>
                            <button wire:click="confirm" class="btn btn-primary flex-1 sm:flex-none sm:min-w-[160px]">
                                <span wire:loading.remove wire:target="confirm">
                                    Contiune
                                </span>
                                <span wire:loading wire:target="confirm" class="loading loading-spinner loading-xs"></span>
                            </button>
                        </div>
                    </div>
                    <div class="hidden lg:block w-[304px] shrink-0 sticky top-24">
                        <div class="flex flex-col gap-5">
                            <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>
                            @php
                                $seg0  = ($selectedFlight['slices'][0]['segments'][0] ?? []);
                                $dep0  = $seg0['departing_at'] ?? null;
                                $arr0  = $seg0['arriving_at']  ?? null;
                                $o     = $seg0['origin']['iata_code']               ?? '';
                                $d     = $seg0['destination']['iata_code']          ?? '';
                                $logo0 = $seg0['operating_carrier']['logo_symbol_url'] ?? '';
                                $airl0 = $seg0['operating_carrier']['name']          ?? '';
                                $fno0  = ($seg0['operating_carrier']['iata_code'] ?? '') . ($seg0['operating_carrier_flight_number'] ?? '');
                                $dur0  = $seg0['duration'] ?? '';
                                $stps0 = count($selectedFlight['slices'][0]['segments'] ?? []) - 1;
                            @endphp
                            @if ($logo0 || $airl0)
                                <div class="card p-4 flex items-center gap-3">
                                    @if ($logo0)
                                        <div class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100 shrink-0">
                                            <img src="{{ $logo0 }}" alt="{{ $airl0 }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="font-semibold text-base text-slate-950 truncate">{{ $airl0 }}</span>
                                        <span class="text-sm text-slate-500">{{ $fno0 }}</span>
                                    </div>
                                </div>
                                <div class="card p-4 flex items-center justify-between gap-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-sm text-slate-950">{{ $dep0 ? \Carbon\Carbon::parse($dep0)->format('H:i') : '' }}</span>
                                        <span class="text-xs text-slate-500">{{ $o }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5 flex-1">
                                        <span class="text-xs text-slate-400">{{ $dur0 ? \Carbon\CarbonInterval::make($dur0)->cascade()->forHumans(['parts'=>2]) : '' }}</span>
                                        <div class="w-full h-px bg-slate-200 relative">
                                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                        </div>
                                        <span class="text-xs text-slate-400">{{ $stps0 === 0 ? 'Non-stop' : $stps0 . ' stop' . ($stps0 > 1 ? 's' : '') }}</span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="font-semibold text-sm text-slate-950">{{ $arr0 ? \Carbon\Carbon::parse($arr0)->format('H:i') : '' }}</span>
                                        <span class="text-xs text-slate-500">{{ $d }}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="card p-5 space-y-3.5">
                                @php
                                    $paxTypes2  = [];
                                    foreach ($passengers as $p) { $t = $p['type'] ?? 'adult'; $paxTypes2[$t] = ($paxTypes2[$t] ?? 0) + 1; }
                                    $totalPax2  = max(1, $adults + $children);
                                    $perPax2    = round($baseTotal / $totalPax2, 2);
                                @endphp
                                @if (($paxTypes2['adult'] ?? 0) > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-700">Base Fare ({{ $paxTypes2['adult'] }} {{ $paxTypes2['adult'] > 1 ? 'Adults' : 'Adult' }})</span>
                                        <span class="text-sm text-slate-500">{{ $currency }} {{ number_format($perPax2 * $paxTypes2['adult'], 2) }}</span>
                                    </div>
                                @endif
                                @if (($paxTypes2['child'] ?? 0) > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-700">Base Fare ({{ $paxTypes2['child'] }} {{ $paxTypes2['child'] > 1 ? 'Children' : 'Child' }})</span>
                                        <span class="text-sm text-slate-500">{{ $currency }} {{ number_format($perPax2 * $paxTypes2['child'], 2) }}</span>
                                    </div>
                                @endif
                                @if ($addonsTotal > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-700">Add-ons</span>
                                        <span class="text-sm text-slate-500">{{ $currency }} {{ number_format($addonsTotal, 2) }}</span>
                                    </div>
                                @endif
                                @if ($seatTotal > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-slate-700">Seat Selection</span>
                                        <span class="text-sm text-slate-500">{{ $currency }} {{ number_format($seatTotal, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="border-slate-100">
                                <div class="flex justify-between items-center pt-1">
                                    <span class="font-semibold text-lg text-slate-950">Total</span>
                                    <span class="font-bold text-xl text-slate-950">{{ $currency }} {{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 px-1">
                                <i data-tabler="shield-check" class="text-green-600 shrink-0" data-size="18"></i>
                                <span class="text-xs text-slate-500">Secure booking — your data is protected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>