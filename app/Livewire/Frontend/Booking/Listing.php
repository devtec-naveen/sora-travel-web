<?php

namespace App\Livewire\Frontend\Booking;

use Livewire\Component;
use App\Services\Common\MyBookingService;
use Illuminate\Support\Facades\Auth;

class Listing extends Component
{
    public string $activeType   = 'flight';
    public string $activeStatus = 'upcoming';
    public string $dateRange    = '';
    public bool $showCancelModal = false;
    public $cancelOrderId = null;
    public bool $isLoading = true;

    protected MyBookingService $service;

    public function boot(MyBookingService $service): void
    {
        $this->service = $service;
    }

    public function loadData(): void
    {
        sleep(1);
        $this->isLoading = false;
    }

    public function openModal($orderId): void
    {
        $this->cancelOrderId   = $orderId;
        $this->showCancelModal = true;
    }

    public function closeModal(): void
    {
        $this->cancelOrderId   = null;
        $this->showCancelModal = false;
    }

    // public function confirmCancel(): void
    // {
    //     if (!$this->cancelOrderId) return;

    //     try {
    //         $result = $this->service->cancelOrder([
    //             'order_id' => $this->cancelOrderId,
    //             'user_id'  => Auth::id(),
    //         ]);

    //         $this->closeModal();

    //         $this->dispatch('notify',
    //             type: $result['success'] ? 'success' : 'danger',
    //             message: $result['message'] ?? 'Something went wrong.',
    //         );

    //     } catch (\Throwable $e) {
    //         $this->dispatch('notify', type: 'danger', message: $e->getMessage());
    //     }
    // }

    public function setType(string $type): void
    {
        $this->activeType   = $type;
        $this->activeStatus = 'upcoming';
        $this->isLoading = true;
        $this->loadData();
    }

    public function setStatus(string $status): void
    {
        sleep(1);
        $this->activeStatus = $status;
    }

    public function updatedDateRange()
    {
        sleep(1);
        $this->isLoading = true;
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.frontend.booking.listing', [
            'parsedOrders' => $this->isLoading
                ? []
                : $this->service->getParsedOrders($this->activeType, $this->activeStatus, $this->dateRange),
        ]);
    }
}