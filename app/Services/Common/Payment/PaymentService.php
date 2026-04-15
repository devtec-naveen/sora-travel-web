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
            'amount'   => (int) ($data['amount'] * 100),
            'currency' => strtolower($data['currency'] ?? 'usd'),
            'payment_method' => 'pm_card_visa',
            // 'payment_method_data' => [
            //     'type' => 'card',
            //     'card' => [
            //         'number'    => $data['card_number'],
            //         'exp_month' => (int) $data['exp_month'],
            //         'exp_year'  => (int) $data['exp_year'],
            //         'cvc'       => $data['cvc'],
            //     ],
            //     'billing_details' => [
            //         'name' => $data['card_holder'],
            //     ],
            // ],
            'metadata' => [
                'payment_id' => $data['payment_id'] ?? null,
            ],
            'automatic_payment_methods' => [
                'enabled'         => true,
                'allow_redirects' => 'never',
            ],
        ]);
    }
}