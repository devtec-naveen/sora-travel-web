<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\OrderService;
use App\Services\Common\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected OrderService $orderService
    ) {}

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'offer_id'    => ['required', 'string'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'currency'    => ['required', 'string', 'size:3'],
            'addons_total'=> ['nullable', 'numeric', 'min:0'],
            'seat_total'  => ['nullable', 'numeric', 'min:0'],
        ], [
            'offer_id.required' => 'Offer ID is required.',
            'amount.required'   => 'Amount is required.',
            'amount.numeric'    => 'Amount must be a number.',
            'amount.min'        => 'Amount must be at least 0.01.',
            'currency.required' => 'Currency is required.',
            'currency.size'     => 'Currency must be a 3-letter code (e.g. USD).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $baseAmount   = (float) $request->input('amount');
            $addonsTotal  = (float) $request->input('addons_total', 0);
            $seatTotal    = (float) $request->input('seat_total', 0);
            $taxAmount    = (float) $request->input('tax_amount', 0);
            $platformFee       = (float) $request->input('platform_fee', 0);

            $result = $this->orderService->create([
                'user_id'      => Auth::id(),
                'base_amount'  => $baseAmount,
                'addons_total' => $addonsTotal,
                'seat_total'   => $seatTotal,
                'platform_fee' => $platformFee,
                'tax_amount' => $taxAmount,
                'currency'     => strtoupper($request->input('currency')),
            ]);

            return response()->json([
                'success'       => true,
                'client_secret' => $result['client_secret'],
                'intent_id'     => $result['client_secret'],
                'payment_id'    => $result['payment_id'],
                'order_id'      => $result['order_id'],
                'platform_fee'  => $platformFee,
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
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
