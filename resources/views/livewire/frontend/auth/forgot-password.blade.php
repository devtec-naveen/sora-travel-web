<div class="w-[100%]">
    <form wire:submit.prevent="sendResetLink">
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
            <span wire:loading.remove wire:target="sendResetLink">
                Send Reset Link
            </span>
            <span wire:loading wire:target="sendResetLink">
                Sending...
            </span>
        </button>
    </form>
    <p class="mt-5 font-normal text-base text-[#4a5565]">
        Remember your password?
        <a href="javascript:void(0)" wire:click="$dispatch('open-login')" class="font-semibold text-base text-blue-600">
            Sign in
        </a>
    </p>
</div>
