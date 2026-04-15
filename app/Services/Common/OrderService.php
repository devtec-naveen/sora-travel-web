<?php

namespace App\Services\Common;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Services\Common\Duffel\DuffelService;
use App\Services\Common\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;

class OrderService
{
    protected $duffel;
    protected $stripe;

    public function __construct(DuffelService $duffel, PaymentService $stripe)
    {
        $this->duffel  = $duffel;
        $this->stripe  = $stripe;
    }

    public function create(array $data)
    {
        $baseAmount   = (float) ($data['base_amount']  ?? 0);
        $currency     = $data['currency'] ?? 'usd';
        $addonsAmount = (float) ($data['addons_total'] ?? 0);
        $seatAmount   = (float) ($data['seat_total']   ?? 0);
        $platformFee  = (float) ($data['platform_fee'] ?? 0);
        $taxAmount    = (float) ($data['tax_amount']   ?? 0);

        $totalAmount = $baseAmount + $taxAmount + $addonsAmount + $seatAmount + $platformFee;

        if ($totalAmount <= 0) {
            throw new \Exception('Invalid amount: ' . $totalAmount);
        }

        [$order, $payment] = DB::transaction(function () use ($data, $baseAmount, $addonsAmount, $seatAmount, $platformFee, $totalAmount, $currency, $taxAmount) {
            $payment = PaymentModel::create([
                'user_id'        => $data['user_id'],
                'payment_id'     => 'PENDING-' . Str::uuid(),
                'payment_method' => 'stripe',
                'tax_amount'     => $taxAmount,
                'base_amount'    => $baseAmount,
                'platform_fee'   => $platformFee,
                'amount'         => $totalAmount,
                'currency'       => $currency,
                'status'         => 'pending',
            ]);

            $order = OrderModel::create([
                'user_id'           => $data['user_id'],
                'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                'payment_id'        => $payment->id,
                'base_amount'       => $baseAmount,
                'tax_amount'        => $taxAmount,
                'addons_amount'     => $addonsAmount,
                'seat_amount'       => $seatAmount,
                'platform_fee'      => $platformFee,
                'total_amount'      => $totalAmount,
                'amount'            => $totalAmount,
                'currency'          => $currency,
                'status'            => 'pending',
                'payment_intent_id' => null,
            ]);

            return [$order, $payment];
        });

        $intent = $this->stripe->createPaymentIntent([
            'amount'     => $totalAmount,
            'currency'   => $currency,
            'payment_id' => $payment->id,
            'card_number' => $data['card_number'] ?? null,
            'exp_month'   => $data['exp_month'] ?? null,
            'exp_year'    => $data['exp_year'] ?? null,
            'cvc'         => $data['cvc'] ?? null,
            'card_holder' => $data['card_holder'] ?? null,
        ]);

        $order->update(['payment_intent_id' => $intent->id]);

        Log::info('Stripe PaymentIntent Created', [
            'order_id'   => $order->id,
            'payment_id' => $payment->id,
            'intent'     => $intent,
        ]);

        return [
            'order_id'      => $order->id,
            'payment_id'    => $payment->id,
            'client_secret' => $intent->client_secret,
        ];
    }

