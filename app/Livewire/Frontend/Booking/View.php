<?php

namespace App\Livewire\Frontend\Booking;

use Livewire\Component;
use App\Services\Common\MyBookingService;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public int|string $bookingId;
    public array      $order               = [];
    public bool       $isLoading           = true;
    public bool       $showCancelModal     = false;
    public bool       $showRescheduleModal = false;

    protected MyBookingService $service;

    public function boot(MyBookingService $service): void
    {
        $this->service = $service;
    }

    public function mount(int|string $id): void
    {
        $this->bookingId = $id;
    }

    public function loadData(): void
    {
        sleep(1);
        $result = $this->service->getOrderDetail($this->bookingId);
        if (!$result) {
            $this->redirect(route('booking.index'));
            return;
        }
        $this->order     = $result;
        $this->isLoading = false;
    }

    public function confirmCancel(): void
    {
        try {
            $result = $this->service->cancelOrder([
                'order_id' => $this->bookingId,
                'user_id'  => Auth::id(),
            ]);

            $this->showCancelModal = false;

            $this->dispatch('notify',
                type:    $result['success'] ? 'success' : 'danger',
                message: $result['message'] ?? 'Something went wrong.',
            );

        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: $e->getMessage());
        }
    }

    public function render()
    {
        $o          = null;
        $flags      = null;
        $p          = null;
        $priceColor = null;
        $services   = [];
        $passengers = [];
        $contact    = [];
        $conditions = [];

        if (! $this->isLoading && ! empty($this->order)) {
            $o          = $this->order['order'];
            $flags      = $this->order['flags'];
            $p          = $this->order['parsed'];
            $services   = $this->order['services']    ?? [];
            $passengers = $this->order['passengers']  ?? [];
            $contact    = $this->order['contact']     ?? [];
            $conditions = $this->order['conditions']  ?? [];

            $priceColor = $flags['isCancelled']
                ? 'text-red-600'
                : ($flags['isCompleted'] ? 'text-green-600' : 'text-blue-600');
        }

        return view('livewire.frontend.booking.view', [
            'o'          => $o,
            'flags'      => $flags,
            'p'          => $p,
            'priceColor' => $priceColor,
            'services'   => $services,
            'passengers' => $passengers,
            'contact'    => $contact,
            'conditions' => $conditions,
        ]);
    }
}