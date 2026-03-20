<div class="w-[100%]">
    <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-center text-slate-800">Welcome Back</h1>
    <p class="font-normal text-sm sm:text-base text-center text-slate-500">Log in to your account to continue</p>

    <div class="w-full space-y-4 mt-7">

        <div class="form-control w-full">
            <label class="form-label">Email</label>
            <input type="email"
                wire:model="email"
                placeholder="Enter email address"
                class="form-input @error('email') border-red-400 @enderror"
                wire:keydown.enter="login" />
            @error('email')
                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-control w-full">
            <label class="form-label">Password</label>
            <input type="password"
                wire:model="password"
                placeholder="Enter your password"
                class="form-input @error('password') border-red-400 @enderror"
                wire:keydown.enter="login" />
            @error('password')
                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="w-full flex justify-center mt-2">
            <a href="javascript:void(0)"
                onclick="login_modal.close(); forgot_password_modal.showModal()"
                class="font-semibold text-base text-center text-blue-600">
                Forgot password?
            </a>
        </div>

        <button
            wire:click="login"
            wire:loading.attr="disabled"
            wire:target="login"
            class="btn btn-primary w-full mt-2 disabled:opacity-60">
            <span wire:loading.remove wire:target="login">Continue</span>
            <span wire:loading wire:target="login" class="loading loading-spinner loading-xs"></span>
        </button>

        <p class="mt-5 text-center font-normal text-base text-[#4a5565]">
            Don't have an account?
            <a href="javascript:void(0)"
                onclick="login_modal.close(); signup_modal.showModal()"
                class="font-semibold text-base text-blue-600">Sign Up</a>
        </p>

    </div>
</div>