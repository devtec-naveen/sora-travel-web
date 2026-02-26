<div>
    <form wire:submit.prevent="changePassword" class="mt-3">

        <!-- Old Password -->
        <div class="form-group row">
            <label class="col-md-2 col-form-label">Old Password</label>
            <div class="col-md-5">
                <input type="password" wire:model.lazy="old_password" class="form-control" placeholder="Enter Old Password">
                @error('old_password')
                    <span class="text-danger form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- New Password -->
        <div class="form-group row mt-2">
            <label class="col-md-2 col-form-label">New Password</label>
            <div class="col-md-5">
                <input type="password" wire:model.lazy="password" class="form-control" placeholder="Enter New Password">
                @error('password')
                    <span class="text-danger form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group row mt-2">
            <label class="col-md-2 col-form-label">Confirm Password</label>
            <div class="col-md-5">
                <input type="password" wire:model.lazy="password_confirmation" class="form-control"
                    placeholder="Confirm New Password">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group row mt-3">
            <label class="col-md-2"></label>
            <div class="col-md-5">
                <button type="submit" class="btn-sm btn-primary" wire:loading.attr="disabled">
                    <span wire:loading>Updating...</span>
                    <span wire:loading.remove>Update Password</span>
                </button>
            </div>
        </div>
    </form>
</div>
