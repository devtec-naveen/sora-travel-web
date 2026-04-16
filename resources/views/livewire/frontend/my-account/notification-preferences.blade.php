<div class="flex-1 min-w-0 flex flex-col gap-6">
    <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Notification Preferences</h1>

    <div class="card p-4 md:p-6 flex flex-col gap-6">
        <div class="flex flex-col gap-4">

            {{-- Booking Updates --}}
            <label class="flex cursor-pointer justify-between items-center gap-5 p-3 bg-slate-50 rounded-xl hover:bg-slate-100/80 transition-colors">
                <div class="flex flex-col gap-0.5">
                    <span class="text-slate-950 text-base font-semibold">Booking updates</span>
                    <span class="text-slate-500 text-sm">Get notified about your booking status changes</span>
                </div>
                <div class="relative inline-block h-5 w-9 shrink-0 rounded-xl focus-within:ring-2 focus-within:ring-secondary-400 focus-within:ring-offset-2 focus-within:outline-none">
                    <input type="checkbox" wire:model.live="booking_updates"
                        class="peer sr-only focus:outline-none focus:ring-0 focus:ring-offset-0">
                    <span class="pointer-events-none absolute inset-0 block rounded-xl bg-slate-200 transition-colors duration-200 peer-checked:bg-secondary-400"
                        aria-hidden="true"></span>
                    <span class="pointer-events-none absolute start-0.5 top-0.5 block size-4 rounded-full bg-slate-50 shadow-sm transition-all duration-200 peer-checked:translate-x-4 peer-checked:bg-white"
                        aria-hidden="true"></span>
                </div>
            </label>

            {{-- Promotions --}}
            <label class="flex cursor-pointer justify-between items-center gap-5 p-3 bg-slate-50 rounded-xl hover:bg-slate-100/80 transition-colors">
                <div class="flex flex-col gap-0.5">
                    <span class="text-slate-950 text-base font-semibold">Promotions</span>
                    <span class="text-slate-500 text-sm">Receive deals, offers and travel discounts</span>
                </div>
                <div class="relative inline-block h-5 w-9 shrink-0 rounded-xl focus-within:ring-2 focus-within:ring-secondary-400 focus-within:ring-offset-2 focus-within:outline-none">
                    <input type="checkbox" wire:model.live="promotions"
                        class="peer sr-only focus:outline-none focus:ring-0 focus:ring-offset-0">
                    <span class="pointer-events-none absolute inset-0 block rounded-xl bg-slate-200 transition-colors duration-200 peer-checked:bg-secondary-400"
                        aria-hidden="true"></span>
                    <span class="pointer-events-none absolute start-0.5 top-0.5 block size-4 rounded-full bg-slate-50 shadow-sm transition-all duration-200 peer-checked:translate-x-4 peer-checked:bg-white"
                        aria-hidden="true"></span>
                </div>
            </label>

            {{-- Payment Alerts --}}
            <label class="flex cursor-pointer justify-between items-center gap-5 p-3 bg-slate-50 rounded-xl hover:bg-slate-100/80 transition-colors">
                <div class="flex flex-col gap-0.5">
                    <span class="text-slate-950 text-base font-semibold">Payment alerts</span>
                    <span class="text-slate-500 text-sm">Get alerts for payments, refunds and transactions</span>
                </div>
                <div class="relative inline-block h-5 w-9 shrink-0 rounded-xl focus-within:ring-2 focus-within:ring-secondary-400 focus-within:ring-offset-2 focus-within:outline-none">
                    <input type="checkbox" wire:model.live="payment_alerts"
                        class="peer sr-only focus:outline-none focus:ring-0 focus:ring-offset-0">
                    <span class="pointer-events-none absolute inset-0 block rounded-xl bg-slate-200 transition-colors duration-200 peer-checked:bg-secondary-400"
                        aria-hidden="true"></span>
                    <span class="pointer-events-none absolute start-0.5 top-0.5 block size-4 rounded-full bg-slate-50 shadow-sm transition-all duration-200 peer-checked:translate-x-4 peer-checked:bg-white"
                        aria-hidden="true"></span>
                </div>
            </label>

        </div>

    </div>
</div>
