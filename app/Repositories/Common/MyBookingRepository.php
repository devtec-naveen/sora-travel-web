<?php

namespace App\Repositories\Common;

use App\Models\OrderModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MyBookingRepository
{
    public function getOrders(string $type,string $status,string $dateRange = ''): Collection 
    {
        $now = now();
        $orders = OrderModel::where('user_id', Auth::id())
            ->where('type', $type)
            ->when(
                $status === 'cancelled',
                fn($q) => $q
                    ->whereIn('status', ['cancelled'])
            )
            ->when(
                in_array($status, ['upcoming', 'completed']),
                fn($q) => $q
                    ->whereIn('status', ['pending', 'confirmed'])
            )
            ->when($dateRange === '7days',   fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($dateRange === '30days',  fn($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($dateRange === '3months', fn($q) => $q->where('created_at', '>=', now()->subMonths(3)))
            ->latest('booking_date')
            ->get();

        if (in_array($status, ['upcoming', 'completed'])) {
            $orders = $orders->filter(function ($order) use ($status, $now) {
                $data = is_array($order->data)
                    ? $order->data
                    : json_decode($order->data, true);

                $slices = $data['slices'] ?? [];

                if (empty($slices)) return false;

                $lastSlice    = end($slices);
                $segments     = $lastSlice['segments'] ?? [];

                if (empty($segments)) return false;

                $lastSegment  = end($segments);
                $arrivingAt   = $lastSegment['arriving_at'] ?? null;

                if (!$arrivingAt) return false;

                try {
                    $arrivalTime = \Carbon\Carbon::parse($arrivingAt);
                } catch (\Throwable $e) {
                    return false;
                }

                return $status === 'upcoming'
                    ? $arrivalTime->greaterThanOrEqualTo($now)
                    : $arrivalTime->lessThan($now);
            })->values();
        }

        return $orders;
    }

    public function getOrderById(int|string $id): ?OrderModel
    {
        return OrderModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->first();
    }
}
