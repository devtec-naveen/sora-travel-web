<?php

namespace App\Repositories\Common;

use App\Models\OrderModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MyBookingRepository
{
    public function getOrders(
        string $type,
        string $status,
        string $dateRange = ''
    ): Collection {
        return OrderModel::where('user_id', Auth::id())
            ->where('type', $type)
            ->when($status === 'upcoming', fn($q) => $q
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('booking_date', '>=', now()->startOfDay())
            )
            ->when($status === 'completed', fn($q) => $q
                ->where('status', 'confirmed')
                ->where('booking_date', '<', now()->startOfDay())
            )
            ->when($status === 'cancelled', fn($q) => $q
                ->whereIn('status', ['cancelled', 'failed'])
            )
            ->when($dateRange === '7days',   fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($dateRange === '30days',  fn($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($dateRange === '3months', fn($q) => $q->where('created_at', '>=', now()->subMonths(3)))
            ->latest('booking_date')
            ->get();
    }

    public function getOrderById(int|string $id): ?OrderModel
    {
        return OrderModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->first();
    }



}