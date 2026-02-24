<div>
    <div class="row row-sm">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card main-content-body-profile">
                <div class="tab-content">
                    <div class="main-content-body border-top-0">
                        <form wire:submit="updateProfile">
                            {{-- ================= IMAGE SECTION (COMMENTED) ================= --}}
                            {{--
                            <div class="form-group">
                                <div class="row row-sm">
                                    <div class="col-md-2">
                                        <label class="form-label">Profile</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" wire:model.live="profile_image">
                                        @error('profile_image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            --}}
                            {{-- ================= NAME ================= --}}
                            <div class="form-group">
                                <div class="row row-sm">
                                    <div class="col-md-2">
                                        <label class="form-label">Name</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" placeholder="Enter Name"
                                            wire:model="name">
                                        @error('name')
                                            <div class="text-danger form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ================= MOBILE ================= --}}
                            <div class="form-group">
                                <div class="row row-sm">
                                    <div class="col-md-2">
                                        <label class="form-label">Mobile</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" placeholder="Enter Phone Number"
                                            wire:model="phone_number">
                                        @error('phone_number')
                                            <div class="text-danger form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- ================= EMAIL (READONLY) ================= --}}
                            <div class="form-group">
                                <div class="row row-sm">
                                    <div class="col-md-2">
                                        <label class="form-label">Email</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                                    </div>
                                </div>
                            </div>
                            {{-- ================= SUBMIT ================= --}}
                            <div class="form-actions row mt-3">
                                <div class="col-md-8 offset-md-2">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            Submit
                                        </span>
                                        <span wire:loading>
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Updating profile...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
