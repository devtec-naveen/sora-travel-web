<div>
    <div class="flex-1 min-w-0 flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Saved Cards</h1>
            <button type="button" class="btn btn-secondary btn-sm" wire:click="openModal('add_card_modal')">
                Add New Card
            </button>
        </div>
        <div class="card p-4 md:p-6 flex flex-col gap-4">
            @if (count($cards) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-slate-400 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg>
                    <p class="text-base font-medium">No saved cards yet.</p>
                    <p class="text-sm">Click "Add New Card" to save your first card.</p>
                </div>
            @else
                <div class="flex flex-col gap-[30px]">
                    @foreach ($cards as $card)
                        @php
                            $brand = strtolower($card['brand'] ?? 'card');
                            $lastFour = $card['last_four'] ?? '****';
                            $expMonth = str_pad($card['exp_month'] ?? '', 2, '0', STR_PAD_LEFT);
                            $expYear = substr($card['exp_year'] ?? '', -2);
                            $isDefault = (bool) ($card['is_default'] ?? false);
                            $pmId = $card['stripe_payment_method_id'] ?? '';
                            $brandColors = [
                                'visa' => 'bg-blue-600',
                                'mastercard' => 'bg-red-500',
                                'amex' => 'bg-sky-500',
                                'discover' => 'bg-orange-500',
                            ];
                            $brandColor = $brandColors[$brand] ?? 'bg-secondary-400';
                        @endphp
                        <div
                            class="p-5 rounded-2xl flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 {{ $isDefault ? 'bg-primary-50 ring-1 ring-primary-200' : 'bg-slate-50 border border-slate-200 ring-1 ring-slate-100' }}">
                            <div class="flex-1 flex flex-col gap-3 min-w-0">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-9 {{ $brandColor }} rounded flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="1" y="4" width="22" height="16" rx="2"
                                                ry="2" />
                                            <line x1="1" y1="10" x2="23" y2="10" />
                                        </svg>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-slate-700 text-sm font-semibold uppercase tracking-wide">
                                            {{ ucfirst($brand) }}
                                        </span>
                                        @if ($isDefault)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div class="text-slate-950 text-xl font-semibold tracking-widest">
                                        •••• •••• •••• {{ $lastFour }}
                                    </div>
                                    <div class="flex flex-wrap gap-6 sm:gap-11">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-slate-500 text-sm">Expiry</span>
                                            <span class="text-slate-950 text-base font-medium">
                                                {{ $expMonth }}/{{ $expYear }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row sm:flex-col gap-2 shrink-0">
                                @if (!$isDefault)
                                    <button type="button" class="btn btn-secondary btn-sm whitespace-nowrap"
                                        wire:click="setDefault('{{ $pmId }}')" wire:loading.attr="disabled">
                                        Set Default
                                    </button>
                                @endif
                                <button type="button" class="btn btn-red btn-sm whitespace-nowrap"
                                    wire:click="deleteCard('{{ $pmId }}')" wire:loading.attr="disabled"
                                    onclick="return confirm('Remove this card?') || event.stopImmediatePropagation()">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <x-frontend.modal id="add_card_modal" :header="true" headerText="Add New Card">
        <div class="p-6 space-y-5">
            <form onsubmit="return false;" class="flex flex-col gap-5">
                <div wire:ignore>
                    <label class="form-label mb-1 block text-sm font-medium text-slate-700">
                        Card Details
                    </label>
                    <div id="card-element"
                        style="padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; min-height: 44px;">
                    </div>
                    <p id="card-errors" class="text-red-500 text-sm mt-1.5 min-h-[20px]"></p>
                </div>
                <button type="button" id="saveCardBtn" class="btn btn-primary w-full">
                    Save Card
                </button>
            </form>
        </div>
    </x-frontend.modal>
</div>
@push('scripts')
    @once
        <script src="https://js.stripe.com/v3/"></script>
    @endonce
    <script>
        window.stripePublishableKey = "{{ config('services.stripe.key') }}";
    </script>
    @vite(['resources/js/stripe.js'])
@endpush
