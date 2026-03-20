<div>
    <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-center text-slate-800">Create an Account
    </h1>
    <p class="font-normal text-sm sm:text-base text-center text-slate-500">Sign up to start booking your travel</p>

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
