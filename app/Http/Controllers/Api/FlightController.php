<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Refund;
use Stripe\Stripe;

class FlightController extends Controller
{
    public function __construct(protected DuffelService $duffelService) {}

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

    public function createOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id'                                               => 'required|string',
                'passengers'                                             => 'required|array|min:1',
                'passengers.*.id'                                        => 'required|string',
                'passengers.*.title'                                     => 'required|string|in:mr,ms,mrs,miss,dr',
                'passengers.*.given_name'                                => 'required|string',
                'passengers.*.family_name'                               => 'required|string',
                'passengers.*.gender'                                    => 'required|string|in:m,f',
                'passengers.*.born_on'                                   => 'required|date_format:Y-m-d',
                'passengers.*.email'                                     => 'required|email',
                'passengers.*.phone_number'                              => 'required|string',
                'passengers.*.identity_documents'                        => 'nullable|array',
                'passengers.*.identity_documents.*.unique_identifier'    => 'required_with:passengers.*.identity_documents|string',
                'passengers.*.identity_documents.*.expires_on'          => 'required_with:passengers.*.identity_documents|date_format:Y-m-d',
                'passengers.*.identity_documents.*.issuing_country_code' => 'required_with:passengers.*.identity_documents|string|size:2',
                'passengers.*.identity_documents.*.type'                 => 'required_with:passengers.*.identity_documents|string|in:passport,tax_id',

                'services'            => 'nullable|array',
                'services.*.id'       => 'required_with:services|string',
                'services.*.quantity' => 'nullable|integer|min:1',

                'seats'               => 'nullable|array',
                'seats.*.service_id'  => 'required_with:seats|string',
                'seats.*.quantity'    => 'nullable|integer|min:1',

                'currency'            => 'required|string|size:3',
                'card.card_number'    => ['required', 'regex:/^(\d{4}\s?){3,4}\d{1,4}$/'],
                'card.expiry'         => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'card.cvc'            => 'required|digits_between:3,4',
                'card.card_holder'    => 'required|string|min:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            [$month, $year] = explode('/', $request->input('card.expiry'));
            $month = (int) $month;
            $year  = (int) $year;

            if ($year < (int) date('y') || ($year === (int) date('y') && $month < (int) date('m'))) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Card expiry date is invalid or expired.'
                ], 422);
            }

            // ─── Passengers normalize ──────────────────────────────────────
            $passengers = collect($request->input('passengers'))->map(function ($pax) {
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

            $currency = strtoupper($request->input('currency'));

            $offerResult = $this->duffelService->getOfferWithServices($request->input('offer_id'));

            if (!empty($offerResult['error']) || empty($offerResult['offer'])) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Could not verify offer. Please try again.',
                ], 422);
            }

            $offerData     = $offerResult['offer'];
            $offerAmount   = (float) ($offerData['total_amount']   ?? 0);
            $offerCurrency = $offerData['total_currency'] ?? $currency;

            if (!$offerAmount) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Could not verify offer amount. Please try again.',
                ], 422);
            }

            $requestedServices  = $request->input('services', []);
            $baggageAmount      = 0.0;

            if (!empty($requestedServices)) {
                $availableServices = $offerResult['services'] ?? [];
                foreach ($requestedServices as $svc) {
                    $svcId     = $svc['id'] ?? null;
                    $available = $availableServices[$svcId] ?? null;
                    if ($available) {
                        $qty            = (int) ($svc['quantity'] ?? 1);
                        $baggageAmount += (float) ($available['total_amount'] ?? 0) * $qty;
                    }
                }
            }

            $requestedSeats = $request->input('seats', []);
            $seatsAmount    = 0.0;

            if (!empty($requestedSeats)) {
                $availableSeats = collect($offerResult['seats'] ?? [])->keyBy('service_id');
                foreach ($requestedSeats as $seat) {
                    $serviceId      = $seat['service_id'] ?? null;
                    $available      = $availableSeats[$serviceId] ?? null;
                    if ($available) {
                        $qty         = (int) ($seat['quantity'] ?? 1);
                        $seatsAmount += (float) ($available['amount'] ?? 0) * $qty;
                    }
                }
            }

            $allServices = [];

            foreach ($requestedServices as $svc) {
                if (!empty($svc['id'])) {
                    $allServices[] = [
                        'id'       => $svc['id'],
                        'quantity' => (int) ($svc['quantity'] ?? 1),
                    ];
                }
            }

            foreach ($requestedSeats as $seat) {
                if (!empty($seat['service_id'])) {
                    $allServices[] = [
                        'id'       => $seat['service_id'],
                        'quantity' => (int) ($seat['quantity'] ?? 1),
                    ];
                }
            }

            $servicesAmount = round($baggageAmount + $seatsAmount, 2);
            $actualTotal    = round($offerAmount + $servicesAmount, 2);

            Log::info('createOrder amount verification', [
                'offer_amount'    => $offerAmount,
                'baggage_amount'  => $baggageAmount,
                'seats_amount'    => $seatsAmount,
                'services_total'  => $servicesAmount,
                'actual_total'    => $actualTotal,
                'client_sent'     => $request->input('amount'),
            ]);

            $stripeService = app(\App\Services\Common\Stripe\StripeService::class);
            $paymentIntent = $stripeService->payWithCard([
                'amount'      => $actualTotal,
                'currency'    => $offerCurrency,
                'card_number' => str_replace(' ', '', $request->input('card.card_number')),
                'exp_month'   => $month,
                'exp_year'    => 2000 + $year,
                'cvc'         => $request->input('card.cvc'),
                'card_holder' => $request->input('card.card_holder'),
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment could not be processed.'
                ], 400);
            }

            $response = $this->duffelService->createOrder([
                'offer_id'         => $request->input('offer_id'),
                'user_id'          => $request->input('user_id'),
                'passengers'       => $passengers,
                'services'         => $allServices,        
                'services_amount'  => $servicesAmount,
                'currency'         => $offerCurrency,
                'stripe_intent_id' => $paymentIntent->id,
            ]);

            if (!empty($response['errors'])) {
                Stripe::setApiKey(config('services.stripe.secret'));
                Refund::create(['payment_intent' => $paymentIntent->id]);

                Log::error('Duffel failed after Stripe charge — refund initiated', [
                    'stripe_intent_id' => $paymentIntent->id,
                    'errors'           => $response['errors'],
                ]);

                $error = $response['errors'][0] ?? [];
                return response()->json([
                    'status'  => false,
                    'message' => $error['message'] ?? 'Order creation failed. Payment refunded.',
                    'code'    => $error['code']    ?? null,
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return response()->json([
                'status'  => true,
                'message' => 'Order created successfully',
                'data'    => $response['data'] ?? [],
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Throwable $e) {
            Log::error('createOrder controller exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Order creation failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function refund(string $intentId): \Stripe\Refund
    {
        return \Stripe\Refund::create([
            'payment_intent' => $intentId,
        ]);
    }
}
