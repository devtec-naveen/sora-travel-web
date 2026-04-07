<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class Confirmation extends Component
{
    public array  $order    = [];
    public string $currency = '';
    public string $tripType = 'one_way';

    public function mount(): void
    {
        $this->order    = session('last_order', []);
        $this->currency = $this->order['total_currency'] ?? '';
        $this->tripType = session('selected_flight.tripType', 'one_way');
    }

    public function render()
    {
        $order  = $this->order;
        $slices = $order['slices'] ?? [];

        return view('livewire.frontend.flight.confirmation', [
            'order'    => $order,
            'slices'   => $slices,
            'tripType' => $this->tripType,
            'currency' => $this->currency,
        ]);
    }

    public function redirectHome(): void
    {
        session()->forget('selected_flight');
        $this->redirect(route('home'));
    }
}