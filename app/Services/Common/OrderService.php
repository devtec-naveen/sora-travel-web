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
        $totalAmount  = $baseAmount + $addonsAmount + $seatAmount + $platformFee;

        if ($totalAmount <= 0) {
            throw new \Exception('Invalid amount: ' . $totalAmount);
        }

        [$order, $payment] = DB::transaction(function () use ($data, $baseAmount, $addonsAmount, $seatAmount, $platformFee, $totalAmount, $currency) {
            $payment = PaymentModel::create([
                'user_id'        => $data['user_id'],
                'payment_id'     => 'PENDING-' . Str::uuid(),
                'payment_method' => 'stripe',
                'base_amount'    => $baseAmount,
                'platform_fee'   => $platformFee,
                'amount'         => $totalAmount,
                'currency'       => $currency,
                'status'         => 'pending',
            ]);

            $order = OrderModel::create([
                'user_id'       => $data['user_id'],
                'order_number'  => 'ORD-' . strtoupper(Str::random(10)),
                'payment_id'    => $payment->id,
                'base_amount'   => $baseAmount,
                'addons_amount' => $addonsAmount,
                'seat_amount'   => $seatAmount,
                'platform_fee'  => $platformFee,
                'total_amount'  => $totalAmount,
                'amount'        => $totalAmount,
                'currency'      => $currency,
                'status'        => 'pending',
                'payment_intent_id' => null,
            ]);

            return [$order, $payment];
        });

        $intent = $this->stripe->createPaymentIntent([
            'amount'     => $totalAmount,
            'currency'   => $currency,
            'payment_id' => $payment->id,
        ]);

        $order->update(['payment_intent_id' => $intent->id]);

        Log::info('Stripe PaymentIntent Created', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'intent' => $intent,
        ]);

        return [
            'order_id'      => $order->id,
            'payment_id'    => $payment->id,
            'client_secret' => $intent->client_secret,
        ];
    }

    public function confirmPayment($paymentId, $offerId, array $passengers)
    {
        Log::info('Confirm Payment Start', [
            'payment_id' => $paymentId,
            'offerId'    => $offerId,
            'passengers' => $passengers
        ]);

        if (!$offerId) {
            throw new \Exception('offer_id is missing');
        }

        $offerAmount = $this->duffel->getOfferAmount($offerId);

        return DB::transaction(function () use ($paymentId, $offerId, $passengers,$offerAmount) {
            $payment = PaymentModel::lockForUpdate()->findOrFail($paymentId);
            $order   = OrderModel::lockForUpdate()->where('payment_id', $payment->id)->firstOrFail();

            if ($order->status === 'confirmed') return $order;

            $formattedPassengers = array_map(function ($p) {
                $bornOn = $p['dob'] ?? $p['born_on'] ?? '';
                try { $bornOn = $bornOn ? \Carbon\Carbon::parse($bornOn)->format('Y-m-d') : ''; } catch (\Throwable) {}

                return [
                    'id'           => $p['id'] ?? null,                  
                    'title'        => strtolower($p['title'] ?? 'mr'),
                    'given_name'   => $p['given_name'] ?? $p['first_name'] ?? '',
                    'family_name'  => $p['family_name'] ?? $p['last_name'] ?? '',
                    'gender'       => match(strtolower($p['gender'] ?? 'm')) {
                                        'male','m' => 'm',
                                        'female','f' => 'f',
                                        default => 'm',
                                    },
                    'born_on'      => $bornOn,
                    'email'        => $p['email'] ?? $p['contact']['email'] ?? '',
                    'phone_number' => $p['phone_number'] ?? (($p['contact']['phone_code'] ?? '') . ($p['contact']['phone'] ?? '')),
                ];
            }, $passengers);

            $payload = [
                'data' => [
                    'type'            => 'instant',
                    'selected_offers' => [$offerId],
                    'passengers'      => $formattedPassengers,
                    'payments'        => [[
                        'type'     => 'balance',
                        'currency' => $order->currency,
                        'amount'   => $offerAmount,
                    ]],
                ],
            ];

            Log::info('Duffel Payload', $payload);

            /** @var \Illuminate\Http\Client\Response $response */
            $response = $this->duffel->createDuffelOrder($payload);
            /** @var array $orderData */
            $orderData = $response;

            Log::info('Duffel Response', $orderData);

            if (!empty($orderData['errors'])) {
                Log::error('Duffel confirmPayment failed', [
                    'payment_id' => $payment->id,
                    'errors'     => $orderData['errors'],
                ]);

                $intent = PaymentIntent::retrieve($order->payment_intent_id);

                if ($intent->status === 'succeeded') {
                    $intent->refunds->create([
                        'amount' => $intent->amount,
                    ]);
                    Log::info('Stripe Payment refunded due to booking failure', [
                        'payment_id' => $payment->id,
                        'intent_id'  => $intent->id,
                    ]);
                } elseif ($intent->status === 'requires_capture') {
                    $intent->cancel();
                    Log::info('Stripe PaymentIntent canceled due to booking failure', [
                        'payment_id' => $payment->id,
                        'intent_id'  => $intent->id,
                    ]);
                }

                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'failed']);

                return $orderData;
            }

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

            return $order;
        });
    }

    private function resolveBookingDate(array $orderData): ?string
    {
        $departing = $orderData['slices'][0]['segments'][0]['departing_at'] ?? null;
        return $departing ? date('Y-m-d', strtotime($departing)) : null;
    }
}