<div>
    <main class="bg-slate-50 min-h-[800px]">

        <div class="booking-progress-container py-6">
            <div class="container">
                <div class="flex items-center justify-between max-w-5xl mx-auto">
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100">
                            <i data-tabler="check" data-size="16"></i>
                        </div>
                        <span class="text-xs md:text-sm font-medium text-slate-900">Search</span>
                    </div>
                    <div class="grow h-0.5 bg-blue-600 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100 font-bold text-sm">
                            2</div>
                        <span class="text-xs md:text-sm font-medium text-slate-900 text-center">Passengers</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">
                            3</div>
                        <span class="text-xs md:text-sm font-medium text-slate-400 text-center">Add-ons</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">
                            4</div>
                        <span class="text-xs md:text-sm font-medium text-slate-400 text-center whitespace-nowrap">Select
                            Seat</span>
                    </div>
                    <div class="grow h-0.5 bg-slate-200 rounded-full"></div>
                    <div class="flex flex-col items-center gap-2 group shrink-0">
                        <div
                            class="sm:w-8 sm:h-8 w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-slate-500 shrink-0 text-sm">
                            5</div>
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
                            <h1 class="font-semibold text-[24px] leading-[36px] text-slate-800">Passenger Details</h1>
                            <span class="font-normal text-base text-slate-500">Please provide details for all
                                passengers</span>
                        </div>

                        <div class="flex flex-col gap-6">

                            @foreach ($passengers as $idx => $pax)
                                @php
                                    $dobId = 'pax_dob_' . $idx;
                                    $expId = 'pax_exp_' . $idx;
                                    $typeLabel = match ($pax['type']) {
                                        'adult' => 'Adult',
                                        'child' => 'Child',
                                        'infant' => 'Infant',
                                        default => 'Passenger',
                                    };
                                @endphp

                                <div class="card">
                                    <div class="flex items-center gap-3 p-5 border-b border-slate-100">
                                        <i data-tabler="user" class="text-slate-500" data-size="22"></i>
                                        <span class="font-semibold text-[20px] leading-[32px] text-slate-950">
                                            Passenger {{ $idx + 1 }} ({{ $typeLabel }})
                                        </span>
                                    </div>
                                    <div class="p-5 lg:p-6 flex flex-col gap-6">

                                        <div class="flex flex-col md:flex-row gap-6">
                                            <div class="w-full md:w-[158px] flex flex-col gap-1.5">
                                                <span class="form-label">Title *</span>
                                                <div class="relative group">
                                                    <select class="form-input appearance-none pr-10"
                                                        wire:model="passengers.{{ $idx }}.title">
                                                        <option>Mr</option>
                                                        <option>Ms</option>
                                                        <option>Mrs</option>
                                                        <option>Master</option>
                                                    </select>
                                                    <div
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <i data-tabler="chevron-down" data-size="16"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-1 flex flex-col gap-1.5">
                                                <span class="form-label">First Name * <span
                                                        class="text-slate-400 font-normal">(as per
                                                        passport/ID)</span></span>
                                                <input type="text" placeholder="Enter name"
                                                    class="form-input @error('passengers.' . $idx . '.first_name') border-red-400 @enderror"
                                                    wire:model="passengers.{{ $idx }}.first_name" />
                                                @error('passengers.' . $idx . '.first_name')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-control">
                                            <span class="form-label">Last Name * <span
                                                    class="text-slate-400 font-normal">(as per
                                                    passport/ID)</span></span>
                                            <input type="text" placeholder="Enter name"
                                                class="form-input @error('passengers.' . $idx . '.last_name') border-red-400 @enderror"
                                                wire:model="passengers.{{ $idx }}.last_name" />
                                            @error('passengers.' . $idx . '.last_name')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-control">
                                                <span class="form-label">Gender *</span>
                                                <div class="relative group">
                                                    <select
                                                        class="form-input appearance-none pr-10 @error('passengers.' . $idx . '.gender') border-red-400 @enderror"
                                                        wire:model="passengers.{{ $idx }}.gender">
                                                        <option value="" disabled>Choose</option>
                                                        <option value="m">Male</option>
                                                        <option value="f">Female</option>
                                                        <option value="o">Other</option>
                                                    </select>
                                                    <div
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <i data-tabler="chevron-down" data-size="16"></i>
                                                    </div>
                                                </div>
                                                @error('passengers.' . $idx . '.gender')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-control">
                                                <span class="form-label">Date of Birth *</span>
                                                <div class="dtp-field relative" data-dtp-id="{{ $dobId }}"
                                                    data-mode="date" data-max-date="today" data-min-year="1970" data-max-year="{{ date('Y') }}">
                                                    <div
                                                        class="form-input flex items-center justify-between cursor-pointer select-none gap-2">
                                                        <span id="dtp_lbl_{{ $dobId }}"
                                                            style="color:{{ !empty($pax['dob']) ? '#1e293b' : '#94a3b8' }};font-weight:{{ !empty($pax['dob']) ? '500' : '400' }}">
                                                            {{ !empty($pax['dob']) ? \Carbon\Carbon::parse($pax['dob'])->format('d M Y') : 'Select date' }}
                                                        </span>
                                                        <img src="{{ asset('assets/images/calendar.svg') }}"
                                                            alt="icon" class="w-5 h-5 flex-shrink-0 opacity-50" />
                                                    </div>
                                                    <div id="dtp_dd_{{ $dobId }}" class="dtp-drop"
                                                        style="display:none" onclick="event.stopPropagation()">
                                                        <div id="dtp_body_{{ $dobId }}"></div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="dtp_val_{{ $dobId }}"
                                                    wire:model="passengers.{{ $idx }}.dob"
                                                    value="{{ $pax['dob'] ?? '' }}" />
                                                @error('passengers.' . $idx . '.dob')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if ($pax['type'] !== 'infant')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="form-control">
                                                    <span class="form-label">Passport Number</span>
                                                    <input type="text" placeholder="Enter Passport Number"
                                                        class="form-input"
                                                        wire:model="passengers.{{ $idx }}.passport_no" />
                                                </div>
                                                <div class="form-control">
                                                    <span class="form-label">Passport Expiry Date</span>
                                                    <div class="dtp-field relative" data-dtp-id="{{ $expId }}"
                                                        data-mode="date" data-min-date="today">
                                                        <div
                                                            class="form-input flex items-center justify-between cursor-pointer select-none gap-2">
                                                            <span id="dtp_lbl_{{ $expId }}"
                                                                style="color:{{ !empty($pax['passport_expiry']) ? '#1e293b' : '#94a3b8' }};font-weight:{{ !empty($pax['passport_expiry']) ? '500' : '400' }}">
                                                                {{ !empty($pax['passport_expiry']) ? \Carbon\Carbon::parse($pax['passport_expiry'])->format('d M Y') : 'Select date' }}
                                                            </span>
                                                            <img src="{{ asset('assets/images/calendar.svg') }}"
                                                                alt="icon"
                                                                class="w-5 h-5 flex-shrink-0 opacity-50" />
                                                        </div>
                                                        <div id="dtp_dd_{{ $expId }}" class="dtp-drop"
                                                            style="display:none" onclick="event.stopPropagation()">
                                                            <div id="dtp_body_{{ $expId }}"></div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="dtp_val_{{ $expId }}"
                                                        wire:model="passengers.{{ $idx }}.passport_expiry"
                                                        value="{{ $pax['passport_expiry'] ?? '' }}" />
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach

                            <div class="card">
                                <div class="flex items-center gap-3 p-5 border-b border-slate-100">
                                    <i data-tabler="mail" class="text-slate-500" data-size="22"></i>
                                    <span class="font-semibold text-[20px] leading-[32px] text-slate-950">Contact
                                        Information</span>
                                </div>
                                <div class="p-5 lg:p-6 flex flex-col gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                        <div class="form-control">
                                            <span class="form-label">Email Address *</span>
                                            <input type="email" placeholder="Enter email address"
                                                class="form-input @error('email') border-red-400 @enderror"
                                                wire:model="email" />
                                            @error('email')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-control">
                                            <span class="form-label">Phone Number *</span>
                                            <div class="flex gap-0">
                                                <div class="w-24 shrink-0 relative group">
                                                    <select
                                                        class="form-input appearance-none pr-10 rounded-r-none border-r-0"
                                                        wire:model="phoneCode">
                                                        <option value="+91">+91</option>
                                                        <option value="+1">+1</option>
                                                        <option value="+44">+44</option>
                                                        <option value="+971">+971</option>
                                                        <option value="+32">+32</option>
                                                        <option value="+33">+33</option>
                                                        <option value="+49">+49</option>
                                                        <option value="+65">+65</option>
                                                        <option value="+66">+66</option>
                                                    </select>
                                                    <div
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <i data-tabler="chevron-down" data-size="14"></i>
                                                    </div>
                                                </div>
                                                <input type="tel" placeholder="Phone number"
                                                    class="form-input flex-1 rounded-l-none @error('phone') border-red-400 @enderror"
                                                    wire:model="phone" />
                                            </div>
                                            @error('phone')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-col items-center gap-2.5 self-stretch mt-2">
                            <div class="flex justify-between items-center self-stretch">
                                <button onclick="history.back()" class="btn btn-white min-w-[140px]">Back</button>
                                <button wire:click="continue" class="btn btn-primary min-w-[140px]">
                                    <span wire:loading.remove wire:target="continue">Continue</span>
                                    <span wire:loading wire:target="continue"
                                        class="loading loading-spinner loading-xs"></span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="w-full lg:w-[304px] shrink-0 sticky top-24">
                        <div class="flex flex-col md:gap-7 gap-2">

                            <div class="flex flex-col gap-2.5">
                                <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>
                            </div>

                            @if (!empty($segment))
                                @php
                                    $dep = $segment['departing_at'] ?? null;
                                    $arr = $segment['arriving_at'] ?? null;
                                    $orig = $segment['origin']['iata_code'] ?? '';
                                    $dest = $segment['destination']['iata_code'] ?? '';
                                    $logo = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                    $airl = $segment['operating_carrier']['name'] ?? '';
                                    $fno = $segment['operating_carrier']['iata_code'] ?? '';
                                    $fnum = $segment['operating_carrier_flight_number'] ?? '';
                                    $dur = $segment['duration'] ?? '';
                                    $stps = count($slice['segments'] ?? []) - 1;
                                @endphp
                                <div class="card p-4 flex items-center gap-3">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-slate-50 overflow-hidden border border-slate-100 shrink-0">
                                        <img src="{{ $logo }}" alt="{{ $airl }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span
                                            class="font-semibold text-base text-slate-950 truncate">{{ $airl }}</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $fno }}
                                            {{ $fnum }}</span>
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
                                            <div
                                                class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                            </div>
                                            <div
                                                class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                            </div>
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
                                @if ($this->adults > 0)
                                    <div class="flex justify-between items-center self-stretch">
                                        <span class="font-normal text-sm text-slate-950">Base Fare
                                            ({{ $this->adults }} Adult{{ $this->adults > 1 ? 's' : '' }})</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($baseFare * $this->adults, 2) }}</span>
                                    </div>
                                @endif
                                @if ($this->children > 0)
                                    <div class="flex justify-between items-center self-stretch">
                                        <span class="font-normal text-sm text-slate-950">Base Fare
                                            ({{ $this->children }} Child{{ $this->children > 1 ? 'ren' : '' }})</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($baseFare * $this->children, 2) }}</span>
                                    </div>
                                @endif
                                @if ($this->infants > 0)
                                    <div class="flex justify-between items-center self-stretch">
                                        <span class="font-normal text-sm text-slate-950">Base Fare
                                            ({{ $this->infants }} Infant{{ $this->infants > 1 ? 's' : '' }})</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($baseFare * $this->infants, 2) }}</span>
                                    </div>
                                @endif
                                @if ($taxes > 0)
                                    <div class="flex justify-between items-center self-stretch">
                                        <span class="font-normal text-sm text-slate-950">Taxes & Fees</span>
                                        <span class="font-normal text-sm text-slate-500">{{ $currency }}
                                            {{ number_format($taxes, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="border-slate-100">
                                <div class="flex justify-between items-center self-stretch pt-2">
                                    <span class="font-semibold text-lg text-slate-950">Total</span>
                                    <span class="font-bold text-xl text-slate-950">{{ $currency }}
                                        {{ number_format((float) $price, 2) }}</span>
                                </div>
                            </div>

                            <div class="card p-4 space-y-3">
                                <h4 class="font-semibold text-sm text-slate-950">Travellers</h4>
                                @if ($this->adults > 0)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <i data-tabler="user" data-size="15" class="text-slate-400"></i>
                                        {{ $this->adults }} Adult{{ $this->adults > 1 ? 's' : '' }}
                                    </div>
                                @endif
                                @if ($this->children > 0)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <i data-tabler="user" data-size="15" class="text-slate-400"></i>
                                        {{ $this->children }} Child{{ $this->children > 1 ? 'ren' : '' }}
                                    </div>
                                @endif
                                @if ($this->infants > 0)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <i data-tabler="baby-carriage" data-size="15" class="text-slate-400"></i>
                                        {{ $this->infants }} Infant{{ $this->infants > 1 ? 's' : '' }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
