<?php

namespace App\Livewire\Frontend\MyAccount;

use Livewire\Component;
use App\Services\Common\MyAccountService;
use App\Traits\Toast;

class SaveAddress extends Component
{
    use Toast;

    public array $addresses = [];

    public string $street_address = '';
    public string $city           = '';
    public string $postal_code    = '';
    public string $county         = '';
    public ?int $deleteId = null;

    public ?int $editingId = null;

    public function mount(MyAccountService $myAccountService): void
    {
        $this->addresses = $myAccountService->getAddresses()->toArray();
    }

    protected function rules(): array
    {
        return [
            'street_address' => ['required', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'postal_code'    => ['required', 'string', 'max:20'],
            'county'         => ['required', 'string', 'max:100'],
        ];
    }

    protected function messages(): array
    {
        return [
            'street_address.required' => 'Street address is required.',
            'city.required'           => 'City is required.',
            'postal_code.required'    => 'Postal code is required.',
            'county.required'         => 'County is required.',
        ];
    }

    public function openAddModal($e)
    {
        $this->resetForm();
        if ($e === "add_address_modal") {
            $this->dispatch('open-modal', id: 'add_address_modal');
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', id: 'confirm_delete_modal');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->dispatch('close-modal', id: 'add_address_modal');
        $this->dispatch('close-modal', id: 'confirm_delete_modal');
    }

    public function saveAddress(MyAccountService $myAccountService): void
    {
        $this->validate();

        try {
            $myAccountService->createAddress([
                'street_address' => $this->street_address,
                'city'           => $this->city,
                'postal_code'    => $this->postal_code,
                'county'         => $this->county,
            ]);

            $this->addresses = $myAccountService->getAddresses()->toArray();
            $this->resetForm();
            $this->dispatch('close-modal', id: 'add_address_modal');
            $this->Toast('success', 'Address added successfully.');
        } catch (\Exception $e) {
            $this->Toast('error', $e->getMessage());
        }
    }

    public function openEditModal(int $id, MyAccountService $myAccountService): void
    {
        $address = $myAccountService->getAddresses()->firstWhere('id', $id);

        if (!$address) {
            $this->Toast('error', 'Address not found.');
            return;
        }

        $this->editingId      = $id;
        $this->street_address = $address['street_address'];
        $this->city           = $address['city'];
        $this->postal_code    = $address['postal_code'];
        $this->county         = $address['county'];

        $this->dispatch('open-modal', id: 'edit_address_modal');
    }

    public function updateAddress(MyAccountService $myAccountService): void
    {
        $this->validate();

        try {
            $myAccountService->updateAddress($this->editingId, [
                'street_address' => $this->street_address,
                'city'           => $this->city,
                'postal_code'    => $this->postal_code,
                'county'         => $this->county,
            ]);

            $this->addresses = $myAccountService->getAddresses()->toArray();
            $this->resetForm();
            $this->dispatch('close-modal', id: 'edit_address_modal');
            $this->Toast('success', 'Address updated successfully.');
        } catch (\Exception $e) {
            $this->Toast('error', $e->getMessage());
        }
    }

    public function deleteAddress(MyAccountService $myAccountService): void
    {
        try {
            $myAccountService->deleteAddress($this->deleteId);
            $this->addresses = $myAccountService->getAddresses()->toArray();
            $this->Toast('success', 'Address deleted successfully.');
            $this->dispatch('close-modal', id: 'confirm_delete_modal');
        } catch (\Exception $e) {
            $this->Toast('error', $e->getMessage());
        }
    }

    public function resetForm(): void
    {
        $this->reset(['street_address', 'city', 'postal_code', 'county', 'editingId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.frontend.my-account.save-address');
    }
}
