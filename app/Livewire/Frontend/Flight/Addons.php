<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class Addons extends Component
{
    public array  $selectedFlight = [];
    public array  $passengers     = [];
    public array  $contact        = [];
    public int    $adults         = 1;
    public int    $children       = 0;
    public int    $infants        = 0;
    public string $currency       = '';
    public float  $baseTotal      = 0;

    public array  $baggage        = [];
    public array  $meals          = [];

    public array  $baggageOptions = [
        ['label' => 'No extra baggage', 'price' => 0,  'value' => '0kg'],
        ['label' => '5 kg',             'price' => 15, 'value' => '5kg'],
        ['label' => '10 kg',            'price' => 25, 'value' => '10kg'],
        ['label' => '15 kg',            'price' => 35, 'value' => '15kg'],
    ];

    public array  $mealOptions = [
        ['label' => 'No meal',              'price' => 0,  'value' => 'none'],
        ['label' => 'Vegetarian Meal',      'price' => 15, 'value' => 'veg'],
        ['label' => 'Non-Vegetarian Meal',  'price' => 25, 'value' => 'non-veg'],
        ['label' => 'Vegan Meal',           'price' => 25, 'value' => 'vegan'],
    ];

    public function mount(): void
    {
        $session = session('passenger_info', []);

        $this->selectedFlight = $session['flight']      ?? [];
        $this->passengers     = $session['passengers']  ?? [];
        $this->contact        = $session['contact']     ?? [];
        $this->adults         = (int) ($session['adults']   ?? 1);
        $this->children       = (int) ($session['children'] ?? 0);
        $this->infants        = (int) ($session['infants']  ?? 0);

        $sf             = $this->selectedFlight;
        $this->currency = $sf['total_currency'] ?? '';
        $this->baseTotal= (float) ($sf['total_amount'] ?? 0);

        // Default: no extra baggage, no meal for each passenger
        foreach ($this->passengers as $idx => $pax) {
            $this->baggage[$idx] = '0kg';
            $this->meals[$idx]   = 'none';
        }
    }

    public function getAddonsTotal(): float
    {
        $total = 0;
        foreach ($this->baggage as $val) {
            $opt = collect($this->baggageOptions)->firstWhere('value', $val);
            $total += $opt['price'] ?? 0;
        }
        foreach ($this->meals as $val) {
            $opt = collect($this->mealOptions)->firstWhere('value', $val);
            $total += $opt['price'] ?? 0;
        }
        return $total;
    }

    public function continue(): void
    {
        $addonsTotal = $this->getAddonsTotal();

        // Build selected addons per passenger
        $selectedAddons = [];
        foreach ($this->passengers as $idx => $pax) {
            $baggageOpt = collect($this->baggageOptions)->firstWhere('value', $this->baggage[$idx] ?? '0kg');
            $mealOpt    = collect($this->mealOptions)->firstWhere('value', $this->meals[$idx] ?? 'none');
            $selectedAddons[$idx] = [
                'baggage' => $baggageOpt,
                'meal'    => $mealOpt,
            ];
        }

        session([
            'addons_info' => [
                'flight'      => $this->selectedFlight,
                'passengers'  => $this->passengers,
                'contact'     => $this->contact,
                'adults'      => $this->adults,
                'children'    => $this->children,
                'infants'     => $this->infants,
                'addons'      => $selectedAddons,
                'addonsTotal' => $addonsTotal,
                'grandTotal'  => $this->baseTotal + $addonsTotal,
                'currency'    => $this->currency,
            ],
        ]);

        $this->redirect(route('front.flight.seats'), navigate: true);
    }

    public function render()
    {
        $addonsTotal = $this->getAddonsTotal();
        $grandTotal  = $this->baseTotal + $addonsTotal;

        $sf      = $this->selectedFlight;
        $slice   = $sf['slices'][0]      ?? [];
        $segment = $slice['segments'][0] ?? [];

        return view('livewire.frontend.flight.addons', [
            'sf'          => $sf,
            'slice'       => $slice,
            'segment'     => $segment,
            'addonsTotal' => $addonsTotal,
            'grandTotal'  => $grandTotal,
        ]);
    }
}