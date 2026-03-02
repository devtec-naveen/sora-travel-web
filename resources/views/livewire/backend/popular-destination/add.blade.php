<div>
    <h6 class="main-content-label mb-3">Add Popular Destination</h6>
    <form wire:submit.prevent="saveDestination" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Title <span class="requirestar">*</span></label>
            <input type="text" wire:model="title" class="form-control" placeholder="Enter Destination Title">
            @error('title')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Destination Image <span class="requirestar">*</span></label>
            <x-backend.image-upload previewId="popularDestinationPreview" />
            @error('image')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
            <span wire:loading.remove>Submit</span>
            <span wire:loading>Loading...</span>
        </button>
        <button type="button" wire:click="resetForm" class="btn btn-secondary">
            Cancel
        </button>
    </form>
</div>
