<?php

namespace App\Livewire\Frontend\Mybooking;

use Livewire\Component;
use App\Services\Common\MyBookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Listing extends Component
{
    public string $activeType   = 'flight';
    public string $activeStatus = 'upcoming';
    public string $dateRange    = '';

    public bool $showCancelModal = false;
    public $cancelOrderId = null;

    protected MyBookingService $service;

    public function boot(MyBookingService $service): void
    {
        $this->service = $service;
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

    public function confirmCancel(): void
    {
        if (!$this->cancelOrderId) return;
        
        try {
            $result = $this->service->cancelOrder([
                'order_id' => $this->cancelOrderId,
                'user_id'  => Auth::id(),
            ]);
                
                dd($result);

            $this->closeModal();

            $this->dispatch('notify',
                type: $result['success'] ? 'success' : 'danger',
                message: $result['message'] ?? 'Something went wrong.',
            );

        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: $e->getMessage());
        }
    }

    public function setType(string $type): void
    {
        $this->activeType   = $type;
        $this->activeStatus = 'upcoming';
    }

    public function setStatus(string $status): void
    {
        $this->activeStatus = $status;
    }

    public function render()
    {
        return view('livewire.frontend.mybooking.listing', [
            'parsedOrders' => $this->service->getParsedOrders(
                $this->activeType,
                $this->activeStatus,
                $this->dateRange,
            ),
        ]);
    }
}