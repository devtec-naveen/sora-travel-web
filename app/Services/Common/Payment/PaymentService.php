<?php

namespace App\Services\Common\Payment;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function createPaymentIntent(array $data): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        return PaymentIntent::create([
            'amount' => (int) ($data['amount'] * 100),
            'currency' => strtolower($data['currency'] ?? 'usd'),
            'metadata' => [
                'payment_id' => $data['payment_id'] ?? null,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);
    }
}
