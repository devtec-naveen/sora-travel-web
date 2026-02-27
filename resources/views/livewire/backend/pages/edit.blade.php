<div>
    <h6 class="main-content-label mb-3">Edit Page</h6>
    <form wire:submit.prevent="updatePage">
        <div class="form-group mb-3">
            <label>Page Title <span class="requirestar">*</span></label>
            <input type="text" wire:model="page_title" class="form-control">
            @error('page_title')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Meta Title</label>
            <input type="text" wire:model="meta_title" class="form-control">
            @error('meta_title')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Meta Keywords</label>
            <input type="text" wire:model="meta_keywords" class="form-control">
            @error('meta_keywords')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Content</label>
            <div wire:ignore>
                <x-text-editore id="pages" model="content" :value="$content" />
            </div>
            @error('content')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Status <span class="requirestar">*</span></label>
            <select wire:model="status" class="form-control">
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status')
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
