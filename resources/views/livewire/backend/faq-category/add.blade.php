<div>
    <h6 class="main-content-label mb-3">Add FAQ Category</h6>
    <form wire:submit.prevent="saveCategory">
        <div class="form-group mb-3">
            <label>Category Name <span class="requirestar">*</span></label>
            <input type="text" wire:model="name" class="form-control" placeholder="Enter Category Name">
            @error('name')
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
