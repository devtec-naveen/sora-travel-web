<div>
    <h6 class="main-content-label mb-3">Add Special Offer</h6>
    <form wire:submit.prevent="saveOffer" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Title <span class="requirestar">*</span></label>
            <input type="text" wire:model="title" class="form-control" placeholder="Enter Offer Title">
            @error('title')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Start Date & Time <span class="requirestar">*</span></label>
            <input type="datetime-local" wire:model="start_date_time" class="form-control">
            @error('start_date_time')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>End Date & Time <span class="requirestar">*</span></label>
            <input type="datetime-local" wire:model="end_date_time" class="form-control">
            @error('end_date_time')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label>Offer Image <span class="requirestar">*</span></label>
            <x-backend.image-upload previewId="specialOfferPreview"/>
            @error('image')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
            <span wire:loading.remove>Submit</span>
            <span wire:loading>Saving...</span>
        </button>
        <button type="button" wire:click="resetForm" class="btn btn-secondary">
            Cancel
        </button>
    </form>
</div>
