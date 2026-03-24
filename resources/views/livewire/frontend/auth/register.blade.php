<div x-data="{
    countdown: 0,
    timer: null,
    startCountdown() {
        this.countdown = 60;
        clearInterval(this.timer);
        this.timer = setInterval(() => {
            if (--this.countdown <= 0) {
                clearInterval(this.timer);
                $wire.canResend = true;
            }
        }, 1000);
    }
}"
x-on:otp-sent.window="startCountdown()">

    <div x-show="$wire.step === 'signup'" x-transition.opacity.duration.300ms>
        <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-center text-slate-800">
            Create an Account
        </h1>
        <p class="font-normal text-sm sm:text-base text-center text-slate-500">
            Sign up to start booking your travel
        </p>

        <div class="w-full space-y-4 mt-7">

            <div class="form-control w-full">
                <label class="form-label">Full Name</label>
                <input type="text" wire:model="name" placeholder="John Doe"
                    class="form-input @error('name') border-red-400 @enderror" />
                @error('name')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control w-full">
                <label class="form-label">Email</label>
                <input type="email" wire:model="email" placeholder="Enter email address"
                    class="form-input @error('email') border-red-400 @enderror" />
                @error('email')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control w-full">
                <label class="form-label">Password</label>
                <input type="password" wire:model="password" placeholder="Create a password (min 8 chars)"
                    class="form-input @error('password') border-red-400 @enderror" />
                @error('password')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control w-full">
                <label class="form-label">Confirm Password</label>
                <input type="password" wire:model="password_confirmation" placeholder="Confirm your password"
                    class="form-input" />
            </div>

            <div class="flex items-start gap-2">
                <input type="checkbox" wire:model="terms" class="checkbox checkbox-sm checkbox-primary mt-0.5" />
                <span class="text-xs sm:text-sm text-slate-500">
                    I agree to the
                    <a href="#" class="text-blue-600 font-medium">Terms & Conditions</a>
                    and
                    <a href="#" class="text-blue-600 font-medium">Privacy Policy</a>
                </span>
            </div>
            @error('terms')
                <span class="text-xs text-red-500 block">{{ $message }}</span>
            @enderror

            <button wire:click="register" wire:loading.attr="disabled" wire:target="register"
                class="btn btn-primary w-full mt-2 disabled:opacity-60">
                <span wire:loading.remove wire:target="register">Sign Up</span>
                <span wire:loading wire:target="register" class="loading loading-spinner loading-xs"></span>
            </button>

            <p class="mt-5 font-normal text-base text-[#4a5565]">
                Already have an account?
                <a href="javascript:void(0)" onclick="signup_modal.close(); login_modal.showModal()"
                    class="font-semibold text-base text-blue-600">Log In</a>
            </p>

        </div>
    </div>

    <div x-show="$wire.step === 'otp'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-slate-800">
                Verify Your Email
            </h1>
            <p class="font-normal text-sm sm:text-base text-slate-500 mt-1">
                We've sent a 6-digit OTP to
                <span class="font-medium text-slate-700">{{ $email }}</span>
            </p>
        </div>

        <div class="w-full space-y-5">

            <div class="flex justify-center gap-2 sm:gap-3">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="text"
                        maxlength="1"
                        inputmode="numeric"
                        pattern="[0-9]"
                        wire:model="otp.{{ $i }}"
                        id="otp_{{ $i }}"
                        class="w-11 h-12 text-center text-lg font-semibold border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('otp') border-red-400 @enderror"
                        oninput="this.value=this.value.replace(/[^0-9]/g,''); if(this.value.length===1){ let next=document.getElementById('otp_{{ $i + 1 }}'); if(next) next.focus(); }"
                        onkeydown="if(event.key==='Backspace' && !this.value){ let prev=document.getElementById('otp_{{ $i - 1 }}'); if(prev) prev.focus(); }"
                    />
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
                <button wire:click="backToSignup" class="text-blue-600 font-medium hover:underline">
                    ← Back to Sign Up
                </button>
            </p>
        </div>
    </div>
</div>