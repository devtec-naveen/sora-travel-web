<div wire:init="loadData">
    <x-loader
        message="Please Wait..."
        targets="loadData"
    />
    <div x-data="{ isLoggedIn: {{ Auth::check() ? 'true' : 'false' }} }" x-on:require-login.window="login_modal.showModal()">
        <main class="bg-slate-50 min-h-[800px]">
            {{-- Booking Progress --}}
            <div class="booking-progress-container py-4 md:py-6">
                <div class="container">
                    <div class="flex items-center justify-between max-w-5xl mx-auto px-2">
                        @foreach (['Search', 'Passengers', 'Add-ons', 'Seat', 'Payment'] as $si => $step)
                            <div class="flex flex-col items-center gap-1.5 shrink-0">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-700 flex items-center justify-center text-white shrink-0 shadow-sm shadow-blue-100 text-xs sm:text-sm font-bold">
                                    @if ($si < 4)
                                        <i data-tabler="check" data-size="14"></i>
                                    @else
                                        5
                                    @endif
                                </div>
                                <span
                                    class="hidden sm:block text-xs md:text-sm font-medium text-slate-900 text-center whitespace-nowrap">{{ $step }}</span>
                                <span
                                    class="block sm:hidden text-[10px] font-medium text-slate-900 text-center leading-tight w-10 truncate">{{ $step }}</span>
                            </div>
                            @if (!$loop->last)
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
    
                        {{-- LEFT --}}
                        <div class="flex-1 flex flex-col gap-5 min-w-0">
    
                            <div class="flex flex-col gap-1.5">
                                <h1 class="font-semibold text-xl md:text-[24px] leading-tight text-slate-800">Complete
                                    Payment</h1>
                                <span class="font-normal text-sm md:text-base text-slate-500">Choose your preferred payment
                                    method</span>
                            </div>
    
                            {{-- Guest Login Notice --}}
                            @guest
                                <div class="card p-4 flex items-start gap-3 border border-blue-100 bg-blue-50">
                                    <i data-tabler="info-circle" class="text-blue-500 shrink-0 mt-0.5" data-size="18"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm text-blue-700">Login required to complete payment</p>
                                        <p class="text-xs text-blue-600 mt-0.5">Please login or create an account to proceed.
                                        </p>
                                    </div>
                                    <button onclick="login_modal.showModal()"
                                        class="shrink-0 text-sm font-semibold text-blue-700 hover:underline whitespace-nowrap">
                                        Login →
                                    </button>
                                </div>
                            @endguest
    
                            {{-- Error Alert --}}
                            @if ($paymentError)
                                <div class="card p-4 flex items-start gap-3 border border-red-100 bg-red-50">
                                    <i data-tabler="alert-triangle" class="text-red-500 shrink-0 mt-0.5" data-size="18"></i>
                                    <div>
                                        <p class="font-semibold text-sm text-red-700">Payment Failed</p>
                                        <p class="text-sm text-red-500 mt-0.5">{{ $errorMessage }}</p>
                                    </div>
                                </div>
                            @endif
    
                            {{-- Payment Method --}}
                            <div class="card overflow-hidden">
                                <div class="px-4 py-4 md:p-5 border-b border-slate-100">
                                    <h2 class="font-semibold text-base md:text-lg text-slate-950">Select Payment Method</h2>
                                </div>
                                <div class="p-4 md:p-5 flex flex-col gap-3">
    
                                    {{-- Card --}}
                                    {{-- <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="card"
                                            wire:model.live.debounce.500ms.live="paymentMethod" class="sr-only" />
                                        <div class="flex items-center justify-between p-3.5 md:p-4 rounded-xl border bg-white transition-all
                                            group-has-[:checked]:border-blue-500 group-has-[:checked]:bg-blue-50 border-slate-200">
                                            <div class="flex items-center gap-3 md:gap-4 min-w-0">
                                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100 shrink-0">
                                                    <i data-tabler="credit-card" class="text-slate-600" data-size="20"></i>
                                                </div>
                                                <div class="flex flex-col gap-0.5 min-w-0">
                                                    <span class="font-semibold text-sm md:text-base text-slate-900">Credit / Debit Card</span>
                                                    <span class="font-normal text-xs md:text-sm text-slate-500">Visa, Mastercard, Amex</span>
                                                </div>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border shrink-0 flex items-center justify-center ml-3 transition-all
                                                group-has-[:checked]:bg-[#f3b515] group-has-[:checked]:border-[#f3b515] border-slate-300">
                                                <i data-tabler="check" class="hidden group-has-[:checked]:block text-white" data-size="12" data-stroke="3"></i>
                                            </div>
                                        </div>
                                    </label> --}}
    
                                    {{-- Stripe --}}
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="stripe"
                                            wire:model.live.debounce.500ms.live="paymentMethod" class="sr-only" />
                                        <div
                                            class="flex items-center justify-between p-3.5 md:p-4 rounded-xl border bg-white transition-all
                                            group-has-[:checked]:border-blue-500 group-has-[:checked]:bg-blue-50 border-slate-200">
                                            <div class="flex items-center gap-3 md:gap-4 min-w-0">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-[#635bff] flex items-center justify-center shrink-0">
                                                    <span class="text-white font-bold text-base">S</span>
                                                </div>
                                                <div class="flex flex-col gap-0.5 min-w-0">
                                                    <span
                                                        class="font-semibold text-sm md:text-base text-slate-900">Stripe</span>
                                                    <span class="font-normal text-xs md:text-sm text-slate-500">Secure
                                                        payment via Stripe</span>
                                                </div>
                                            </div>
                                            <div
                                                class="w-5 h-5 rounded-full border shrink-0 flex items-center justify-center ml-3 transition-all
                                                group-has-[:checked]:bg-[#f3b515] group-has-[:checked]:border-[#f3b515] border-slate-300">
                                                <i data-tabler="check" class="hidden group-has-[:checked]:block text-white"
                                                    data-size="12" data-stroke="3"></i>
                                            </div>
                                        </div>
                                    </label>
    
                                </div>
                            </div>
    
                            {{-- Card Details --}}
                            {{-- @if ($paymentMethod === 'card')
                                <div class="card overflow-hidden">
                                    <div class="px-4 py-4 md:p-5 border-b border-slate-100">
                                        <h2 class="font-semibold text-base md:text-lg text-slate-950">Card Details</h2>
                                    </div>
                                    <div class="p-4 md:p-5 lg:p-6 flex flex-col gap-5">
    
                                        <div class="form-control">
                                            <label class="form-label">Card Number *</label>
                                            <div class="relative">
                                                <input type="text"
                                                    wire:model.live.debounce.500ms="cardNumber"
                                                    class="form-input pr-12 @error('cardNumber') border-red-400 @enderror"
                                                    placeholder="1234 5678 9012 3456"
                                                    maxlength="19"
                                                    x-on:input="$el.value = $el.value.replace(/[^0-9]/g,'').replace(/(.{4})/g,'$1 ').trim()" />
                                                <i data-tabler="credit-card" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none" data-size="18"></i>
                                            </div>
                                            @error('cardNumber')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
    
                                        <div class="form-control">
                                            <label class="form-label">Cardholder Name *</label>
                                            <input type="text"
                                                wire:model.live.debounce.500ms="cardHolder"
                                                class="form-input @error('cardHolder') border-red-400 @enderror"
                                                placeholder="Name as on card" />
                                            @error('cardHolder')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
    
                                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                                            <div class="form-control">
                                                <label class="form-label">Expiry Date *</label>
                                                <input type="text"
                                                    wire:model.live.debounce.500ms="cardExpiry"
                                                    class="form-input @error('cardExpiry') border-red-400 @enderror"
                                                    placeholder="MM/YY"
                                                    maxlength="5"
                                                    x-on:input="
                                                        let v = $el.value.replace(/\D/g,'');
                                                        if(v.length >= 2) v = v.slice(0,2) + '/' + v.slice(2);
                                                        $el.value = v.slice(0,5);
                                                    " />
                                                @error('cardExpiry')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-control">
                                                <label class="form-label">CVV *</label>
                                                <div class="relative">
                                                    <input type="password"
                                                        wire:model.live.debounce.500ms="cardCvv"
                                                        class="form-input pr-10 @error('cardCvv') border-red-400 @enderror"
                                                        placeholder="•••"
                                                        maxlength="4" />
                                                    <i data-tabler="lock" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none" data-size="15"></i>
                                                </div>
                                                @error('cardCvv')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
    
                                        <div class="flex items-center gap-2 bg-slate-50 rounded-xl p-3">
                                            <i data-tabler="shield-check" class="text-green-500 shrink-0" data-size="16"></i>
                                            <span class="text-xs text-slate-500">Your payment information is encrypted and secure.</span>
                                        </div>
    
                                    </div>
                                </div>
                            @endif --}}
    
                            {{-- Stripe info --}}
                            @if ($paymentMethod === 'stripe')
                                <div class="card overflow-hidden">
                                    <div class="px-4 py-4 md:p-5 border-b border-slate-100">
                                        <h2 class="font-semibold text-base md:text-lg text-slate-950">Card Details</h2>
                                    </div>
                                    <div class="p-4 md:p-5 lg:p-6 flex flex-col gap-5">
    
                                        <div class="form-control">
                                            <label class="form-label">Card Number *</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live.debounce.500ms="cardNumber"
                                                    class="form-input pr-12 @error('cardNumber') border-red-400 @enderror"
                                                    placeholder="1234 5678 9012 3456" maxlength="19"
                                                    x-on:input="$el.value = $el.value.replace(/[^0-9]/g,'').replace(/(.{4})/g,'$1 ').trim()" />
                                                <i data-tabler="credit-card"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"
                                                    data-size="18"></i>
                                            </div>
                                            @error('cardNumber')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
    
                                        <div class="form-control">
                                            <label class="form-label">Cardholder Name *</label>
                                            <input type="text" wire:model.live.debounce.500ms="cardHolder"
                                                class="form-input @error('cardHolder') border-red-400 @enderror"
                                                placeholder="Name as on card" />
                                            @error('cardHolder')
                                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
    
                                        {{-- Expiry + CVV: stack on xs, 2-col on sm+ --}}
                                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                                            <div class="form-control">
                                                <label class="form-label">Expiry Date *</label>
                                                <input type="text" wire:model.live.debounce.500ms="cardExpiry"
                                                    class="form-input @error('cardExpiry') border-red-400 @enderror"
                                                    placeholder="MM/YY" maxlength="5"
                                                    x-on:keydown="
                                                        if ($event.key === 'Backspace' && $el.value.endsWith('/')) {
                                                            $event.preventDefault();
                                                            $el.value = $el.value.slice(0, -1);
                                                            $el.dispatchEvent(new Event('input'));
                                                        }
                                                    "
                                                    x-on:input="
                                                        let v = $el.value.replace(/\D/g, '');
                                                        if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2);
                                                        $el.value = v.slice(0, 5);
                                                    " />
                                                @error('cardExpiry')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-control">
                                                <label class="form-label">CVV *</label>
                                                <div class="relative">
                                                    <input type="password" wire:model.live.debounce.500ms="cardCvv"
                                                        class="form-input pr-10 @error('cardCvv') border-red-400 @enderror"
                                                        placeholder="•••" maxlength="4"  autocomplete="cc-csc" name="cc-csc"/>
                                                    <i data-tabler="lock"
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"
                                                        data-size="15"></i>
                                                </div>
                                                @error('cardCvv')
                                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
    
                                        <div class="flex items-center gap-2 bg-slate-50 rounded-xl p-3">
                                            <i data-tabler="shield-check" class="text-green-500 shrink-0"
                                                data-size="16"></i>
                                            <span class="text-xs text-slate-500">Your payment information is encrypted and
                                                secure.</span>
                                        </div>
    
                                    </div>
                                </div>
                            @endif
    
                            {{-- Mobile Price Summary --}}
                            <div class="block lg:hidden card p-4 space-y-3">
                                <h3 class="font-semibold text-base text-slate-800">Price Summary</h3>
                                @php
                                    $paxTypesMob = [];
                                    foreach ($passengers as $p) {
                                        $t = $p['type'] ?? 'adult';
                                        $paxTypesMob[$t] = ($paxTypesMob[$t] ?? 0) + 1;
                                    }
                                    $totalPaxMob = max(1, $adults + $children);
                                    $perPaxMob = round($baseTotal / $totalPaxMob, 2);
                                @endphp
                                @if (($paxTypesMob['adult'] ?? 0) > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Base Fare ({{ $paxTypesMob['adult'] }}
                                            {{ $paxTypesMob['adult'] > 1 ? 'Adults' : 'Adult' }})</span>
                                        <span class="text-slate-700 font-medium">{{ $currency }}
                                            {{ number_format($perPaxMob * $paxTypesMob['adult'], 2) }}</span>
                                    </div>
                                @endif
                                @if (($paxTypesMob['child'] ?? 0) > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Base Fare ({{ $paxTypesMob['child'] }}
                                            {{ $paxTypesMob['child'] > 1 ? 'Children' : 'Child' }})</span>
                                        <span class="text-slate-700 font-medium">{{ $currency }}
                                            {{ number_format($perPaxMob * $paxTypesMob['child'], 2) }}</span>
                                    </div>
                                @endif
                                @if ($addonsTotal > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Add-ons</span>
                                        <span class="text-slate-700 font-medium">{{ $currency }}
                                            {{ number_format($addonsTotal, 2) }}</span>
                                    </div>
                                @endif
                                @if ($seatTotal > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Seat Selection</span>
                                        <span class="text-slate-700 font-medium">{{ $currency }}
                                            {{ number_format($seatTotal, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                    <span class="font-semibold text-base text-slate-950">Total</span>
                                    <span class="font-bold text-lg text-slate-950">{{ $currency }}
                                        {{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
    
                            {{-- Actions --}}
                            <div class="flex justify-between items-center gap-4 py-2">
                                <button onclick="history.back()"
                                    class="btn btn-white min-w-[110px] sm:min-w-[130px]">Back</button>
    
                                @guest
                                    <button onclick="login_modal.showModal()"
                                        class="btn btn-primary flex-1 sm:flex-none sm:min-w-[160px]">
                                        <i data-tabler="lock" data-size="15"></i>
                                        Login to Pay
                                    </button>
                                @else
                                    <button wire:click="pay" wire:loading.attr="disabled" wire:target="pay"
                                        class="btn btn-primary flex-1 sm:flex-none sm:min-w-[180px] disabled:opacity-60">
                                        <span wire:loading.remove wire:target="pay"
                                            class="flex items-center justify-center gap-2">
                                            <i data-tabler="lock" data-size="15"></i>
                                            <span class="whitespace-nowrap">Pay {{ $currency }}
                                                {{ number_format($grandTotal, 2) }}</span>
                                        </span>
                                        <span wire:loading wire:target="pay" class="flex items-center justify-center gap-2">
                                            <span class="loading loading-spinner loading-xs"></span>
                                            Processing...
                                        </span>
                                    </button>
                                @endauth
                            </div>
    
                        </div>
    
                        {{-- RIGHT: Sidebar (desktop only) --}}
                        <div class="hidden lg:block w-[304px] shrink-0 sticky top-24">
                            <div class="flex flex-col gap-5">
                                <h3 class="font-semibold text-[24px] leading-[36px] text-slate-800">Price details</h3>
    
                                @php
                                    $dep = $segment['departing_at'] ?? null;
                                    $arr = $segment['arriving_at'] ?? null;
                                    $orig = $segment['origin']['iata_code'] ?? '';
                                    $dest = $segment['destination']['iata_code'] ?? '';
                                    $logo = $segment['operating_carrier']['logo_symbol_url'] ?? '';
                                    $airl = $segment['operating_carrier']['name'] ?? '';
                                    $fno =
                                        ($segment['operating_carrier']['iata_code'] ?? '') .
                                        ($segment['operating_carrier_flight_number'] ?? '');
                                    $dur = $segment['duration'] ?? '';
                                    $stps = count($slice['segments'] ?? []) - 1;
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
                                            <span class="text-sm text-slate-500">{{ $fno }}</span>
                                        </div>
                                    </div>
                                    <div class="card p-4 flex items-center justify-between gap-3">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-semibold text-sm text-slate-950">{{ $dep ? \Carbon\Carbon::parse($dep)->format('H:i') : '' }}</span>
                                            <span class="text-xs text-slate-500">{{ $orig }}</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-0.5 flex-1">
                                            <span
                                                class="text-xs text-slate-400">{{ $dur? \Carbon\CarbonInterval::make($dur)->cascade()->forHumans(['parts' => 2]): '' }}</span>
                                            <div class="w-full h-px bg-slate-200 relative">
                                                <div
                                                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                                </div>
                                                <div
                                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-slate-300">
                                                </div>
                                            </div>
                                            <span
                                                class="text-xs text-slate-400">{{ $stps === 0 ? 'Non-stop' : $stps . ' stop' . ($stps > 1 ? 's' : '') }}</span>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="font-semibold text-sm text-slate-950">{{ $arr ? \Carbon\Carbon::parse($arr)->format('H:i') : '' }}</span>
                                            <span class="text-xs text-slate-500">{{ $dest }}</span>
                                        </div>
                                    </div>
                                @endif
    
                                <div class="card p-5 space-y-3.5">
                                    @php
                                        $paxTypes = [];
                                        foreach ($passengers as $p) {
                                            $t = $p['type'] ?? 'adult';
                                            $paxTypes[$t] = ($paxTypes[$t] ?? 0) + 1;
                                        }
                                        $totalPax = max(1, $adults + $children);
                                        $perPax = round($baseTotal / $totalPax, 2);
                                    @endphp
                                    @if (($paxTypes['adult'] ?? 0) > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-700">Base Fare ({{ $paxTypes['adult'] }}
                                                {{ $paxTypes['adult'] > 1 ? 'Adults' : 'Adult' }})</span>
                                            <span class="text-sm text-slate-500">{{ $currency }}
                                                {{ number_format($perPax * $paxTypes['adult'], 2) }}</span>
                                        </div>
                                    @endif
                                    @if (($paxTypes['child'] ?? 0) > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-700">Base Fare ({{ $paxTypes['child'] }}
                                                {{ $paxTypes['child'] > 1 ? 'Children' : 'Child' }})</span>
                                            <span class="text-sm text-slate-500">{{ $currency }}
                                                {{ number_format($perPax * $paxTypes['child'], 2) }}</span>
                                        </div>
                                    @endif
                                    @if ($addonsTotal > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-700">Add-ons</span>
                                            <span class="text-sm text-slate-500">{{ $currency }}
                                                {{ number_format($addonsTotal, 2) }}</span>
                                        </div>
                                    @endif
                                    @if ($seatTotal > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-700">Seat Selection</span>
                                            <span class="text-sm text-slate-500">{{ $currency }}
                                                {{ number_format($seatTotal, 2) }}</span>
                                        </div>
                                    @endif
                                    <hr class="border-slate-100">
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="font-semibold text-lg text-slate-950">Total</span>
                                        <span class="font-bold text-xl text-slate-950">{{ $currency }}
                                            {{ number_format($grandTotal, 2) }}</span>
                                    </div>
                                </div>
    
                                <div class="flex items-center gap-2 px-1">
                                    <i data-tabler="shield-check" class="text-green-600 shrink-0" data-size="18"></i>
                                    <span class="text-xs text-slate-500">256-bit SSL encrypted — your data is safe</span>
                                </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
