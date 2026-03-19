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

                    <div class="flex-1 flex flex-col gap-4 md:gap-9">
                        <div class="flex flex-col items-center lg:items-start gap-2.5 px-4 lg:px-0">
                            <h1 class="font-semibold text-[24px] leading-[36px] text-slate-800">Enhance Your Journey</h1>
                            <span class="font-normal text-base text-slate-500">Select optional add-ons for a better travel experience</span>
                        </div>

                        <div class="flex flex-col gap-6">

                            {{-- Extra Baggage --}}
                            <div class="card overflow-hidden">
                                <div class="flex flex-col justify-center gap-1.5 p-5 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <i data-tabler="luggage" class="text-slate-950" data-size="24"></i>
                                        <span class="font-semibold text-[20px] leading-[32px] text-slate-950">Extra Baggage</span>
                                    </div>
                                    <span class="font-normal text-sm text-slate-500">Standard baggage: 1 x 7kg cabin bag included. Add extra baggage below.</span>
                                </div>
                                <div class="p-5 lg:p-6 flex flex-col gap-8">
                                    @foreach ($passengers as $idx => $pax)
                                        @if ($pax['type'] !== 'infant')
                                            @php
                                                $typeLabel = match($pax['type']) {
                                                    'adult' => 'Adult',
                                                    'child' => 'Child',
                                                    default => 'Passenger',
                                                };
                                            @endphp
                                            <div class="flex flex-col gap-5">
                                                <span class="font-semibold text-lg text-slate-900">
                                                    Passenger {{ $idx + 1 }} ({{ $typeLabel }})
                                                </span>
                                                <div class="flex flex-col gap-3.5">
                                                    @foreach ($baggageOptions as $opt)
                                                        <label class="relative cursor-pointer group">
                                                            <input type="radio"
                                                                name="baggage_{{ $idx }}"
                                                                value="{{ $opt['value'] }}"
                                                                wire:model.live="baggage.{{ $idx }}"
                                                                class="peer sr-only"
                                                                @if(($baggage[$idx] ?? '0kg') === $opt['value']) checked @endif />
                                                            <div class="flex justify-between items-center p-4 rounded-2xl border border-slate-200 group-has-[:checked]:border group-has-[:checked]:border-blue-600 transition-all">
                                                                <div class="flex items-center gap-3.5">
                                                                    <div class="w-5 h-5 rounded-full border border-slate-300 group-has-[:checked]:border-transparent group-has-[:checked]:bg-[#f3b515] flex items-center justify-center text-white transition-colors">
                                                                        <i data-tabler="check" class="hidden group-has-[:checked]:block" data-size="16" data-stroke="2"></i>
                                                                    </div>
                                                                    <span class="font-medium text-base text-slate-950">{{ $opt['label'] }}</span>
                                                                </div>
                                                                <span class="font-bold text-base text-slate-950">
                                                                    {{ $opt['price'] > 0 ? $currency . ' ' . $opt['price'] : 'FREE' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- In-Flight Meals --}}
                            <div class="card overflow-hidden">
                                <div class="flex flex-col justify-center gap-1.5 p-5 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <i data-tabler="tools-kitchen-2" class="text-slate-950" data-size="24"></i>
                                        <span class="font-semibold text-[20px] leading-[32px] text-slate-950">In-Flight Meals</span>
                                    </div>
                                    <span class="font-normal text-sm text-slate-500">Choose from our wide variety of delicious meals served on board.</span>
                                </div>
                                <div class="p-5 lg:p-6 flex flex-col gap-8">
                                    @foreach ($passengers as $idx => $pax)
                                        @if ($pax['type'] !== 'infant')
                                            @php
                                                $typeLabel = match($pax['type']) {
                                                    'adult' => 'Adult',
                                                    'child' => 'Child',
                                                    default => 'Passenger',
                                                };
                                            @endphp
                                            <div class="flex flex-col gap-5">
                                                <span class="font-semibold text-lg text-slate-900">
                                                    Passenger {{ $idx + 1 }} ({{ $typeLabel }})
                                                </span>
                                                <div class="flex flex-col gap-3.5">
                                                    @foreach ($mealOptions as $opt)
                                                        <label class="relative cursor-pointer group">
                                                            <input type="radio"
                                                                name="meals_{{ $idx }}"
                                                                value="{{ $opt['value'] }}"
                                                                wire:model.live="meals.{{ $idx }}"
                                                                class="peer sr-only"
                                                                @if(($meals[$idx] ?? 'none') === $opt['value']) checked @endif />
                                                            <div class="flex justify-between items-center p-4 rounded-2xl border border-slate-200 group-has-[:checked]:border group-has-[:checked]:border-blue-600 transition-all">
                                                                <div class="flex items-center gap-3.5">
                                                                    <div class="w-5 h-5 rounded-full border border-slate-300 group-has-[:checked]:border-transparent group-has-[:checked]:bg-[#f3b515] flex items-center justify-center text-white transition-colors">
                                                                        <i data-tabler="check" class="hidden group-has-[:checked]:block" data-size="16" data-stroke="2"></i>
                                                                    </div>
                                                                    <span class="font-medium text-base text-slate-950">{{ $opt['label'] }}</span>
                                                                </div>
                                                                <span class="font-bold text-base text-slate-950 uppercase">
                                                                    {{ $opt['price'] > 0 ? $currency . ' ' . $opt['price'] : 'FREE' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-col items-center gap-2.5 self-stretch mt-2">
                            <div class="flex justify-between items-center self-stretch">
                                <button onclick="history.back()" class="btn btn-white min-w-[140px]">Back</button>
                                <button wire:click="continue" class="btn btn-primary min-w-[140px]">
                                    <span wire:loading.remove wire:target="continue">Continue</span>
                                    <span wire:loading wire:target="continue" class="loading loading-spinner loading-xs"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Right Sidebar --}}
                    <div class="w-full lg:w-[304px] shrink-0 sticky top-24">
                        <div class="flex flex-col md:gap-7 gap-2">
                            <div class="flex flex-col gap-2.5">
                                <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>
                            </div>

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

                            <div class="card p-5 space-y-4">
                                <div class="flex justify-between items-center self-stretch">
                                    <span class="font-normal text-sm text-slate-950">Base Fare</span>
                                    <span class="font-normal text-sm text-slate-500">{{ $currency }} {{ number_format($baseTotal, 2) }}</span>
                                </div>
                                @if ($addonsTotal > 0)
                                    <div class="flex justify-between items-center self-stretch">
                                        <span class="font-normal text-sm text-slate-950">Add-ons</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }} {{ number_format($addonsTotal, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="border-slate-100">
                                <div class="flex justify-between items-center self-stretch pt-2">
                                    <span class="font-semibold text-lg text-slate-950">Total</span>
                                    <span class="font-bold text-xl text-slate-950">{{ $currency }} {{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>

                            {{-- Selected addons summary --}}
                            @php $hasAddons = collect($baggage)->contains(fn($v) => $v !== '0kg') || collect($meals)->contains(fn($v) => $v !== 'none'); @endphp
                            @if ($hasAddons)
                                <div class="card p-4 space-y-3">
                                    <h4 class="font-semibold text-sm text-slate-950">Selected Add-ons</h4>
                                    @foreach ($passengers as $idx => $pax)
                                        @if ($pax['type'] !== 'infant')
                                            @php
                                                $bag  = collect($baggageOptions)->firstWhere('value', $baggage[$idx]  ?? '0kg');
                                                $meal = collect($mealOptions)->firstWhere('value',    $meals[$idx]    ?? 'none');
                                            @endphp
                                            @if (($bag['price'] ?? 0) > 0 || ($meal['price'] ?? 0) > 0)
                                                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-2">
                                                    Pax {{ $idx + 1 }}
                                                </div>
                                                @if (($bag['price'] ?? 0) > 0)
                                                    <div class="flex justify-between text-sm text-slate-700">
                                                        <span>{{ $bag['label'] }}</span>
                                                        <span>{{ $currency }} {{ $bag['price'] }}</span>
                                                    </div>
                                                @endif
                                                @if (($meal['price'] ?? 0) > 0)
                                                    <div class="flex justify-between text-sm text-slate-700">
                                                        <span>{{ $meal['label'] }}</span>
                                                        <span>{{ $currency }} {{ $meal['price'] }}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
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