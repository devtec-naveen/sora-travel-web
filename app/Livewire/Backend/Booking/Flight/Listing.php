<?php

namespace App\Livewire\Backend\Booking\Flight;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Backend\MyBookingService;
use App\Traits\Toast;

class Listing extends Component
{
    use WithPagination, Toast;

    public string $activeTab      = 'flight';
    public string $search         = '';
    public string $sortField      = 'id';
    public string $sortDirection  = 'desc';
    public int    $perPage        = 10;
    public array  $counts         = [];

    protected $listeners = ['changeStatus'];

    protected $queryString = [
        'activeTab'     => ['except' => 'flight'],
        'search'        => ['except' => ''],
        'sortField'     => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->counts = app(MyBookingService::class)->getCountByType();
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->reset(['search', 'sortField', 'sortDirection']);
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'sortField', 'sortDirection']);
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function changeStatus(int $id, string $status): void
    {
        app(MyBookingService::class)->updateStatus($id, $status);
        $this->SessionToast('success', 'Booking status updated successfully!');
    }

    public function render(MyBookingService $service)
    {
        $bookings = $service->getBookings(
            $this->activeTab,
            $this->search,
            $this->sortField,
            $this->sortDirection,
            $this->perPage
        );

        return view('livewire.backend.booking.flight.listing', [
            'bookings' => $bookings,
            'service'  => $service,
            'types'    => $service->getAvailableTypes(),
        ]);
    }
}