<div>
    <h6 class="main-content-label mb-3">Edit FAQ</h6>
    <form wire:submit.prevent="updateFaq" method="POST">
        @csrf
        <div class="row row-sm">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label>FAQ Category <span class="requirestar">*</span></label>
                    <select wire:model="faq.faq_category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach ($faqCategoryList as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error("faq.faq_category_id")
                        <span class="text-danger form-error">Category is required</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Question <span class="requirestar">*</span></label>
                    <input type="text" wire:model="faq.question" class="form-control" placeholder="Enter Question">
                    @error("faq.question")
                        <span class="text-danger form-error">Question is required</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Answer <span class="requirestar">*</span></label>
                    <textarea wire:model="faq.answer" class="form-control" rows="3" placeholder="Enter Answer"></textarea>
                    @error("faq.answer")
                        <span class="text-danger form-error">Answer is required</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model="faq.status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" wire:click="cancelEdit" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</div>