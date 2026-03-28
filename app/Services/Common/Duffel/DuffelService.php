<?php

namespace App\Services\Common\Duffel;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DuffelService
{
    private $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }
    
    public function searchFlightsMain(array $data): array
    {
        // Multi-city
        if (!empty($data['trips']) && count($data['trips']) > 1) {
            return $this->searchMultiCity($data);
        }

        // Round-trip
        if (!empty($data['returnDate'])) {
            return $this->searchRoundTrip($data);
        }

        // Default -> One-way
        return $this->searchOneWay($data);
    }

    private function searchOneWay(array $data): array
    {
        $params = [
            "data" => [
                "slices" => [
                    [
                        "origin" => $data['origin'],
                        "destination" => $data['destination'],
                        "departure_date" => $data['departureDate']
                    ]
                ],
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];

        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }   

    private function searchRoundTrip(array $data): array
    {
        $slices = [
            [
                "origin" => $data['origin'],
                "destination" => $data['destination'],
                "departure_date" => $data['departureDate']
            ],
            [
                "origin" => $data['destination'],
                "destination" => $data['origin'],
                "departure_date" => $data['returnDate']
            ]
        ];

        $params = [
            "data" => [
                "slices" => $slices,
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];

        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }

    private function searchMultiCity(array $data): array
    {
        $slices = [];

        foreach ($data['trips'] as $trip) {
            $slices[] = [
                "origin" => $trip['origin'],
                "destination" => $trip['destination'],
                "departure_date" => $trip['departureDate']
            ];
        }

        $params = [
            "data" => [
                "slices" => $slices,
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];
        
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }

    private function buildPassengers(array $data): array
    {
        $passengers = [];

        $adults   = (int) ($data['adults'] ?? 1);
        $children = (int) ($data['children'] ?? 0);
        $infants  = (int) ($data['infants'] ?? 0);

        for ($i = 0; $i < $adults; $i++) {
            $passengers[] = ["type" => "adult"];
        }

        for ($i = 0; $i < $children; $i++) {
            $passengers[] = ["type" => "child"];
        }

        for ($i = 0; $i < $infants; $i++) {
            $passengers[] = ["type" => "infant_without_seat"];
        }

        return $passengers;
    }

    public function getOfferWithServices(string $offerId): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->get("/air/offers/{$offerId}", [
                'return_available_services' => 'true',
            ]);
 
        if ($response->failed()) {
            return [
                'offer'    => [],
                'services' => [],
                'error'    => 'Failed to fetch offer services. Status: ' . $response->status(),
            ];
        }
 
        $offer    = $response->json('data', []);
        $allSvcs  = $offer['available_services'] ?? [];
 
        // Sirf baggage type services lo
        $baggage  = collect($allSvcs)
            ->filter(fn($s) => ($s['type'] ?? '') === 'baggage')
            ->keyBy('id')
            ->toArray();
 
        return [
            'offer'    => $offer,
            'services' => $baggage,
            'error'    => null,
        ];
    }

    public function getSeatMaps(string $offerId): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth->client()->get('/air/seat_maps', [
            'offer_id' => $offerId,
        ]);
 
        if ($response->failed()) {
            return [
                'seat_maps' => [],
                'error'     => 'Failed to fetch seat maps. Status: ' . $response->status(),
            ];
        }
 
        return [
            'seat_maps' => $response->json('data', []),
            'error'     => null,
        ];
    }

    public function createOrder(array $data): array
    {
        $offerId    = $data['offer_id'];
        $services   = $data['services']   ?? [];
        $passengers = $data['passengers'];
        $currency   = $data['currency'];

        /** @var \Illuminate\Http\Client\Response $offerResponse */
        $offerResponse = $this->auth->client()->get("/air/offers/{$offerId}");
        $offerData     = $offerResponse->json('data', []);
        $offerAmount   = $offerData['total_amount']   ?? null;
        $offerCurrency = $offerData['total_currency'] ?? $currency;

        if (!$offerAmount) {
            throw new \Exception('Duffel: Could not resolve offer amount.');
        }

        $servicesAmount = (float) ($data['services_amount'] ?? 0.0);
        $grandTotal     = number_format((float) $offerAmount + $servicesAmount, 2, '.', '');

        $normalizedServices = array_values(array_map(
            fn($s) => ['id' => $s['id'], 'quantity' => (int) ($s['quantity'] ?? 1)],
            array_filter($services, fn($s) => !empty($s['id']))
        ));

        [$payment, $order] = DB::transaction(function () use ($grandTotal, $offerCurrency) {

            $payment = PaymentModel::create([
                'user_id'        => Auth::id(),
                'payment_id'     => 'PENDING-' . Str::uuid(),
                'payment_method' => 'balance',
                'amount'         => $grandTotal,
                'currency'       => $offerCurrency,
                'status'         => 'pending',
            ]);

            $order = OrderModel::create([
                'user_id'      => Auth::id(),
                'payment_id'   => $payment->id,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'type'         => 'flight',
                'external_id'  => null, 
                'amount'       => $grandTotal,
                'currency'     => $offerCurrency,
                'status'       => 'pending',
            ]);

            return [$payment, $order];
        });

        try {
            $payload = [
                'data' => [
                    'type'            => 'instant',
                    'selected_offers' => [$offerId],
                    'passengers'      => $passengers,
                    'payments'        => [[
                        'type'     => 'balance',
                        'currency' => $offerCurrency,
                        'amount'   => $grandTotal,
                    ]],
                ],
            ];

            if (!empty($normalizedServices)) {
                $payload['data']['services'] = $normalizedServices;
            }

            /** @var \Illuminate\Http\Client\Response $response */
            $response   = $this->auth->client()->post('/air/orders', $payload);
            $result     = $response->json();

            if (!empty($result['errors'])) {
                Log::error('Duffel createOrder failed', [
                    'offer_id'   => $offerId,
                    'grand_total'=> $grandTotal,
                    'errors'     => $result['errors'],
                ]);

                $payment->update(['status' => 'failed']);
                $order->update(['status'   => 'failed']);

                return $result;
            }

            $orderData = $result['data'];

            DB::transaction(function () use ($payment, $order, $orderData) {
                $payment->update([
                    'payment_id'       => $orderData['id'],
                    'status'           => 'completed',
                    'paid_at'          => now(),
                    'gateway_response' => $orderData,
                ]);

                $order->update([
                    'external_id'  => $orderData['id'],
                    'status'       => 'confirmed',
                    'booking_date' => $this->resolveBookingDate($orderData),
                    'data'         => $orderData,
                ]);
            });

            $result['db'] = [
                'payment_id' => $payment->id,
                'order_id'   => $order->id,
            ];

            return $result;

        } catch (\Throwable $e) {
            Log::error('createOrder unexpected error', [
                'message'  => $e->getMessage(),
                'order_id' => $order->id,
            ]);

            $payment->update(['status' => 'failed', 'failure_reason' => $e->getMessage()]);
            $order->update(['status'   => 'failed']);

            throw $e;
        }
    }

    private function resolveBookingDate(array $orderData): ?string
    {
        $departing = $orderData['slices'][0]['segments'][0]['departing_at'] ?? null;
        return $departing ? date('Y-m-d', strtotime($departing)) : null;
    }

    public function filterAndSort(array $offers, array $filters = []): array
    {
        $maxPrice      = $filters['max_price']  ?? PHP_INT_MAX;
        $stops         = $filters['stops']      ?? [];
        $airlines      = $filters['airlines']   ?? [];
        $refundable    = $filters['refundable'] ?? false;
        $sort          = $filters['sort']        ?? '';
 
        $collection = collect($offers);
 
        $collection = $collection->filter(
            fn($o) => (float) ($o['total_amount'] ?? 0) <= $maxPrice
        );
 
        if (! empty($stops)) {
            $selectedStops = array_map('intval', $stops);
            $collection = $collection->filter(function ($o) use ($selectedStops) {
                $count      = count($o['slices'][0]['segments'] ?? []) - 1;
                $normalized = $count >= 2 ? 2 : $count;
                return in_array($normalized, $selectedStops, true);
            });
        }
 
        if (! empty($airlines)) {
            $collection = $collection->filter(function ($o) use ($airlines) {
                $name = $o['slices'][0]['segments'][0]['operating_carrier']['name'] ?? '';
                return in_array($name, $airlines, true);
            });
        }
 
        if ($refundable) {
            $collection = $collection->filter(
                fn($o) => (bool) ($o['conditions']['refund_before_departure']['allowed'] ?? false)
            );
        }
 
        $collection = match ($sort) {
            'price_low_high'  => $collection->sortBy(fn($o)     => (float) ($o['total_amount'] ?? 0)),
            'price_high_low'  => $collection->sortByDesc(fn($o) => (float) ($o['total_amount'] ?? 0)),
            'duration'        => $collection->sortBy(fn($o)     => $o['slices'][0]['duration'] ?? 0),
            'depart_earliest' => $collection->sortBy(fn($o)     => $o['slices'][0]['segments'][0]['departing_at'] ?? ''),
            'arrive_earliest' => $collection->sortBy(function ($o) {
                $segments = $o['slices'][0]['segments'] ?? [];
                return $segments[count($segments) - 1]['arriving_at'] ?? '';
            }),
            default => $collection,
        };
 
        return $collection->values()->toArray();
    }

    public function extractFilterMeta(array $offers): array
    {
        $prices = collect($offers)->map(fn($o) => (float) ($o['total_amount'] ?? 0))->filter();
 
        $airlines = collect($offers)
            ->map(fn($o) => $o['slices'][0]['segments'][0]['operating_carrier']['name'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();
 
        return [
            'min_price' => (int) ($prices->min() ?? 0),
            'max_price' => (int) ($prices->max() ?? 0),
            'airlines'  => $airlines,
        ];
    }
}