<div>
   <div class="flex-1 min-w-0 flex flex-col gap-6">
      <div class="flex items-center justify-between">
         <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Saved Addresses</h1>
         <button type="button" class="btn btn-secondary btn-sm" wire:click="openAddModal('add_address_modal')">
         Add New Address
         </button>
      </div>
      <div class="card p-4 md:p-6 flex flex-col gap-4">
         <div class="flex flex-col gap-[30px]">
            @forelse($addresses as $address)
            <div
               class="p-5 bg-slate-50 rounded-2xl flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
               <div class="flex-1 flex flex-col gap-2 min-w-0">
                  <div class="size-10 rounded flex items-center justify-center bg-secondary-400 text-white"
                     aria-hidden="true">
                     <i data-tabler="map-pin" class="size-6"></i>
                  </div>
                  <div class="flex flex-col gap-1">
                     <div class="text-slate-950 md:text-xl text-md font-semibold">
                        {{ $address['street_address'] }}, {{ $address['city'] }},
                        {{ $address['postal_code'] }}
                     </div>
                     <div class="text-slate-600 md:text-base text-sm font-normal">
                        {{ $address['county'] }}
                     </div>
                  </div>
               </div>
               <div class="flex flex-wrap gap-2 shrink-0">
                  <button type="button" class="btn btn-white w-24 sm:w-auto"
                     wire:click="openEditModal({{ $address['id'] }})">
                  Edit
                  </button>
                  <button type="button" class="btn btn-primary w-24 sm:w-auto"
                     wire:click="confirmDelete({{ $address['id'] }})">
                  Delete
                  </button>
               </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-400">
               <i data-tabler="map-off" class="size-10 mx-auto mb-3"></i>
               <p class="text-sm">No saved addresses yet. Add one to get started.</p>
            </div>
            @endforelse
         </div>
      </div>
   </div>
   <x-frontend.modal id="add_address_modal" :header="true" headerText="Add New Address">
      <div class="p-6 space-y-5">
         <div class="flex flex-col gap-5">
            <div class="form-control">
               <label class="form-label" for="add_street">
               Street Address <span class="text-red-500">*</span>
               </label>
               <input type="text" id="add_street" wire:model="street_address"
                  class="form-input @error('street_address') border-red-500 @enderror"
                  placeholder="123 Avenue Louise" autocomplete="street-address" />
               @error('street_address')
               <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>
            <div class="flex flex-col md:flex-row gap-4">
               <div class="form-control md:flex-1">
                  <label class="form-label" for="add_city">
                  City <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="add_city" wire:model="city"
                     class="form-input @error('city') border-red-500 @enderror" placeholder="Enter city name"
                     autocomplete="address-level2" />
                  @error('city')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                  @enderror
               </div>
               <div class="form-control md:flex-1">
                  <label class="form-label" for="add_postal">
                  Postal Code <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="add_postal" wire:model="postal_code"
                     class="form-input @error('postal_code') border-red-500 @enderror"
                     placeholder="Enter postal code" autocomplete="postal-code" />
                  @error('postal_code')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                  @enderror
               </div>
            </div>
            <div class="form-control">
               <label class="form-label" for="add_county">
               County <span class="text-red-500">*</span>
               </label>
               <input type="text" id="add_county" wire:model="county"
                  class="form-input @error('county') border-red-500 @enderror" placeholder="Enter county"
                  autocomplete="address-level1" />
               @error('county')
               <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>
            <div class="flex gap-3 mt-2">
               <button type="button" class="btn btn-primary w-28" wire:click="saveAddress"
                  wire:loading.attr="disabled" wire:target="saveAddress">
               <span wire:loading.remove wire:target="saveAddress">Submit</span>
               <span wire:loading wire:target="saveAddress">Saving...</span>
               </button>
               <button type="button" class="btn btn-white"
                  wire:click="$dispatch('close-modal', { id: 'add_address_modal' })">
               Cancel
               </button>
            </div>
         </div>
      </div>
   </x-frontend.modal>
   <x-frontend.modal id="edit_address_modal" :header="true" headerText="Edit Address">
      <div class="p-6 space-y-5">
         <div class="flex flex-col gap-5">
            <div class="form-control">
               <label class="form-label" for="edit_street">
               Street Address <span class="text-red-500">*</span>
               </label>
               <input type="text" id="edit_street" wire:model="street_address"
                  class="form-input @error('street_address') border-red-500 @enderror"
                  placeholder="123 Avenue Louise" autocomplete="street-address" />
               @error('street_address')
               <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>
            <div class="flex flex-col md:flex-row gap-4">
               <div class="form-control md:flex-1">
                  <label class="form-label" for="edit_city">
                  City <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="edit_city" wire:model="city"
                     class="form-input @error('city') border-red-500 @enderror" placeholder="Enter city name"
                     autocomplete="address-level2" />
                  @error('city')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                  @enderror
               </div>
               <div class="form-control md:flex-1">
                  <label class="form-label" for="edit_postal">
                  Postal Code <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="edit_postal" wire:model="postal_code"
                     class="form-input @error('postal_code') border-red-500 @enderror"
                     placeholder="Enter postal code" autocomplete="postal-code" />
                  @error('postal_code')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                  @enderror
               </div>
            </div>
            <div class="form-control">
               <label class="form-label" for="edit_county">
               County <span class="text-red-500">*</span>
               </label>
               <input type="text" id="edit_county" wire:model="county"
                  class="form-input @error('county') border-red-500 @enderror" placeholder="Enter county"
                  autocomplete="address-level1" />
               @error('county')
               <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>
            <div class="flex gap-3 mt-2">
               <button type="button" class="btn btn-primary w-28" wire:click="updateAddress"
                  wire:loading.attr="disabled" wire:target="updateAddress">
               <span wire:loading.remove wire:target="updateAddress">Update</span>
               <span wire:loading wire:target="updateAddress">Updating...</span>
               </button>
               <button type="button" class="btn btn-white"
                  wire:click="$dispatch('close-modal', { id: 'edit_address_modal' })">
               Cancel
               </button>
            </div>
         </div>
      </div>
   </x-frontend.modal>
   <x-frontend.confirm-delete-modal 
     modalId="confirm_delete_modal" 
     title="Delete Address"
     message="Are you sure you want to delete this address?"
     confirmAction="deleteAddress"
     closeAction="closeModal"
     />
</div>