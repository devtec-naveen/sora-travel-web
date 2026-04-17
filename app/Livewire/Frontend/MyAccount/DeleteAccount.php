<?php

namespace App\Livewire\Frontend\MyAccount;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Services\Common\MyAccountService;
use App\Traits\Toast;
use Illuminate\Support\Facades\Auth;

class DeleteAccount extends Component
{
    use Toast;

    /** 'confirm' → show warning modal | 'password' → show password step */
    public string $step = 'confirm';

    #[Validate('required', message: 'Password is required.')]
    public string $password = '';

    public bool $isLoading = false;

    /*
    |--------------------------------------------------------------------------
    | OPEN MODAL — called from "Delete Account" button on page
    |--------------------------------------------------------------------------
    */
    public function openModal(): void
    {
        $this->step     = 'confirm';
        $this->password = '';
        $this->resetErrorBag();
        $this->dispatch('open-modal', id: 'delete_account_modal');
    }

    public function closeModal(): void
    {
        $this->step     = 'confirm';
        $this->password = '';
        $this->resetErrorBag();
        $this->dispatch('close-modal', id: 'delete_account_modal');
    }

    /*
    |--------------------------------------------------------------------------
    | STEP: confirm → password
    |--------------------------------------------------------------------------
    */
    public function proceedToPassword(): void
    {
        $this->step = 'password';
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM DELETE — verify password, delete account, logout, redirect
    |--------------------------------------------------------------------------
    */
    public function confirmDelete(MyAccountService $myAccountService): void
    {
        $this->validate();

        $this->isLoading = true;

        try {
            $myAccountService->deleteAccount($this->password);

            Auth::logout();

            session()->invalidate();
            session()->regenerateToken();

            $this->dispatch('close-modal', id: 'delete_account_modal');

            $this->redirect(route('home'), navigate: false);

        } catch (\RuntimeException $e) {
            $this->addError('password', $e->getMessage());
        } catch (\Exception $e) {
            $this->Toast('error', 'Something went wrong. Please try again.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.frontend.my-account.delete-account');
    }
}
