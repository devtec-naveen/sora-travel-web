<div class="w-[100%]">
    {{-- ───────────── STEP 1 : EMAIL ───────────── --}}
    @if ($step === 'email')
        <form wire:submit.prevent="sendOtp">
            <div class="w-full space-y-4 mt-7">
                <div class="form-control w-full">
                    <label class="form-label">Email</label>
                    <input type="email" placeholder="Enter email address" class="form-input" wire:model.defer="email" />
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-6" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="sendOtp">Send OTP</span>
                <span wire:loading wire:target="sendOtp">Sending...</span>
            </button>
        </form>

        <p class="mt-5 font-normal text-base text-[#4a5565]">
            Remember your password?
            <a href="javascript:void(0)" wire:click="$dispatch('open-login')"
                class="font-semibold text-base text-blue-600">Sign in</a>
        </p>
    @endif
    {{-- ───────────── STEP 2 : OTP VERIFY ───────────── --}}
    @if ($step === 'otp')
        <div class="w-full space-y-4 mt-7">
            <p class="text-sm text-[#4a5565]">
                We sent a 6-digit OTP to <span class="font-semibold text-gray-800">{{ $email }}</span>
            </p>

            <div class="w-full space-y-5">
                <div class="flex justify-center gap-2 sm:gap-3">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                            wire:model="otp.{{ $i }}" id="fp_otp_{{ $i }}"
                            class="w-11 h-12 text-center text-lg font-semibold border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('otp') border-red-400 @enderror"
                            oninput="
                            this.value = this.value.replace(/[^0-9]/g, '').slice(0,1);
                            if (this.value.length === 1) {
                                var next = document.getElementById('fp_otp_{{ $i + 1 }}');
                                if (next) { next.focus(); next.select(); }
                            }
                        "
                            onkeydown="
                            if (event.key === 'Backspace') {
                                if (this.value) {
                                    this.value = '';
                                    @this.set('otp.{{ $i }}', '');
                                } else {
                                    var prev = document.getElementById('fp_otp_{{ $i - 1 }}');
                                    if (prev) { prev.focus(); prev.select(); }
                                }
                                event.preventDefault();
                            }
                        "
                            onpaste="
                            event.preventDefault();
                            var pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                            pasted.split('').forEach(function(char, idx) {
                                var box = document.getElementById('fp_otp_' + idx);
                                if (box) {
                                    box.value = char;
                                    @this.set('otp.' + idx, char);
                                }
                            });
                            var lastBox = document.getElementById('fp_otp_' + (pasted.length < 6 ? pasted.length : 5));
                            if (lastBox) lastBox.focus();
                        " />
                    @endfor
                </div>

                @error('otp')
                    <span class="text-xs text-red-500 text-center block">{{ $message }}</span>
                @enderror

                <button wire:click="verifyOtp" wire:loading.attr="disabled" wire:target="verifyOtp"
                    class="btn btn-primary w-full disabled:opacity-60">
                    <span wire:loading.remove wire:target="verifyOtp">Verify OTP</span>
                    <span wire:loading wire:target="verifyOtp" class="loading loading-spinner loading-xs"></span>
                </button>

                <p class="text-center text-sm text-slate-500">
                    Didn't receive the code?
                    <template x-if="countdown > 0">
                        <span class="text-slate-400">Resend in <span x-text="countdown"></span>s</span>
                    </template>
                    <template x-if="countdown <= 0">
                        <button wire:click="resendOtp" wire:loading.attr="disabled" wire:target="resendOtp"
                            class="text-blue-600 font-medium hover:underline">
                            <span wire:loading.remove wire:target="resendOtp">Resend OTP</span>
                            <span wire:loading wire:target="resendOtp">Sending...</span>
                        </button>
                    </template>
                </p>

                <p class="text-center text-sm">
                    <button wire:click="$set('step', 'email')" class="text-blue-600 font-medium hover:underline">
                        ← Back to Email
                    </button>
                </p>
            </div>
        </div>
    @endif
    {{-- ───────────── STEP 3 : NEW PASSWORD ───────────── --}}
    @if ($step === 'password')
        <form wire:submit.prevent="resetPassword">
            <div class="w-full space-y-4 mt-7">
                <div class="form-control w-full">
                    <label class="form-label">New Password</label>
                    <input type="password" placeholder="Enter new password" class="form-input"
                        wire:model.defer="password" />
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control w-full">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" placeholder="Confirm new password" class="form-input"
                        wire:model.defer="password_confirmation" />
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-6" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                <span wire:loading wire:target="resetPassword">Resetting...</span>
            </button>
        </form>
    @endif

</div>
