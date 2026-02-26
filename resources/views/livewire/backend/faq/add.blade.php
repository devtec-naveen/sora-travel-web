<div>
    <h6 class="main-content-label mb-3">Add FAQ(s)</h6>
    <form wire:submit.prevent="saveFaqs" method="POST">
        @csrf
        <div class="row row-sm">
            @foreach($faqs as $index => $faq)
            <div class="col-md-12 mb-3 border rounded p-3">
                <div class="form-group">
                    <label>Question <span class="requirestar">*</span></label>
                    <input type="text" wire:model="faqs.{{$index}}.question" class="form-control"
                        placeholder="Enter Question">
                    @error("faqs.$index.question")
                        <span class="text-danger form-error">Question is required</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Answer <span class="requirestar">*</span></label>
                    <textarea wire:model="faqs.{{$index}}.answer" class="form-control" rows="3"
                        placeholder="Enter Answer"></textarea>
                    @error("faqs.$index.answer")
                        <span class="text-danger form-error">Answer is required</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model="faqs.{{$index}}.status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                @if(count($faqs) > 1)
                <button type="button" wire:click="removeFaq({{$index}})" class="btn btn-danger btn-sm mt-2">
                    Remove
                </button>
                @endif
            </div>
            @endforeach
            <div class="col-md-12 mb-3">
                <button type="button" wire:click="addFaq" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add More
                </button>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" wire:click="resetForm" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</div>