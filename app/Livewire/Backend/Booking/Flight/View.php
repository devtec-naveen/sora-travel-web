<?php

namespace App\Livewire\Backend\Booking\Flight;

use Livewire\Component;
use App\Services\Backend\MyBookingService;
use App\Traits\Toast;

class View extends Component
{
    use Toast;

    public int    $bookingId;
    public string $type = 'flight';
    public array  $order    = [];
    public array  $payment  = [];
    public array  $flightData = [];

    protected $listeners = ['changeStatus'];

    public function mount(int $id, string $type = 'flight'): void
    {
        $this->bookingId = $id;
        $this->type      = $type;

        $booking = app(MyBookingService::class)->findById($id, $type);

        $this->order   = $booking->toArray();
        $this->payment = $booking->payment?->toArray() ?? [];

        $data = $booking->data;
        $this->flightData = is_string($data) ? json_decode($data, true) : (array) $data;
    }

    public function changeStatus(int $id, string $status): void
    {
        app(MyBookingService::class)->updateStatus($id, $status);
        $this->SessionToast('success', 'Status updated successfully!');
        $this->mount($this->bookingId, $this->type);
    }

    public function render()
    {
        return view('livewire.backend.booking.flight.view', [
            'order'      => $this->order,
            'payment'    => $this->payment,
            'flightData' => $this->flightData,
            'type'       => $this->type,
        ]);
    }
}