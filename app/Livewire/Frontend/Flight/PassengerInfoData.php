<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class PassengerInfoData extends Component
{
    public array  $selectedFlight = [];
    public int    $adults         = 1;
    public int    $children       = 0;
    public int    $infants        = 0;
    public string $cabinClass     = 'Economy';

    public array  $passengers     = [];
    public string $email          = '';
    public string $phoneCode      = '+91';
    public string $phone          = '';

    public function mount(): void
    {
        $session = session('selected_flight', []);

        $this->selectedFlight = $session['flight']      ?? [];
        $this->adults         = (int) ($session['adults']    ?? request('adults',   1));
        $this->children       = (int) ($session['children']  ?? request('children', 0));
        $this->infants        = (int) ($session['infants']   ?? request('infants',  0));
        $this->cabinClass     = $session['cabinClass']   ?? request('cabin_class', 'Economy');

        $this->buildPassengers();
    }

    private function buildPassengers(): void
    {
        $this->passengers = [];

        for ($i = 0; $i < $this->adults; $i++) {
            $this->passengers[] = $this->emptyPassenger('adult', $i + 1);
        }
        for ($i = 0; $i < $this->children; $i++) {
            $this->passengers[] = $this->emptyPassenger('child', $i + 1);
        }
        for ($i = 0; $i < $this->infants; $i++) {
            $this->passengers[] = $this->emptyPassenger('infant', $i + 1);
        }
    }

    private function emptyPassenger(string $type, int $num): array
    {
        return [
            'type'           => $type,
            'num'            => $num,
            'title'          => 'Mr',
            'first_name'     => '',
            'last_name'      => '',
            'gender'         => '',
            'dob'            => '',
            'passport_no'    => '',
            'passport_expiry'=> '',
        ];
    }

    public function continue(): void
    {
        $this->validate([
            'passengers.*.first_name'  => 'required|string',
            'passengers.*.last_name'   => 'required|string',
            'passengers.*.gender'      => 'required|string',
            'passengers.*.dob'         => 'required|string',
            'email'                    => 'required|email',
            'phone'                    => 'required|string|min:7|max:15',
        ], [
            'passengers.*.first_name.required' => 'First name is required for all passengers.',
            'passengers.*.last_name.required'  => 'Last name is required for all passengers.',
            'passengers.*.gender.required'     => 'Gender is required for all passengers.',
            'passengers.*.dob.required'        => 'Date of birth is required for all passengers.',
            'email.required'                   => 'Email address is required.',
            'email.email'                      => 'Please enter a valid email address.',
            'phone.required'                   => 'Phone number is required.',
            'phone.min'                        => 'Phone number must be at least 7 digits.',
            'phone.max'                        => 'Phone number must not exceed 15 digits.',
        ]);

        session([
            'passenger_info' => [
                'flight'     => $this->selectedFlight,
                'passengers' => $this->passengers,
                'contact'    => [
                    'email'      => $this->email,
                    'phone_code' => $this->phoneCode,
                    'phone'      => $this->phone,
                ],
                'adults'     => $this->adults,
                'children'   => $this->children,
                'infants'    => $this->infants,
                'cabinClass' => $this->cabinClass,
            ],
        ]);

        $this->redirect(route('airport.addon'));
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        $slice   = $sf['slices'][0]    ?? [];
        $segment = $slice['segments'][0] ?? [];

        $price    = $sf['total_amount']   ?? '';
        $currency = $sf['total_currency'] ?? '';

        $baseFare = $price ? round((float) $price / max(1, $this->adults + $this->children + $this->infants), 2) : 0;
        $taxes    = $price ? round((float) $price - ($baseFare * ($this->adults + $this->children + $this->infants)), 2) : 0;

        return view('livewire.frontend.flight.passengers-info', [
            'sf'       => $sf,
            'slice'    => $slice,
            'segment'  => $segment,
            'price'    => $price,
            'currency' => $currency,
            'baseFare' => $baseFare,
            'taxes'    => $taxes,
        ]);
    }
}