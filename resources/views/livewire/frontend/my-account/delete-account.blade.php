<div>
    <div class="flex-1 min-w-0 flex flex-col gap-6">
        <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Delete Account</h1>
        <div class="card p-6 md:p-8 flex flex-col gap-6">
            <div class="flex items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="#ef4444" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
            </div>
            <div class="text-center flex flex-col gap-2">
                <h2 class="text-slate-950 text-xl font-semibold">Are you sure you want to delete your account?</h2>
                <p class="text-slate-500 text-sm">This action is permanent and cannot be undone.</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 flex flex-col gap-3">
                <p class="text-red-700 text-sm font-semibold">The following data will be permanently deleted:</p>
                <ul class="flex flex-col gap-2">
                    @foreach ([['icon' => 'user', 'text' => 'Your personal profile and account information'], ['icon' => 'credit-card', 'text' => 'All saved payment cards (removed from Stripe too)'], ['icon' => 'map-pin', 'text' => 'All saved addresses'], ['icon' => 'plane', 'text' => 'All booking and transaction history'], ['icon' => 'bell', 'text' => 'Your notification preferences'], ['icon' => 'lock', 'text' => 'All active sessions and access tokens']] as $item)
                        <li class="flex items-center gap-2.5 text-sm text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" y1="9" x2="9" y2="15" />
                                <line x1="9" y1="9" x2="15" y2="15" />
                            </svg>
                            {{ $item['text'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-end">
                <button type="button" class="btn btn-red" wire:click="openModal">
                    Delete My Account
                </button>
            </div>
        </div>
    </div>
    <x-frontend.modal id="delete_account_modal" :header="true" headerText="Delete Account">
        <div class="p-6 flex flex-col gap-5">
            @if ($step === 'confirm')
                <div class="flex flex-col items-center gap-3 text-center">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6M14 11v6" />
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                        </svg>
                    </div>
                    <p class="text-slate-700 text-base font-medium">
                        You are about to permanently delete your account.
                    </p>
                    <p class="text-slate-500 text-sm">
                        All your data including bookings, saved cards, and addresses will be deleted forever.
                        This cannot be undone.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button" class="btn btn-secondary flex-1" wire:click="closeModal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-red flex-1" wire:click="proceedToPassword">
                        Delete It
                    </button>
                </div>
            @elseif ($step === 'password')
                <div class="flex flex-col gap-1 text-center">
                    <p class="text-slate-700 text-base font-medium">Enter your password to confirm</p>
                    <p class="text-slate-500 text-sm">This is the final step. Your account will be deleted immediately.
                    </p>
                </div>
                <div class="form-control">
                    <label class="form-label">Password</label>
                    <input type="password" wire:model="password"
                        class="form-input @error('password') border-red-400 @enderror" placeholder="Enter your password"
                        wire:keydown.enter="confirmDelete" autofocus />
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" class="btn btn-secondary flex-1" wire:click="closeModal"
                        wire:loading.attr="disabled">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-red flex-1" wire:click="confirmDelete"
                        wire:loading.attr="disabled" wire:target="confirmDelete">
                        <span wire:loading.remove wire:target="confirmDelete">Confirm Delete</span>
                        <span wire:loading wire:target="confirmDelete">Deleting…</span>
                    </button>
                </div>
            @endif
        </div>
    </x-frontend.modal>
</div>
