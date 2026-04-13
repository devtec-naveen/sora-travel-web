<?php

namespace App\Repositories\Backend;

use App\Models\Order;
use App\Models\OrderModel;
use Illuminate\Pagination\LengthAwarePaginator;

class MyBookingRepository
{
    public function getPaginatedBookings(
        string $type = 'flight',
        string $search = '',
        string $sortField = 'id',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return OrderModel::query()
            ->with(['user', 'payment'])
            ->where('type', $type)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('order_number', 'like', "%{$search}%")
                       ->orWhere('external_id', 'like', "%{$search}%")
                       ->orWhereHas('user', fn($u) =>
                           $u->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                       );
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    public function findById(int $id, string $type): OrderModel
    {
        return OrderModel::with(['user', 'payment'])
            ->where('type', $type)
            ->findOrFail($id);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return (bool) OrderModel::where('id', $id)->update(['status' => $status]);
    }

    public function getCountByType(): array
    {
        return OrderModel::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }
}