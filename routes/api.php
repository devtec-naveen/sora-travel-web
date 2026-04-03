<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\MyBookingController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/send-otp', 'sendRegisterOtp');
    Route::post('/verify-otp', 'verifyAndRegister');
    Route::post('/login', 'login');
    Route::post('/forgot-password',   'forgotPassword');
    Route::post('/verify-forgot-otp', 'verifyForgotOtp');
    Route::post('/reset-password',    'resetPasswordWithOtp');
});

Route::controller(ContentController::class)->group(function () {
    Route::get('/special-offers', 'specialOffers');
    Route::get('/popular-destinations', 'popularDestinations');
});


Route::get('/airports/search', [AirportController::class, 'search']);
Route::post('/flights/search', [FlightController::class, 'listing']);
Route::post('/flights/seats', [FlightController::class, 'seats']);
Route::post('/flights/addons',  [FlightController::class, 'addons']);
Route::post('/flights/order', [FlightController::class, 'createOrder']);



Route::prefix('hotels')->group(function () {
    Route::get('search', [HotelController::class, 'search']);
    Route::get('listing', [HotelController::class, 'listing']);
    Route::get('/detail/{accommodationId}', [HotelController::class, 'details']);
});






Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mybooking', [MyBookingController::class, 'indexFlight']);
    Route::get('/mybooking/flight/{id}', [MyBookingController::class, 'viewFlight']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