    public function confirmPayment($paymentId, $offerId, array $passengers, array $services = [])
    {
        Log::info('>>> [STEP 1] confirmPayment called', [
            'payment_id'      => $paymentId,
            'offer_id'        => $offerId,
            'passenger_count' => count($passengers),
        ]);

        foreach ($passengers as $i => $p) {
            Log::info(">>> [STEP 1] RAW passenger [{$i}]", [
                'id'                  => $p['id']                  ?? 'MISSING',
                'type'                => $p['type']                ?? 'NOT SET',
                'born_on'             => $p['born_on']             ?? 'MISSING',
                'dob'                 => $p['dob']                 ?? 'NOT SET',
                'given_name'          => $p['given_name']          ?? $p['first_name'] ?? 'MISSING',
                'family_name'         => $p['family_name']         ?? $p['last_name']  ?? 'MISSING',
                'gender'              => $p['gender']              ?? 'MISSING',
                'email'               => $p['email']               ?? $p['contact']['email'] ?? 'MISSING',
                'phone_number'        => $p['phone_number']        ?? 'MISSING',
                'infant_passenger_id' => $p['infant_passenger_id'] ?? 'none',
                'ALL_KEYS'            => array_keys($p),
            ]);
        }

        if (!$offerId) {
            throw new \Exception('offer_id is missing');
        }

        return DB::transaction(function () use ($paymentId, $offerId, $passengers, $services) {
            $payment = PaymentModel::lockForUpdate()->findOrFail($paymentId);
            $order   = OrderModel::lockForUpdate()->where('payment_id', $payment->id)->firstOrFail();

            if ($order->status === 'confirmed') {
                Log::warning('>>> Order already confirmed', ['order_id' => $order->id]);
                return $order;
            }

            // Order ka actual total use karo — getOfferAmount() nahi
            $offerAmountStr = number_format(
                (float) $order->base_amount + (float) $order->tax_amount + (float) $order->seat_amount + (float) $order->addons_amount,
                2,
                '.',
                ''
            );
            $offerCurrency  = strtoupper($order->currency);

            Log::info('>>> [STEP 2] Using order total as Duffel payment amount', [
                'total_amount' => $offerAmountStr,
                'currency'     => $offerCurrency,
            ]);

            Log::info('>>> [STEP 3] Formatting passengers...');

            $formattedPassengers = array_map(function ($p, $index) {
                $bornOnRaw = $p['dob'] ?? $p['born_on'] ?? '';
                $bornOn    = '';

                try {
                    $bornOn = $bornOnRaw
                        ? \Carbon\Carbon::parse($bornOnRaw)->format('Y-m-d')
                        : '';
                } catch (\Throwable $e) {
                    Log::error(">>> [STEP 3] Passenger [{$index}] born_on PARSE ERROR", [
                        'raw'   => $bornOnRaw,
                        'error' => $e->getMessage(),
                    ]);
                }

                $calculatedAge  = null;
                $calculatedType = 'unknown';
                if ($bornOn) {
                    try {
                        $calculatedAge  = \Carbon\Carbon::parse($bornOn)->age;
                        $calculatedType = match (true) {
                            $calculatedAge < 2  => 'infant',
                            $calculatedAge < 12 => 'child',
                            default             => 'adult',
                        };
                    } catch (\Throwable) {
                    }
                }

                $formatted = [
                    'id'           => $p['id'] ?? null,
                    'title'        => strtolower($p['title'] ?? 'mr'),
                    'given_name'   => $p['given_name'] ?? $p['first_name'] ?? '',
                    'family_name'  => $p['family_name'] ?? $p['last_name'] ?? '',
                    'gender'       => match (strtolower($p['gender'] ?? 'm')) {
                        'male', 'm'   => 'm',
                        'female', 'f' => 'f',
                        default       => 'm',
                    },
                    'born_on'      => $bornOn,
                    'email'        => $p['email'] ?? $p['contact']['email'] ?? '',
                    'phone_number' => $p['phone_number']
                        ?? (($p['contact']['phone_code'] ?? '') . ($p['contact']['phone'] ?? '')),
                ];

                if (!empty($p['infant_passenger_id'])) {
                    $formatted['infant_passenger_id'] = $p['infant_passenger_id'];
                }

                Log::info(">>> [STEP 3] Passenger [{$index}] formatted", [
                    'passenger_id'      => $formatted['id'],
                    'born_on_raw'       => $bornOnRaw,
                    'born_on_final'     => $bornOn,
                    'born_on_empty'     => empty($bornOn)                   ? '*** EMPTY - WILL FAIL ***' : 'OK',
                    'id_empty'          => empty($formatted['id'])           ? '*** EMPTY - WILL FAIL ***' : 'OK',
                    'email_empty'       => empty($formatted['email'])        ? '*** EMPTY - WILL FAIL ***' : 'OK',
                    'phone_empty'       => empty($formatted['phone_number']) ? '*** EMPTY ***'             : 'OK',
                    'calculated_age'    => $calculatedAge,
                    'calculated_type'   => $calculatedType,
                    'has_infant_key'    => isset($p['infant_passenger_id']) ? 'YES → ' . $p['infant_passenger_id'] : 'no',
                    'formatted_payload' => $formatted,
                ]);

                return $formatted;
            }, $passengers, array_keys($passengers));

            $payload = [
                'data' => [
                    'type'            => 'instant',
                    'selected_offers' => [$offerId],
                    'passengers'      => $formattedPassengers,
                    'payments'        => [[
                        'type'     => 'balance',
                        'currency' => $offerCurrency,
                        'amount'   => $offerAmountStr,
                    ]],
                ],
            ];

            if (!empty($services)) {
                $payload['data']['services'] = array_map(fn($s) => [
                    'id'       => $s['service_id'] ?? $s['id'],
                    'quantity' => $s['quantity'] ?? 1,
                ], $services);
            }

            Log::info('>>> [STEP 4] FINAL payload to Duffel', [
                'payload' => $payload,
            ]);

            $bornOns = array_column($formattedPassengers, 'born_on');
            if (count($bornOns) !== count(array_unique($bornOns))) {
                Log::warning('>>> [STEP 4] *** WARNING: Multiple passengers share the same born_on ***', [
                    'born_ons' => $bornOns,
                ]);
            }

            Log::info('>>> [STEP 5] Calling Duffel createDuffelOrder...');

            /** @var array $orderData */
            $orderData = $this->duffel->createDuffelOrder($payload);

            Log::info('>>> [STEP 5] Duffel raw response', ['response' => $orderData]);

            if (!empty($orderData['errors'])) {
                Log::error('>>> [STEP 6] Duffel FAILED', [
                    'errors'          => $orderData['errors'],
                    'passengers_sent' => $formattedPassengers,
                ]);

                $intent = PaymentIntent::retrieve($order->payment_intent_id);

                Log::info('>>> [STEP 6] Stripe intent at failure time', [
                    'intent_id'     => $intent->id,
                    'intent_status' => $intent->status,
                ]);

                if ($intent->status === 'succeeded') {
                    $intent->refunds->create(['amount' => $intent->amount]);
                    Log::info('>>> [STEP 6] Stripe refunded', ['intent_id' => $intent->id]);
                } elseif ($intent->status === 'requires_capture') {
                    $intent->cancel();
                    Log::info('>>> [STEP 6] Stripe intent canceled', ['intent_id' => $intent->id]);
                }

                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'failed']);

                return $orderData;
            }

            Log::info('>>> [STEP 7] Duffel order SUCCESS', ['duffel_order_id' => $orderData['id']]);

            DB::transaction(function () use ($payment, $order, $orderData) {
                $payment->update([
                    'status'           => 'completed',
                    'paid_at'          => now(),
                    'payment_id'       => $orderData['id'],
                    'gateway_response' => $orderData,
                ]);

                $order->update([
                    'status'       => 'confirmed',
                    'external_id'  => $orderData['id'],
                    'booking_date' => $this->resolveBookingDate($orderData),
                    'data'         => $orderData,
                ]);
            });

            Log::info('>>> [STEP 7] Order confirmed in DB', [
                'order_id'        => $order->id,
                'duffel_order_id' => $orderData['id'],
            ]);

            session(['last_order' => $orderData]);
            return $order;
        });
    }

    private function resolveBookingDate(array $orderData): ?string
    {
        $departing = $orderData['slices'][0]['segments'][0]['departing_at'] ?? null;
        return $departing ? date('Y-m-d', strtotime($departing)) : null;
    }
}
