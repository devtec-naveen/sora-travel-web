<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class Confirmation extends Component
{
    public array  $order    = [];
    public string $currency = '';

    public function mount(): void
    {
        $this->order    = session('last_order', []);
        $this->currency = $this->order['total_currency'] ?? '';
    }

    public function render()
    {
        $order   = $this->order;
        $slice   = $order['slices'][0]      ?? [];
        $segment = $slice['segments'][0]    ?? [];

        return view('livewire.frontend.flight.confirmation', [
            'order'   => $order,
            'slice'   => $slice,
            'segment' => $segment,
        ]);
    }
}