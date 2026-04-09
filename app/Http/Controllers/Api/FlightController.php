<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\Duffel\DuffelService;
use App\Services\Common\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class FlightController extends Controller
{
    public function __construct(
    protected DuffelService $duffelService,
    protected OrderService  $orderService,
    ) {}

    public function listing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'origin'        => 'required|string',
                'destination'   => 'required|string',
                'departureDate' => 'required|date',
                'returnDate'    => 'nullable|date',
                'adults'        => 'nullable|integer|min:1',
                'childrens'      => 'nullable|integer|min:0',
                'infants'       => 'nullable|integer|min:0',
                'cabin'         => 'nullable|string',
                'max_price'     => 'nullable|numeric',
                'stops'         => 'nullable|array',
                'stops.*'       => 'integer|in:0,1,2',
                'airlines'      => 'nullable|array',
                'airlines.*'    => 'string',
                'refundable'    => 'nullable|boolean',
                'sort'          => 'nullable|string|in:price_low_high,price_high_low,duration,depart_earliest,arrive_earliest',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->searchFlightsMain($request->all());
            $offers = $result['offers'] ?? [];

            $meta = $this->duffelService->extractFilterMeta($offers);

            $filteredOffers = $this->duffelService->filterAndSort($offers, [
                'max_price'  => $request->input('max_price', PHP_INT_MAX),
                'stops'      => $request->input('stops', []),
                'airlines'   => $request->input('airlines', []),
                'refundable' => $request->boolean('refundable', false),
                'sort'       => $request->input('sort', ''),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Flights fetched successfully',
                'meta'    => $meta,
                'data'    => $filteredOffers,
            ], config('constant.httpCode.SUCCESS_OK'));
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Flight search failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function seats(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->getSeatMaps($request->input('offer_id'));

            if ($result['error']) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['error'],
                ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
            }

            return response()->json([
                'status'  => true,
                'message' => 'Seat maps fetched successfully',
                'data'    => $result['seat_maps'],
            ], config('constant.httpCode.SUCCESS_OK'));
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch seat maps',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function addons(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->getOfferWithServices($request->input('offer_id'));

            if ($result['error']) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['error'],
                ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
            }

            $services = collect($result['services'])->map(function ($svc) {
                $meta = $svc['metadata'] ?? [];
                return [
                    'id'               => $svc['id'],
                    'type'             => $svc['type'],
                    'total_amount'     => $svc['total_amount'],
                    'total_currency'   => $svc['total_currency'],
                    'maximum_quantity' => $svc['maximum_quantity'] ?? 1,
                    'passenger_ids'    => $svc['passenger_ids']    ?? [],
                    'segment_ids'      => $svc['segment_ids']      ?? [],
                    'metadata'         => [
                        'baggage_type'      => $meta['baggage_type']      ?? null,
                        'maximum_weight_kg' => $meta['maximum_weight_kg'] ?? null,
                        'maximum_length_cm' => $meta['maximum_length_cm'] ?? null,
                    ],
                ];
            })->values();

            return response()->json([
                'status'  => true,
                'message' => 'Addon services fetched successfully',
                'data'    => [
                    'offer_id'   => $result['offer']['id'] ?? $request->input('offer_id'),
                    'passengers' => collect($result['offer']['passengers'] ?? [])->map(fn($p) => [
                        'id'   => $p['id'],
                        'type' => $p['type'],
                    ]),
                    'services'   => $services,
                ],
            ], config('constant.httpCode.SUCCESS_OK'));
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch addon services',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function confirmAndBook(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_intent_id'                                          => ['required', 'string'],
                'offer_id'                                                   => ['required', 'string'],
                'passengers'                                                 => ['required', 'array', 'min:1'],
                'passengers.*.id'                                            => ['required', 'string'],
                'passengers.*.title'                                         => ['required', 'string', 'in:mr,ms,mrs,miss,dr'],
                'passengers.*.given_name'                                    => ['required', 'string'],
                'passengers.*.family_name'                                   => ['required', 'string'],
                'passengers.*.gender'                                        => ['required', 'string', 'in:m,f'],
                'passengers.*.born_on'                                       => ['required', 'date_format:Y-m-d'],
                'passengers.*.email'                                         => ['required', 'email'],
                'passengers.*.phone_number'                                  => ['required', 'string'],
                'passengers.*.identity_documents'                            => ['nullable', 'array'],
                'passengers.*.identity_documents.*.unique_identifier'        => ['required_with:passengers.*.identity_documents', 'string'],
                'passengers.*.identity_documents.*.expires_on'              => ['required_with:passengers.*.identity_documents', 'date_format:Y-m-d', 'after:today'],
                'passengers.*.identity_documents.*.issuing_country_code'     => ['required_with:passengers.*.identity_documents', 'string', 'size:2'],
                'passengers.*.identity_documents.*.type'                     => ['required_with:passengers.*.identity_documents', 'string', 'in:passport,tax_id'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $intent = \Stripe\PaymentIntent::retrieve($request->input('payment_intent_id'));

            if ($intent->status !== 'succeeded') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment not completed. Intent status: ' . $intent->status,
                ], 422);
            }

            $paymentId = $request->input('payment_id');

            if (!$paymentId) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment record not found.',
                ], 422);
            }

            $passengers = collect($request->input('passengers'))
                ->map(function ($pax) {
                    $pax['born_on'] = Carbon::parse($pax['born_on'])->format('Y-m-d');
                    $pax['title']   = strtolower($pax['title']);
                    $pax['gender']  = strtolower($pax['gender']);

                    if (!empty($pax['identity_documents'])) {
                        $pax['identity_documents'] = collect($pax['identity_documents'])
                            ->map(function ($doc) {
                                $doc['expires_on']           = Carbon::parse($doc['expires_on'])->format('Y-m-d');
                                $doc['issuing_country_code'] = strtoupper($doc['issuing_country_code']);
                                return $doc;
                            })->toArray();
                    }

                    return $pax;
                })->toArray();

            $order = $this->orderService->confirmPayment(
                $paymentId,
                $request->input('offer_id'),
                $passengers,
            );

            if (is_array($order) && !empty($order['errors'])) {
                $error = $order['errors'][0] ?? [];
                return response()->json([
                    'status'  => false,
                    'message' => $error['message'] ?? 'Booking failed.',
                    'code'    => $error['code']    ?? null,
                ], 422);
            }

            return response()->json([
                'status'            => true,
                'message'           => 'Booking confirmed successfully',
                'order_id'          => $order->id,
                'order_number'      => $order->order_number,
                'booking_reference' => $order->data['booking_reference'] ?? null,
                'total_amount'      => $order->total_amount,
                'currency'          => $order->currency,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('[confirmAndBook] EXCEPTION', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function refund(string $intentId): \Stripe\Refund
    {
        return \Stripe\Refund::create([
            'payment_intent' => $intentId,
        ]);
    }
}
