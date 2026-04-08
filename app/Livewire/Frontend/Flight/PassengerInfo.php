<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class PassengerInfo extends Component
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
    public bool   $isLoading      = true;
    public $returnDate;
    public float $baseAmount  = 0;
    public float $platformFee = 0;

    public function mount(): void
    {
        $session = session('selected_flight', []);
        $this->returnDate = $session['return_date'] ?? null;
    }

    protected function rules()
    {
        return [
            'passengers.*.first_name' => 'required|string',
            'passengers.*.last_name'  => 'required|string',
            'passengers.*.gender'     => 'required|string',
            'passengers.*.dob'        => 'required|string',
            'email'                   => 'required|email',
            'phone'                   => 'required|string|min:7|max:15',
        ];
    }

    protected function messages()
    {
        return [
            'passengers.*.first_name.required' => 'First name is required for all passengers.',
            'passengers.*.last_name.required'  => 'Last name is required for all passengers.',
            'passengers.*.gender.required'     => 'Gender is required for all passengers.',
            'passengers.*.dob.required'        => 'Date of birth is required for all passengers.',

            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',

            'phone.required' => 'Phone number is required.',
            'phone.min'      => 'Phone number must be at least 7 digits.',
            'phone.max'      => 'Phone number must not exceed 15 digits.',
        ];
    }

    public function loadData(): void
    {
        sleep(1);
        $session = session('selected_flight', []);
        $this->selectedFlight = $session['flight']    ?? [];
        $this->adults         = (int) ($session['adults']    ?? request('adults',   1));
        $this->children       = (int) ($session['children']  ?? request('childrens', 0));
        $this->infants        = (int) ($session['infants']   ?? request('infants',  0));
        $this->cabinClass     = $session['cabinClass'] ?? request('cabin_class', 'Economy');
        $this->returnDate = $session['return_date'] ?? null;
        $this->baseAmount  = (float) ($session['base_amount']  ?? 0);
        $this->platformFee = (float) ($session['platform_fee'] ?? 0);

        $saved = session('passenger_info', []);
        if (! empty($saved['passengers'])) {
            $this->passengers = $saved['passengers'];
        } else {
            $this->buildPassengers();
        }

        if (! empty($saved['contact'])) {
            $this->email     = $saved['contact']['email']      ?? '';
            $this->phoneCode = $saved['contact']['phone_code'] ?? '+91';
            $this->phone     = $saved['contact']['phone']      ?? '';
        }

        $this->isLoading = false;
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
            'type'            => $type,
            'num'             => $num,
            'title'           => 'Mr',
            'first_name'      => '',
            'last_name'       => '',
            'gender'          => '',
            'dob'             => '',
            'passport_no'     => '',
            'passport_expiry' => '',
        ];
    }

    public function continue(): void
    {
        $this->validate();

        session([
            'passenger_info' => [
                'flight'     => $this->selectedFlight,
                'base_amount'  => $this->baseAmount, 
                'platform_fee' => $this->platformFee,
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
                'return_date' => $this->returnDate,
            ],
        ]);

        $this->redirect(route('airport.addon'));
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        $reuturnDate      = $this->returnDate;
        $slice   = $sf['slices'][0]      ?? [];
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
            'reuturnDate' => $reuturnDate
        ]);
    }
}