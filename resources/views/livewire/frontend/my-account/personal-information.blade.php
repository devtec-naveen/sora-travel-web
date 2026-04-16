<div class="flex flex-col gap-6">
    <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Personal Information</h1>
    <div class="card p-4 md:p-6 flex flex-col gap-6">
        <div class="flex flex-col gap-5">
            <h2 class="text-slate-950 text-lg font-semibold">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                {{-- Full Name --}}
                <div class="form-control md:col-span-2 lg:col-span-1">
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name"
                        class="form-input @error('name') border-red-400 @enderror"
                        placeholder="Enter full name" />
                    @error('name')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="form-control md:col-span-2 lg:col-span-1">
                    <label class="form-label">Phone Number <span class="text-red-500">*</span></label>
                    <div class="w-full" wire:ignore>
                        <input type="tel" id="contact-phone-input"
                            placeholder="Phone number"
                            class="form-input intl-phone-input @error('phone') border-red-400 @enderror"
                            data-phone-code="{{ $phoneCode }}"
                            data-phone-number="{{ $phone }}"
                            autocomplete="tel" />
                    </div>
                    <input type="hidden" id="contact-phone-code-hidden"
                        wire:model.live.debounce.400ms="phoneCode" />
                    <input type="hidden" id="contact-phone-hidden"
                        wire:model.live.debounce.400ms="phone" />
                    @error('phone')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                    @error('phoneCode')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-control md:col-span-2 lg:col-span-1">
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="form-input bg-slate-50 cursor-not-allowed"
                        value="{{ $email }}" disabled />
                </div>

                {{-- Passport / ID Number --}}
                <div class="form-control md:col-span-2 lg:col-span-1">
                    <label class="form-label">
                        Passport / ID Number
                        <span class="text-slate-500 font-normal">(optional)</span>
                    </label>
                    <input type="text" wire:model="passport_id"
                        class="form-input @error('passport_id') border-red-400 @enderror"
                        placeholder="e.g. A12345678" />
                    @error('passport_id')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Update Button: @mousedown blurs phone input BEFORE wire:click fires
             so intlTelInput has time to sync the value to hidden fields --}}
        <button wire:click="update" wire:loading.attr="disabled" wire:target="update"
            @mousedown="document.getElementById('contact-phone-input')?.dispatchEvent(new Event('blur'))"
            class="btn btn-primary w-fit btn-sm disabled:opacity-60">
            <span wire:loading.remove wire:target="update">Update</span>
            <span wire:loading wire:target="update" class="loading loading-spinner loading-xs"></span>
        </button>
    </div>
</div>
