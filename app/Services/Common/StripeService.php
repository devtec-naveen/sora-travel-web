<?php

namespace App\Services\Stripe;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use App\Models\UserCard;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function getCustomer($user)
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->update([
            'stripe_customer_id' => $customer->id
        ]);

        return $customer->id;
    }

    // SAVE CARD
    public function saveCard($user, $paymentMethodId)
    {
        $customerId = $this->getCustomer($user);

        $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach([
            'customer' => $customerId
        ]);

        // Save Database
        // return UserCard::create([
        //     'user_id' => $user->id,
        //     'stripe_payment_method_id' => $paymentMethodId,
        //     'brand' => $paymentMethod->card->brand,
        //     'last_four' => $paymentMethod->card->last4,
        //     'exp_month' => $paymentMethod->card->exp_month,
        //     'exp_year' => $paymentMethod->card->exp_year,
        // ]);
    }

    // SET DEFAULT CARD
    public function setDefaultCard($user, $paymentMethodId)
    {
        $customerId = $this->getCustomer($user);

        Customer::update($customerId, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId
            ]
        ]);

        // Update local DB
        // UserCard::where('user_id', $user->id)->update(['is_default' => false]);
        // UserCard::where('stripe_payment_method_id', $paymentMethodId)->update(['is_default' => true]);

        $user->update([
            'default_payment_method_id' => $paymentMethodId
        ]);

        return true;
    }

    // DELETE CARD
    public function deleteCard($user, $paymentMethodId)
    {
        $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->detach();
        // UserCard::where('stripe_payment_method_id', $paymentMethodId)->delete();

        return true;
    }

    // LIST CARDS
    public function listCards($user)
    {
        // return UserCard::where('user_id', $user->id)->get();
    }
}
