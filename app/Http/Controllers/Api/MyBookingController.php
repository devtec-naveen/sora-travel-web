<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Services\Frontend\MyBookingService;     
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class MyBookingController extends Controller
{
    public function __construct(
        protected MyBookingService $bookingService
    ) {}

    public function index(Request $request)
    {
        try {
            $request->validate([
                'date_range' => 'nullable|string|in:7days,30days,3months',
            ]);

            $dateRange = $request->date_range ?? '';

            return response()->json([
                'success' => true,
                'data'    => [
                    'upcoming'  => $this->bookingService->getParsedOrders('flight', 'upcoming',  $dateRange),
                    'completed' => $this->bookingService->getParsedOrders('flight', 'completed', $dateRange),
                    'cancelled' => $this->bookingService->getParsedOrders('flight', 'cancelled', $dateRange),
                ],
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching bookings.',
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}