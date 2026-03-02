<div>
    <h6 class="main-content-label mb-3">Edit Popular Destination</h6>
    <form wire:submit.prevent="updateDestination" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Title <span class="requirestar">*</span></label>
            <input type="text" wire:model="title" class="form-control" placeholder="Enter Destination Title">
            @error('title')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Destination Image</label>
            <x-backend.image-upload previewId="popularDestinationPreview" :currentImage="$oldImage"
                folderPath="popular_destination" />
            @error('image')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">

            <span wire:loading.remove>Update</span>
            <span wire:loading>Updating...</span>
        </button>
        <button type="button" wire:click="cancelEdit" class="btn btn-secondary">
            Cancel
        </button>
    </form>
</div>
