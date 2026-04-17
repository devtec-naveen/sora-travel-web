<?php

namespace App\Livewire\Frontend\MyAccount;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Services\Common\StripeService;
use App\Traits\Toast;

class SaveCards extends Component
{
    use Toast;

    public array $cards = [];

    public string $card_number = '';
    public string $cardholder_name = '';
    public string $expiry_date = '';
    public string $cvv = '';

    public bool $isLoading = false;

    public function mount(StripeService $stripeService): void
    {
        $this->cards = $stripeService->listCards(Auth::user())?->toArray() ?? [];
    }

    protected function rules(): array
    {
        return [
            'card_number'     => ['required', 'string', 'min:12', 'max:19'],
            'cardholder_name' => ['required', 'string', 'min:2', 'max:100'],
            'expiry_date'     => ['required', 'string'],
            'cvv'             => ['required', 'digits_between:3,4'],
        ];
    }

    protected function messages(): array
    {
        return [
            'card_number.required'     => 'Card number is required.',
            'cardholder_name.required' => 'Cardholder name is required.',
            'expiry_date.required'     => 'Expiry date is required.',
            'cvv.required'             => 'CVV is required.',
            'cvv.digits_between'       => 'CVV must be 3 or 4 digits.',
        ];
    }

    #[On('stripePaymentMethod')]
    public function stripePaymentMethod($paymentMethodId, StripeService $stripeService): void
    {
        try {
            $stripeService->saveCard(Auth::user(), $paymentMethodId);

            $this->cards = $stripeService->listCards(Auth::user())?->toArray() ?? [];

            $this->dispatch('close-modal', id: 'add_card_modal');

            $this->Toast('success', 'Card saved successfully.');
        } catch (\Exception $e) {
            $this->Toast('error', $e->getMessage());
        }
    }

    public function openModal($id)
    {
        $this->dispatch('open-modal', id: $id);
    }

    public function closeModal($id)
    {
        $this->dispatch('close-modal', id: $id);
    }

    public function resetForm(): void
    {
        $this->reset([
            'card_number',
            'cardholder_name',
            'expiry_date',
            'cvv'
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function setDefault(string $paymentMethodId, StripeService $stripeService): void
    {
        $stripeService->setDefaultCard(Auth::user(), $paymentMethodId);
        $this->cards = $stripeService->listCards(Auth::user())?->toArray() ?? [];
        $this->Toast('success', 'Default card updated.');
    }

    public function deleteCard(string $paymentMethodId, StripeService $stripeService): void
    {
        $stripeService->deleteCard(Auth::user(), $paymentMethodId);
        $this->cards = $stripeService->listCards(Auth::user())?->toArray() ?? [];
        $this->Toast('success', 'Card removed successfully.');
    }

    public function render()
    {
        return view('livewire.frontend.my-account.save-cards');
    }
}