<?php

namespace App\Services\Common;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use App\Models\User;
use App\Models\UserCardModel;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /*
    |--------------------------------------------------------------------------
    | GET OR CREATE CUSTOMER
    |--------------------------------------------------------------------------
    */
    public function getCustomer(User $user): string
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

    /*
    |--------------------------------------------------------------------------
    | SAVE CARD
    |--------------------------------------------------------------------------
    */
    public function saveCard(User $user, string $paymentMethodId): UserCardModel
    {
        $customerId = $this->getCustomer($user);

        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

        $paymentMethod->attach([
            'customer' => $customerId
        ]);

        // Optional: set as default if first card
        $isFirstCard = UserCardModel::where('user_id', $user->id)->count() === 0;

        if ($isFirstCard) {
            $this->setDefaultCard($user, $paymentMethodId);
        }

        return UserCardModel::create([
            'user_id' => $user->id,
            'stripe_payment_method_id' => $paymentMethodId,
            'brand' => $paymentMethod->card->brand ?? null,
            'last_four' => $paymentMethod->card->last4 ?? null,
            'exp_month' => $paymentMethod->card->exp_month ?? null,
            'exp_year' => $paymentMethod->card->exp_year ?? null,
            'is_default' => $isFirstCard,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST CARDS
    |--------------------------------------------------------------------------
    */
    public function listCards(User $user)
    {
        return UserCardModel::where('user_id', $user->id)->get();
    }

    /*
    |--------------------------------------------------------------------------
    | SET DEFAULT CARD
    |--------------------------------------------------------------------------
    */
    public function setDefaultCard(User $user, string $paymentMethodId): bool
    {
        $customerId = $this->getCustomer($user);

        Customer::update($customerId, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId
            ]
        ]);

        UserCardModel::where('user_id', $user->id)
            ->update(['is_default' => false]);

        UserCardModel::where('stripe_payment_method_id', $paymentMethodId)
            ->update(['is_default' => true]);

        $user->update([
            'default_payment_method_id' => $paymentMethodId
        ]);

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE CARD
    |--------------------------------------------------------------------------
    */
    public function deleteCard(User $user, string $paymentMethodId): bool
    {
        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->detach();

        $card = UserCardModel::where('user_id', $user->id)
            ->where('stripe_payment_method_id', $paymentMethodId)
            ->first();

        if ($card && $card->is_default) {
            $user->update([
                'default_payment_method_id' => null
            ]);
        }

        $card?->delete();

        return true;
    }
}