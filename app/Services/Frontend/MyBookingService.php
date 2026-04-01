<?php

namespace App\Services\Frontend;

use App\Models\OrderModel;
use App\Repositories\Frontend\MyBookingRepository;
use Illuminate\Support\Collection;
use App\Services\Common\Duffel\DuffelService;

class MyBookingService
{
    public function __construct(
        protected MyBookingRepository $repository,
        protected DuffelService $suffelService
    ) {}

    public function getOrders(
        string $type,
        string $status,
        string $dateRange = ''
    ): Collection {
        return $this->repository->getOrders($type, $status, $dateRange);
    }

    public function parseFlightOrder(array $data): array
    {
        $slice   = $data['slices'][0]    ?? [];
        $segment = $slice['segments'][0] ?? [];
        $pax     = $segment['passengers'][0] ?? [];
        $carrier = $segment['marketing_carrier'] ?? [];
        $origin  = $segment['origin']      ?? [];
        $dest    = $segment['destination'] ?? [];
        $bags    = collect($data['services'] ?? [])->where('type', 'baggage');

        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $segment['duration'] ?? 'PT0H0M', $m);

        return [
            'booking_reference' => $data['booking_reference'] ?? null,
            'carrier'           => $carrier,
            'origin'            => $origin,
            'destination'       => $dest,
            'dep_at'            => \Carbon\Carbon::parse($segment['departing_at'] ?? null),
            'arr_at'            => \Carbon\Carbon::parse($segment['arriving_at']  ?? null),
            'duration'          => ($m[1] ?? 0) . 'h ' . ($m[2] ?? 0) . 'm',
            'stop_label'        => (count($slice['segments'] ?? []) - 1) === 0
                ? 'Direct'
                : (count($slice['segments']) - 1) . ' stop(s)',
            'cabin_class'       => $pax['cabin_class_marketing_name'] ?? null,
            'fare_brand'        => $slice['fare_brand_name']           ?? null,
            'baggages'          => $pax['baggages']                    ?? [],
            'checked_bag_kg'    => $bags->first()['metadata']['maximum_weight_kg'] ?? null,
            'origin_terminal'   => $segment['origin_terminal']         ?? null,
            'dest_terminal'     => $segment['destination_terminal']    ?? null,
            'flight_number'     => ($carrier['iata_code'] ?? '') . ($segment['marketing_carrier_flight_number'] ?? ''),
            'aircraft'          => $segment['aircraft']['name']        ?? null,
        ];
    }

    public function getStatusFlags(OrderModel $order): array
    {
        $isUpcoming  = in_array($order->status, ['pending', 'confirmed'])
            && $order->booking_date
            && \Carbon\Carbon::parse($order->booking_date)->isFuture();

        $isCompleted = $order->status === 'confirmed'
            && $order->booking_date
            && \Carbon\Carbon::parse($order->booking_date)->isPast();

        $isCancelled = in_array($order->status, ['cancelled', 'failed']);

        return compact('isUpcoming', 'isCompleted', 'isCancelled');
    }

    public function getParsedOrders(
        string $type,
        string $status,
        string $dateRange = ''
    ): Collection {
        return $this->getOrders($type, $status, $dateRange)
            ->map(function (OrderModel $order) use ($type) {
                $data = is_array($order->data)
                    ? $order->data
                    : json_decode($order->data, true);

                return [
                    'order'  => $order,
                    'flags'  => $this->getStatusFlags($order),
                    'parsed' => $type === 'flight' && $data
                        ? $this->parseFlightOrder($data)
                        : null,
                ];
            });
    }

    public function cancelOrder(array $data): array
    {
        return $this->suffelService->cancelOrder($data);
    }
}
