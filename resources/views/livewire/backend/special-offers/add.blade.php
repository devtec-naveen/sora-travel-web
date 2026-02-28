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
            <label>Start Date<span class="requirestar">*</span></label>
            <input type="text" wire:model="start_date_time" id="start_date" class="form-control" readonly>
            @error('start_date')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>End Date<span class="requirestar">*</span></label>
            <input type="text" wire:model="end_date_time" id="end_date" class="form-control" readonly>
            @error('end_date')
                <span class="text-danger form-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Offer Image <span class="requirestar">*</span></label>
            <x-backend.image-upload previewId="specialOfferPreview" />
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
@push('scripts')
    <script>
        function initDatePickers() {
            let startPicker = flatpickr("#start_date", {
                enableTime: false,
                dateFormat: "Y-m-d",
                defaultDate: @this.start_date ?? null,
                onChange: function(selectedDates, dateStr) {
                    @this.set('start_date', dateStr);

                    if (selectedDates.length > 0) {
                        let nextDay = new Date(selectedDates[0]);
                        nextDay.setDate(nextDay.getDate() + 1);
                        endPicker.set('minDate', nextDay);
                    }
                }
            });
            let endPicker = flatpickr("#end_date", {
                enableTime: false,
                dateFormat: "Y-m-d",
                defaultDate: @this.end_date ?? null,
                minDate: new Date().fp_incr(1),
                onChange: function(selectedDates, dateStr) {
                    @this.set('end_date', dateStr);
                }
            });
        }
        document.addEventListener('livewire:init', initDatePickers);
        document.addEventListener('livewire:navigated', initDatePickers);
    </script>
@endpush
