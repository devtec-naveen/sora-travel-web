<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount'     => ['required', 'numeric', 'min:0.01'],
            'currency'   => ['nullable', 'string', 'size:3'],
            'payment_id' => ['nullable', 'string'],
        ], [
            'amount.required' => 'Amount is required.',
            'amount.numeric'  => 'Amount must be a number.',
            'amount.min'      => 'Amount must be at least 0.01.',
            'currency.size'   => 'Currency must be a 3-letter code (e.g. usd).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $intent = $this->paymentService->createPaymentIntent($validator->validated());

            return response()->json([
                'success'       => true,
                'client_secret' => $intent->client_secret,
                'intent_id'     => $intent->id,
                'status'        => $intent->status,
            ], 201);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment intent creation failed.',
            ], 500);
        }
    }
}
