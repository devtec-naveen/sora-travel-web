<?php

namespace App\Services\Common\Stripe;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    public function payWithCard(array $data): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        return PaymentIntent::create([
            'amount' => (int) ($data['amount'] * 100),
            'currency' => strtolower($data['currency'] ?? 'usd'),
            'payment_method' => 'pm_card_visa',
            'confirm' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);
    }
}
