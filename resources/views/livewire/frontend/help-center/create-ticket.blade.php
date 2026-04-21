<div>
    <form wire:submit="submit" class="flex flex-col gap-5 p-5">

        <!-- Subject & Booking ID -->
        <div class="flex flex-col sm:flex-row gap-6">
            <div class="flex-1 form-control">
                <label class="form-label">Subject <span class="text-red-500">*</span></label>
                <input
                    wire:model="subject"
                    type="text"
                    class="form-input @error('subject') border-red-400 @enderror"
                    placeholder="Enter Subject" />
                @error('subject')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex-1 form-control">
                <label class="form-label">Booking ID <span class="text-slate-400 font-normal text-xs">(optional)</span></label>
                <input
                    wire:model="order_id"
                    type="text"
                    class="form-input @error('order_id') border-red-400 @enderror"
                    placeholder="Enter Booking ID" />
                @error('order_id')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="form-control">
            <label class="form-label">Description <span class="text-red-500">*</span></label>
            <textarea
                wire:model="description"
                class="w-full min-h-[145px] p-2.5 bg-white rounded-md border border-slate-200 shadow-sm text-sm font-normal focus:outline-none focus:ring-0 resize-none @error('description') border-red-400 @enderror"
                placeholder="Enter issue in detail"></textarea>
            @error('description')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Attachments -->
        <div class="form-control">
            <label class="form-label">Attachments <span class="text-slate-400 font-normal text-xs">(optional)</span></label>
            <div class="relative">
                <input
                    wire:model="attachment"
                    type="file"
                    class="form-input pr-10 @error('attachment') border-red-400 @enderror"
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i data-tabler="upload" class="w-5 h-5 text-slate-400"></i>
                </div>
            </div>
            @error('attachment')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
            <div wire:loading wire:target="attachment" class="text-xs text-slate-400 mt-1">Uploading...</div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 pt-1 border-t border-slate-100">
            <button
                type="button"
                onclick="document.getElementById('raise_ticket_modal').close()"
                class="btn btn-outline">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="submit">
                <span wire:loading.remove wire:target="submit">Submit</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </div>

    </form>
</div>
