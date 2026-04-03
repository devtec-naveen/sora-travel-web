<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Services\Common\MyBookingService;     
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class MyBookingController extends Controller
{
    public function __construct(
        protected MyBookingService $bookingService
    ) {}

    public function indexFlight(Request $request)
    {
        try {
            $request->validate([
                'date_range' => 'nullable|string|in:7days,30days,3months',
            ]);

            $dateRange = $request->date_range ?? '';

            return response()->json([
                'success' => true,
                'data'    => [
                    'upcoming'  => $this->bookingService->getParsedOrders('flight', 'upcoming',  $dateRange),
                    'completed' => $this->bookingService->getParsedOrders('flight', 'completed', $dateRange),
                    'cancelled' => $this->bookingService->getParsedOrders('flight', 'cancelled', $dateRange),
                ],
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching bookings.',
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function viewFlight(string $id)
    {
        try {
            $result = $this->bookingService->getOrderDetail($id);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found.',
                ], config('constant.httpCode.NOT_FOUND'));
            }

            $order    = $result['order'];
            $services = $result['services'] ?? [];

            $orderData   = is_array($order->data) ? $order->data : json_decode($order->data, true);
            $baseFare    = (float) ($orderData['base_amount']  ?? 0);
            $taxAmount   = (float) ($orderData['tax_amount']   ?? $order->tax_amount ?? 0);
            $grandTotal  = (float) ($orderData['total_amount'] ?? $order->amount);
            $currency    = $orderData['total_currency']        ?? $order->currency;

            $pricing = [
                'currency'       => $currency,
                'base_fare'      => round($baseFare, 2),
                'tax_amount'     => round($taxAmount, 2),
                'services_total' => round(collect($services)->sum(fn($s) => (float) ($s['amount'] ?? 0)), 2),
                'grand_total'    => round($grandTotal, 2),
                'breakdown'      => [
                    'baggage' => collect($services)
                        ->where('type', 'baggage')
                        ->map(fn($s) => [
                            'label'    => 'Extra Baggage' . ($s['weight_kg'] ? ' (' . $s['weight_kg'] . 'kg)' : ''),
                            'quantity' => (int) ($s['quantity'] ?? 1),
                            'amount'   => round((float) ($s['amount'] ?? 0), 2),
                            'currency' => $s['currency'] ?? $currency,
                        ])->values(),

                    'seats' => collect($services)
                        ->where('type', 'seat')
                        ->map(fn($s) => [
                            'label'    => 'Seat Selection',
                            'quantity' => (int) ($s['quantity'] ?? 1),
                            'amount'   => round((float) ($s['amount'] ?? 0), 2),
                            'currency' => $s['currency'] ?? $currency,
                        ])->values(),

                    'others' => collect($services)
                        ->whereNotIn('type', ['baggage', 'seat'])
                        ->map(fn($s) => [
                            'label'    => ucfirst($s['type'] ?? 'Add-on'),
                            'quantity' => (int) ($s['quantity'] ?? 1),
                            'amount'   => round((float) ($s['amount'] ?? 0), 2),
                            'currency' => $s['currency'] ?? $currency,
                        ])->values(),
                ],
            ];

            $parsed = $result['parsed'];

            return response()->json([
                'success' => true,
                'data'    => [
                    'order' => [
                        'id'               => $order->id,
                        'order_number'     => $order->order_number,
                        'external_id'      => $order->external_id,
                        'booking_reference'=> $parsed['booking_reference'] ?? null,
                        'status'           => $order->status,
                        'type'             => $order->type,
                        'booking_date'     => $order->booking_date?->toDateString(),
                        'created_at'       => $order->created_at?->toIso8601String(),
                    ],
                    'flags'      => $result['flags'],
                    'flight'     => [
                        'carrier'         => $parsed['carrier']         ?? null,
                        'flight_number'   => $parsed['flight_number']   ?? null,
                        'aircraft'        => $parsed['aircraft']        ?? null,
                        'origin'          => $parsed['origin']          ?? null,
                        'destination'     => $parsed['destination']     ?? null,
                        'departing_at'    => $parsed['dep_at']?->toIso8601String(),
                        'arriving_at'     => $parsed['arr_at']?->toIso8601String(),
                        'duration'        => $parsed['duration']        ?? null,
                        'stop_label'      => $parsed['stop_label']      ?? null,
                        'cabin_class'     => $parsed['cabin_class']     ?? null,
                        'fare_brand'      => $parsed['fare_brand']      ?? null,
                        'baggages'        => $parsed['baggages']        ?? [],
                        'origin_terminal' => $parsed['origin_terminal'] ?? null,
                        'dest_terminal'   => $parsed['dest_terminal']   ?? null,
                    ],
                    'passengers' => $result['passengers'],
                    'contact'    => $result['contact'],
                    'conditions' => $result['conditions'],
                    'services'   => $services,
                    'pricing'    => $pricing,
                ],
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching booking detail.',
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

}