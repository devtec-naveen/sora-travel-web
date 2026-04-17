<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCardModel;
use App\Services\Common\StripeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class CardController extends Controller
{
    use ApiResponse;

    public function __construct(protected StripeService $stripeService) {}

    public function list(): JsonResponse
    {
        try {
            $cards = $this->stripeService->listCards(Auth::user());

            return $this->success('Cards fetched successfully.', $cards, 200);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'payment_method_id' => ['required', 'string', 'starts_with:pm_'],
            ], [
                'payment_method_id.required'    => 'Payment method ID is required.',
                'payment_method_id.starts_with' => 'Invalid payment method ID.',
            ]);

            $alreadyExists = UserCardModel::where('user_id', Auth::id())
                ->where('stripe_payment_method_id', $request->payment_method_id)
                ->exists();

            if ($alreadyExists) {
                return $this->error('This card is already saved to your account.', 422);
            }

            $card = $this->stripeService->saveCard(Auth::user(), $request->payment_method_id);

            return $this->success('Card saved successfully.', $card, 201);

        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function setDefault(int $id): JsonResponse
    {
        try {
            $card = UserCardModel::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (! $card) {
                return $this->error('Card not found.', 404);
            }

            $this->stripeService->setDefaultCard(Auth::user(), $card->stripe_payment_method_id);

            return $this->success('Default card updated successfully.', [], 200);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }


    public function destroy(int $id): JsonResponse
    {
        try {
            $card = UserCardModel::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();

            if (! $card) {
                return $this->error('Card not found.', 404);
            }

            $this->stripeService->deleteCard(Auth::user(), $card->stripe_payment_method_id);

            return $this->success('Card removed successfully.', [], 200);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
