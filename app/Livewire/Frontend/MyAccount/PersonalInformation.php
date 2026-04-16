<?php

namespace App\Livewire\Frontend\MyAccount;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\Toast;

class PersonalInformation extends Component
{
    use Toast;

    public string $name        = '';
    public string $email       = '';
    public string $phoneCode   = '+91';
    public string $phone       = '';
    public string $passport_id = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->name        = $user->name         ?? '';
        $this->email       = $user->email        ?? '';
        $this->passport_id = $user->passport_id  ?? '';
        $this->phone       = $user->phone_number ?? '';
        $this->phoneCode   = $user->country_code ?? '+91';
    }

    protected function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:100'],
            'phone'       => ['required', 'string', 'min:5', 'max:15'],
            'phoneCode'   => ['required', 'string', 'max:6'],
            'passport_id' => ['nullable', 'string', 'max:20'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'      => 'Full name is required.',
            'name.min'           => 'Name must be at least 2 characters.',
            'phone.required'     => 'Phone number is required.',
            'phone.min'          => 'Phone number must be at least 5 digits.',
            'phone.max'          => 'Phone number must not exceed 15 digits.',
            'phoneCode.required' => 'Country code is required.',
        ];
    }

    public function update(): void
    {
        $this->validate();

        Auth::user()->update([
            'name'         => $this->name,
            'phone_number' => $this->phone,
            'country_code' => $this->phoneCode,
            'passport_id'  => $this->passport_id ?: null,
        ]);

        $this->Toast('success', 'Personal information updated successfully.');
    }

    public function render()
    {
        return view('livewire.frontend.my-account.personal-information');
    }
}
