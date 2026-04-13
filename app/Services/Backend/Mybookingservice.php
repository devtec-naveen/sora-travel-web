<?php

namespace App\Services\Backend;

use App\Models\Order;
use App\Models\OrderModel;
use App\Repositories\Backend\MyBookingRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MyBookingService
{
    public function __construct(
        protected MyBookingRepository $repo
    ) {}

    public function getBookings(
        string $type,
        string $search = '',
        string $sortField = 'id',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $allowed = $this->getAllowedSortFields($type);

        if (!in_array($sortField, $allowed)) {
            $sortField = 'id';
        }

        return $this->repo->getPaginatedBookings($type, $search, $sortField, $sortDirection, $perPage);
    }

    public function findById(int $id, string $type): OrderModel
    {
        return $this->repo->findById($id, $type);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'confirmed', 'cancelled', 'failed'];

        if (!in_array($status, $allowed)) {
            return false;
        }

        return $this->repo->updateStatus($id, $status);
    }

    public function getCountByType(): array
    {
        return $this->repo->getCountByType();
    }

    public function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'confirmed' => 'badge-success',
            'pending'   => 'badge-warning',
            'cancelled' => 'badge-danger',
            'failed'    => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }

    public function getPaymentBadgeClass(string $status): string
    {
        return match ($status) {
            'completed' => 'badge-success',
            'pending'   => 'badge-warning',
            'failed'    => 'badge-danger',
            'refunded'  => 'badge-info',
            default     => 'badge-secondary',
        };
    }

    public function getAvailableTypes(): array
    {
        return [
            'flight' => ['label' => 'Flights',  'icon' => 'fa-plane'],
            'hotel'  => ['label' => 'Hotels',   'icon' => 'fa-hotel'],
            'car'    => ['label' => 'Cars',      'icon' => 'fa-car'],
        ];
    }

    private function getAllowedSortFields(string $type): array
    {
        $common = ['id', 'order_number', 'total_amount', 'status', 'created_at', 'booking_date'];

        return match ($type) {
            'flight' => $common,
            'hotel'  => [...$common],
            'car'    => [...$common],
            default  => $common,
        };
    }
}