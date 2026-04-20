<?php

namespace App\Services\Common;

use App\Jobs\SendEmail;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Repositories\Common\Auth\AuthRepository;
use App\Services\Common\Duffel\DuffelService;
use App\Services\Common\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;

class OrderService
{
    public function __construct(protected AuthRepository $authRepo, protected DuffelService $duffel, protected PaymentService $stripe)
    {
        $this->duffel  = $duffel;
        $this->stripe  = $stripe;
        $this->authRepo  = $authRepo;
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
        if (!$offerId) {
            throw new \Exception('offer_id is missing');
        }

        $order = DB::transaction(function () use ($paymentId, $offerId, $passengers, $services) {
            $payment = PaymentModel::lockForUpdate()->findOrFail($paymentId);
            $order   = OrderModel::lockForUpdate()->where('payment_id', $payment->id)->firstOrFail();

            if ($order->status === 'confirmed') {
                return $order;
            }

            $offerAmountStr = number_format(
                (float) $order->base_amount + (float) $order->tax_amount + (float) $order->seat_amount + (float) $order->addons_amount,
                2, '.', ''
            );
            $offerCurrency = strtoupper($order->currency);

            $formattedPassengers = array_map(function ($p, $index) {
                $bornOnRaw = $p['dob'] ?? $p['born_on'] ?? '';
                $bornOn    = '';

                try {
                    $bornOn = $bornOnRaw ? \Carbon\Carbon::parse($bornOnRaw)->format('Y-m-d') : '';
                } catch (\Throwable $e) {
                    Log::error("Passenger [{$index}] born_on parse error", ['raw' => $bornOnRaw, 'error' => $e->getMessage()]);
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

            $orderData = $this->duffel->createDuffelOrder($payload);

            if (!empty($orderData['errors'])) {
                Log::error('Duffel order failed', ['errors' => $orderData['errors']]);

                $intent = PaymentIntent::retrieve($order->payment_intent_id);

                if ($intent->status === 'succeeded') {
                    $intent->refunds->create(['amount' => $intent->amount]);
                } elseif ($intent->status === 'requires_capture') {
                    $intent->cancel();
                }

                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'failed']);

                return $orderData;
            }

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

            $order->refresh();

            session(['last_order' => $orderData]);

            return $order;
        });

        try {
            $emailTemplate = $this->authRepo->findEmailTemplate('booking-confirmation');

            if ($emailTemplate) {
                $user = getUser($order->user_id, ['id', 'name', 'email']);

                SendEmail::dispatch(
                    $user->email,
                    str_replace(
                        ['{booking_type}', '{order_number}', '{app_name}'],
                        [ucfirst($order->type), $order->order_number, config('app.name')],
                        $emailTemplate->subject
                    ),
                    $emailTemplate->body,
                    [
                        'name'            => ucfirst($user->name),
                        'app_name'        => config('app.name'),
                        'order_number'    => $order->order_number,
                        'booking_type'    => ucfirst($order->type),
                        'booking_date'    => $order->booking_date
                            ? \Carbon\Carbon::parse($order->booking_date)->format('d M Y, h:i A')
                            : now()->format('d M Y, h:i A'),
                        'expires_at'      => $order->expires_at
                            ? \Carbon\Carbon::parse($order->expires_at)->format('d M Y, h:i A')
                            : 'N/A',
                        'external_id'     => $order->external_id,
                        'base_amount'     => number_format($order->base_amount, 2),
                        'seat_amount'     => number_format($order->seat_amount, 2),
                        'addons_amount'   => number_format($order->addons_amount, 2),
                        'platform_fee'    => number_format($order->platform_fee, 2),
                        'tax_amount'      => number_format($order->tax_amount, 2),
                        'discount_amount' => number_format($order->discount_amount, 2),
                        'total_amount'    => number_format($order->total_amount, 2),
                        'currency'        => strtoupper($order->currency),
                        'booking_url'     => config('app.url') . '/my-bookings/' . $order->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Booking confirmation email failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        return $order;
    }

    private function resolveBookingDate(array $orderData): ?string
    {
        $departing = $orderData['slices'][0]['segments'][0]['departing_at'] ?? null;
        return $departing ? date('Y-m-d', strtotime($departing)) : null;
    }
}
