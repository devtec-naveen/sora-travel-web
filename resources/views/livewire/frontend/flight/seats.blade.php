<div wire:init="loadData">
    <x-loader message="Please Wait..." targets="loadData" />
    <main class="bg-slate-50 min-h-[800px]">
        <div class="booking-progress-container py-6">
            <div class="container">
                <div class="flex items-center justify-between max-w-5xl mx-auto">
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Search</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Passengers</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Add-ons</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100 font-bold text-sm">
                            4</div>
                        <span class="text-xs md:text-sm font-medium text-slate-900 whitespace-nowrap">Select Seat</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">
                            5</div>
                        <span class="text-xs md:text-sm font-medium text-slate-400">Payment</span>
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
                    <div class="flex-1 flex flex-col gap-6">
                        <div class="flex flex-col items-center lg:items-start gap-2.5 px-4 lg:px-0">
                            <h1 class="font-semibold text-[24px] leading-[36px] text-slate-800">Select your seat</h1>
                            <span class="font-normal text-base text-slate-500">Travelers under the age of 2 must sit on
                                an adult's lap</span>
                        </div>
                        @if ($fetchError)
                            <div class="card p-5 flex items-start gap-3 border border-red-100 bg-red-50">
                                <i data-tabler="alert-triangle" class="text-red-500 shrink-0 mt-0.5" data-size="20"></i>
                                <div>
                                    <p class="font-semibold text-sm text-red-700">Unable to load seat map</p>
                                    <p class="text-sm text-red-500 mt-1">Could not fetch seat maps from the airline. You
                                        can skip seat selection and continue.</p>
                                </div>
                            </div>
                        @elseif ($noSeatsAvailable)
                            <div class="card p-5 flex items-start gap-3 border border-amber-100 bg-amber-50">
                                <i data-tabler="info-circle" class="text-amber-500 shrink-0 mt-0.5" data-size="20"></i>
                                <div>
                                    <p class="font-semibold text-sm text-amber-700">Seat selection not available</p>
                                    <p class="text-sm text-amber-600 mt-1">This airline does not support seat selection
                                        through our platform. Seats will be assigned at check-in.</p>
                                </div>
                            </div>
                        @else
                            @if (count($seatMaps) > 1)
                                <div class="flex gap-2 flex-wrap">
                                    @foreach ($seatMaps as $mi => $map)
                                        @php
                                            $seg = $selectedFlight['slices'][$mi]['segments'][0] ?? [];
                                            $orig = $seg['origin']['iata_code'] ?? 'DEP';
                                            $dest = $seg['destination']['iata_code'] ?? 'ARR';
                                        @endphp
                                        <button wire:key="tab-map-{{ $mi }}"
                                            wire:click="setActiveMap({{ $mi }})"
                                            class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all
                                                {{ $activeMapIndex === $mi
                                                    ? 'bg-blue-700 text-white border-blue-700'
                                                    : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300' }}">
                                            {{ $orig }} → {{ $dest }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            @php $currentMap = $seatMaps[$activeMapIndex] ?? null; @endphp
                            @if ($currentMap)
                                @if (count($passengerMeta) > 1)
                                    <div class="flex gap-2 flex-wrap">
                                        @foreach ($passengerMeta as $paxKey => $meta)
                                            @php
                                                $pSeat = $selectedSeats[$activeMapIndex][$paxKey] ?? null;
                                                $isActive = $activePassengerKey === $paxKey;
                                            @endphp
                                            <button wire:key="tab-pax-{{ $paxKey }}"
                                                wire:click="setActivePassenger('{{ $paxKey }}')"
                                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium border transition-all
                                                    {{ $isActive
                                                        ? 'bg-blue-50 text-blue-700 border-blue-400'
                                                        : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300' }}">
                                                <i data-tabler="user" data-size="14"></i>
                                                {{ $meta['name'] }}
                                                @if ($pSeat)
                                                    <span wire:key="badge-{{ $paxKey }}"
                                                        class="px-1.5 py-0.5 rounded-md bg-[#f3b515] text-white text-xs font-bold">
                                                        {{ $pSeat['designator'] }}
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                                @php
                                    $seg0 = $selectedFlight['slices'][$activeMapIndex]['segments'][0] ?? [];
                                    $orig = $seg0['origin']['iata_code'] ?? '';
                                    $dest = $seg0['destination']['iata_code'] ?? '';
                                    $origCity = $seg0['origin']['city_name'] ?? $orig;
                                    $destCity = $seg0['destination']['city_name'] ?? $dest;
                                    $activeDuffelId = $passengerMeta[$activePassengerKey]['duffel_id'] ?? null;
                                @endphp
                                <div class="card overflow-hidden">
                                    <div class="flex items-center justify-between p-5 border-b border-slate-100">
                                        <span class="font-semibold text-lg text-slate-950">
                                            {{ $origCity }} ({{ $orig }}) → {{ $destCity }}
                                            ({{ $dest }})
                                        </span>
                                    </div>
                                    <div class="p-5 lg:p-8">
                                        <div class="flex flex-col xl:flex-row gap-8">
                                            <div class="flex-1 overflow-x-auto pb-2">
                                                @foreach ($currentMap['cabins'] ?? [] as $cabinIdx => $cabin)
                                                    @if (count($currentMap['cabins']) > 1)
                                                        <div class="mb-4">
                                                            <span
                                                                class="text-xs font-bold uppercase tracking-widest text-slate-400 bg-slate-100 px-3 py-1 rounded-full">
                                                                {{ ucfirst(str_replace('_', ' ', $cabin['cabin_class'] ?? 'economy')) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    @php
                                                        $headerSections = [];
                                                        foreach ($cabin['rows'] ?? [] as $row) {
                                                            $hasSeat = collect($row['sections'] ?? [])
                                                                ->flatMap(fn($s) => $s['elements'] ?? [])
                                                                ->contains(fn($e) => ($e['type'] ?? '') === 'seat');
                                                            if ($hasSeat) {
                                                                $headerSections = $row['sections'] ?? [];
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="inline-block min-w-max">
                                                        <div class="flex items-center gap-1 mb-2 pl-10">
                                                            @foreach ($headerSections as $secIdx => $section)
                                                                @if ($secIdx > 0)
                                                                    <div class="w-6 shrink-0"></div>
                                                                @endif
                                                                @foreach ($section['elements'] ?? [] as $el)
                                                                    @if (($el['type'] ?? '') !== 'seat')
                                                                        @continue
                                                                    @endif
                                                                    @php $letter = preg_replace('/[0-9]/', '', $el['designator'] ?? ''); @endphp
                                                                    <div
                                                                        class="w-9 h-7 flex items-center justify-center shrink-0">
                                                                        <span
                                                                            class="font-semibold text-sm text-slate-700">{{ $letter }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                        @foreach ($cabin['rows'] ?? [] as $rowIdx => $row)
                                                            @php
                                                                $sections = $row['sections'] ?? [];
                                                                $allElements = collect($sections)->flatMap(
                                                                    fn($s) => $s['elements'] ?? [],
                                                                );
                                                                $hasSeats = $allElements->contains(
                                                                    fn($e) => ($e['type'] ?? '') === 'seat',
                                                                );
                                                                $hasExit = $allElements->contains(
                                                                    fn($e) => ($e['type'] ?? '') === 'exit_row',
                                                                );
                                                                $firstSeat = $allElements->firstWhere('type', 'seat');
                                                                $rowNum = $firstSeat
                                                                    ? preg_replace(
                                                                        '/[^0-9]/',
                                                                        '',
                                                                        $firstSeat['designator'] ?? '',
                                                                    )
                                                                    : '';
                                                            @endphp
                                                            @if (!$hasSeats)
                                                                @if ($hasExit)
                                                                    <div wire:key="exit-{{ $cabinIdx }}-{{ $rowIdx }}"
                                                                        class="flex items-center gap-2 my-1 pl-10">
                                                                        <div class="h-0.5 w-4 bg-orange-300 rounded">
                                                                        </div>
                                                                        <span
                                                                            class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Exit
                                                                            Row</span>
                                                                        <div class="h-0.5 flex-1 bg-orange-300 rounded">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @continue
                                                            @endif
                                                            <div wire:key="row-{{ $cabinIdx }}-{{ $rowNum }}"
                                                                class="flex items-center gap-1 mb-1.5">
                                                                <div
                                                                    class="w-8 shrink-0 flex items-center justify-end pr-1">
                                                                    <span
                                                                        class="font-semibold text-sm text-slate-500">{{ $rowNum }}</span>
                                                                </div>
                                                                @foreach ($sections as $secIdx => $section)
                                                                    @if ($secIdx > 0)
                                                                        <div
                                                                            class="w-6 shrink-0 flex items-center justify-center">
                                                                            <div class="w-px h-8 bg-slate-200"></div>
                                                                        </div>
                                                                    @endif
                                                                    @foreach ($section['elements'] ?? [] as $elIdx => $el)
                                                                        @if (($el['type'] ?? '') !== 'seat')
                                                                            @continue
                                                                        @endif
                                                                        @php
                                                                            $designator = $el['designator'] ?? '';
                                                                            $svcList = $el['available_services'] ?? [];

                                                                            if ($activeDuffelId) {
                                                                                $activeSvc = collect($svcList)->first(
                                                                                    fn($s) => ($s['passenger_id'] ??
                                                                                        '') ===
                                                                                        $activeDuffelId,
                                                                                );
                                                                            } else {
                                                                                $activePaxIdx =
                                                                                    $passengerMeta[$activePassengerKey][
                                                                                        'index'
                                                                                    ] ?? 0;
                                                                                $activeSvc =
                                                                                    $svcList[$activePaxIdx] ??
                                                                                    ($svcList[0] ?? null);
                                                                            }

                                                                            $isAvailable = !empty($activeSvc);
                                                                            $activeSel =
                                                                                $selectedSeats[$activeMapIndex][
                                                                                    $activePassengerKey
                                                                                ] ?? null;
                                                                            $isSelectedMe =
                                                                                $activeSel &&
                                                                                $activeSel['designator'] ===
                                                                                    $designator;

                                                                            $takenByOther = false;
                                                                            $takenByName = '';
                                                                            foreach (
                                                                                $selectedSeats[$activeMapIndex]
                                                                                as $pk => $seat
                                                                            ) {
                                                                                if (
                                                                                    $pk !== $activePassengerKey &&
                                                                                    $seat &&
                                                                                    $seat['designator'] === $designator
                                                                                ) {
                                                                                    $takenByOther = true;
                                                                                    $takenByName =
                                                                                        $passengerMeta[$pk]['name'] ??
                                                                                        '';
                                                                                    break;
                                                                                }
                                                                            }

                                                                            $svcId = $activeSvc['id'] ?? '';
                                                                            $amount =
                                                                                (float) ($activeSvc['total_amount'] ??
                                                                                    0);
                                                                            $cur =
                                                                                $activeSvc['total_currency'] ??
                                                                                $currency;
                                                                        @endphp
                                                                        @if ($takenByOther)
                                                                            <div wire:key="seat-{{ $activeMapIndex }}-{{ $designator }}-{{ $activePassengerKey }}"
                                                                                title="{{ $designator }} – {{ $takenByName }}"
                                                                                class="w-9 h-9 flex justify-center items-center bg-purple-100 rounded-xl border border-purple-200 text-purple-500 shrink-0 cursor-not-allowed">
                                                                                <i data-tabler="user" data-size="16"
                                                                                    data-stroke="1.8"></i>
                                                                            </div>
                                                                        @elseif ($isSelectedMe)
                                                                            <button
                                                                                wire:key="seat-{{ $activeMapIndex }}-{{ $designator }}-{{ $activePassengerKey }}"
                                                                                wire:click="selectSeat({{ $activeMapIndex }}, '{{ $designator }}', '{{ $svcId }}', {{ $amount }}, '{{ $cur }}')"
                                                                                wire:loading.attr="disabled"
                                                                                wire:target="selectSeat"
                                                                                title="Click to deselect {{ $designator }}"
                                                                                class="seat-selected w-9 h-9 flex justify-center items-center bg-[#f3b515] rounded-xl border-2 border-[#e0a512] text-white shrink-0 shadow-md shadow-amber-200 transition-all duration-150">
                                                                                <i data-tabler="armchair"
                                                                                    data-size="18"
                                                                                    data-stroke="1.8"></i>
                                                                            </button>
                                                                        @elseif ($isAvailable)
                                                                            <button
                                                                                wire:key="seat-{{ $activeMapIndex }}-{{ $designator }}-{{ $activePassengerKey }}"
                                                                                wire:click="selectSeat({{ $activeMapIndex }}, '{{ $designator }}', '{{ $svcId }}', {{ $amount }}, '{{ $cur }}')"
                                                                                wire:loading.attr="disabled"
                                                                                wire:target="selectSeat"
                                                                                title="{{ $designator }}{{ $amount > 0 ? ' – ' . $cur . ' ' . number_format($amount, 2) : ' – Free' }}"
                                                                                class="seat-available w-9 h-9 flex justify-center items-center bg-[#dff0fb] rounded-xl border border-[#c5e4f5] text-slate-600 shrink-0 hover:bg-[#bfdffa] hover:border-blue-300 hover:shadow-sm transition-all duration-150 cursor-pointer">
                                                                                <i data-tabler="armchair"
                                                                                    data-size="18"
                                                                                    data-stroke="1.8"></i>
                                                                            </button>
                                                                        @else
                                                                            <div wire:key="seat-{{ $activeMapIndex }}-{{ $designator }}-{{ $activePassengerKey }}"
                                                                                title="{{ $designator }} – Unavailable"
                                                                                class="w-9 h-9 flex justify-center items-center bg-slate-100 rounded-xl border border-slate-200 text-slate-300 shrink-0">
                                                                                <i data-tabler="x" data-size="16"
                                                                                    data-stroke="2"></i>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if (!$loop->last)
                                                        <div class="my-6 border-t border-dashed border-slate-200">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="w-full xl:w-[220px] flex flex-col gap-4 shrink-0">
                                                <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
                                                    <span
                                                        class="font-semibold text-base text-slate-950 block mb-4">Seat
                                                        option</span>
                                                    <div class="flex flex-col gap-3.5">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-9 h-9 flex justify-center items-center bg-slate-100 rounded-xl border border-slate-200 text-slate-300 shrink-0">
                                                                <i data-tabler="x" data-size="16"
                                                                    data-stroke="2"></i>
                                                            </div>
                                                            <span class="text-sm text-slate-700">Unavailable
                                                                seat</span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-9 h-9 flex justify-center items-center bg-[#dff0fb] rounded-xl border border-[#c5e4f5] text-slate-600 shrink-0">
                                                                <i data-tabler="armchair" data-size="18"
                                                                    data-stroke="1.8"></i>
                                                            </div>
                                                            <span class="text-sm text-slate-700">Available seat</span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-9 h-9 flex justify-center items-center bg-[#f3b515] rounded-xl border-2 border-[#e0a512] text-white shrink-0">
                                                                <i data-tabler="armchair" data-size="18"
                                                                    data-stroke="1.8"></i>
                                                            </div>
                                                            <span class="text-sm text-slate-700">Selected seat</span>
                                                        </div>
                                                        @if (count($passengerMeta) > 1)
                                                            <div class="flex items-center gap-3">
                                                                <div
                                                                    class="w-9 h-9 flex justify-center items-center bg-purple-100 rounded-xl border border-purple-200 text-purple-500 shrink-0">
                                                                    <i data-tabler="user" data-size="16"
                                                                        data-stroke="1.8"></i>
                                                                </div>
                                                                <span class="text-sm text-slate-700">Other
                                                                    passenger</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
                                                    <span
                                                        class="font-semibold text-base text-slate-950 block mb-4">Selections</span>
                                                    <div class="flex flex-col gap-3">
                                                        @foreach ($passengerMeta as $paxKey => $meta)
                                                            @php $pSeat = $selectedSeats[$activeMapIndex][$paxKey] ?? null; @endphp
                                                            <div wire:key="summary-{{ $paxKey }}"
                                                                class="flex items-center justify-between gap-2">
                                                                <div class="flex items-center gap-2 min-w-0">
                                                                    <i data-tabler="user"
                                                                        class="text-slate-400 shrink-0"
                                                                        data-size="14"></i>
                                                                    <span
                                                                        class="text-sm text-slate-700 truncate">{{ $meta['name'] }}</span>
                                                                </div>
                                                                @if ($pSeat)
                                                                    <div class="flex items-center gap-1 shrink-0">
                                                                        <span
                                                                            class="px-2 py-0.5 rounded-lg bg-[#f3b515] text-white text-xs font-bold">
                                                                            {{ $pSeat['designator'] }}
                                                                        </span>
                                                                        @if (($pSeat['amount'] ?? 0) > 0)
                                                                            <span
                                                                                class="text-xs text-slate-500 whitespace-nowrap">{{ number_format($pSeat['amount'], 0) }}</span>
                                                                        @else
                                                                            <span
                                                                                class="text-xs text-green-600 font-semibold">Free</span>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span
                                                                        class="text-xs text-slate-400 italic shrink-0">—</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="flex justify-between items-center mt-2">
                            <button onclick="history.back()"
                                class="btn btn-white min-w-[100px] sm:min-w-[140px]">Back</button>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <button wire:click="skipSeats"
                                    class="text-blue-600 font-bold hover:underline px-2 sm:px-6">
                                    <span wire:loading.remove wire:target="skipSeats">Skip</span>
                                    <span wire:loading wire:target="skipSeats"
                                        class="loading loading-spinner loading-xs"></span>
                                </button>
                                <button wire:click="continue" class="btn btn-primary min-w-[100px] sm:min-w-[140px]">
                                    <span wire:loading.remove wire:target="continue">Continue</span>
                                    <span wire:loading wire:target="continue"
                                        class="loading loading-spinner loading-xs"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-[304px] shrink-0 sticky top-24">
                        <div class="flex flex-col md:gap-7 gap-2">
                            <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>
                            @php
                                $sf = $selectedFlight;
                                $seg0 = $sf['slices'][0]['segments'][0] ?? [];
                                $dep = $seg0['departing_at'] ?? null;
                                $arr = $seg0['arriving_at'] ?? null;
                                $o = $seg0['origin']['iata_code'] ?? '';
                                $d = $seg0['destination']['iata_code'] ?? '';
                                $logo = $seg0['operating_carrier']['logo_symbol_url'] ?? '';
                                $airl = $seg0['operating_carrier']['name'] ?? '';
                                $fno = $seg0['operating_carrier']['iata_code'] ?? '';
                                $fnum = $seg0['operating_carrier_flight_number'] ?? '';
                                $dur = $seg0['duration'] ?? '';
                                $stps = count($sf['slices'][0]['segments'] ?? []) - 1;
                            @endphp
                            @if ($logo || $airl)
                                <div class="card p-4 flex items-center gap-3">
                                    @if ($logo)
                                        <div
                                            class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100 shrink-0">
                                            <img src="{{ $logo }}" alt="{{ $airl }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span
                                            class="font-semibold text-base text-slate-950 truncate">{{ $airl }}</span>
                                        <span
                                            class="font-normal text-sm text-slate-500">{{ $fno }}{{ $fnum }}</span>
                                    </div>
                                </div>
                                <div class="card p-4 flex flex-row items-center justify-between gap-4">
                                    <div class="flex flex-col items-start">
                                        <span
                                            class="font-semibold text-sm text-slate-950">{{ $dep ? \Carbon\Carbon::parse($dep)->format('h:i A') : '' }}</span>
                                        <span class="font-normal text-xs text-slate-500">{{ $o }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5 flex-1">
                                        <span
                                            class="font-normal text-xs text-slate-400">{{ $dur ? \Carbon\CarbonInterval::make($dur)->cascade()->forHumans() : '' }}</span>
                                        <div class="w-full h-px bg-slate-200 relative">
                                            <div
                                                class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                            </div>
                                            <div
                                                class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                            </div>
                                        </div>
                                        <span
                                            class="font-normal text-xs text-slate-400">{{ $stps === 0 ? 'Non-stop' : $stps . ' stop' . ($stps > 1 ? 's' : '') }}</span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span
                                            class="font-semibold text-sm text-slate-950">{{ $arr ? \Carbon\Carbon::parse($arr)->format('h:i A') : '' }}</span>
                                        <span class="font-normal text-xs text-slate-500">{{ $d }}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="card p-5 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-normal text-sm text-slate-950">Base Fare</span>
                                    <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                        {{ number_format($baseTotal, 2) }}</span>
                                </div>
                                @if ($addonsTotal > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="font-normal text-sm text-slate-950">Add-ons</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($addonsTotal, 2) }}</span>
                                    </div>
                                @endif
                                @if ($seatTotal > 0)
                                    <div wire:key="seat-total-line" class="flex justify-between items-center">
                                        <span class="font-normal text-sm text-slate-950">Seat Selection</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($seatTotal, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="border-slate-100">
                                <div class="flex justify-between items-center pt-2">
                                    <span class="font-semibold text-[20px] text-slate-950">Total</span>
                                    <span wire:key="grand-total"
                                        class="font-bold text-[24px] text-slate-950">{{ $currency }}
                                        {{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
