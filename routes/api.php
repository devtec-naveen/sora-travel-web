<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\MyAccountController;
use App\Http\Controllers\Api\MyBookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CardController;

Route::middleware('check.active')->group(function () {

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


    Route::prefix('hotels')->group(function () {
        Route::get('search', [HotelController::class, 'search']);
        Route::get('listing', [HotelController::class, 'listing']);
        Route::get('/detail/{accommodationId}', [HotelController::class, 'details']);
    });

    Route::get('/page/{slug}', [CmsController::class, 'show']);

    //================== Auth Sanctum ======================

    Route::middleware('auth:sanctum')->group(function () {        
        Route::put('/profile/update', [MyAccountController::class, 'updateProfile']);
        Route::get('/user', [MyAccountController::class, 'getUser']);
        Route::get('/notification-settings', [MyAccountController::class, 'getNotificationSettings']);
        Route::put('/notification-settings', [MyAccountController::class, 'updateNotificationSettings']);
        Route::delete('/account', [MyAccountController::class, 'deleteAccount']);

        Route::get('/mybooking', [MyBookingController::class, 'indexFlight']);
        Route::get('/mybooking/flight/{id}', [MyBookingController::class, 'viewFlight']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/payment/intent', [PaymentController::class, 'createPaymentIntent']);
        Route::post('/payments/confirm-and-book', [FlightController::class, 'confirmAndBook']);

        Route::prefix('addresses')->group(function () {
            Route::get('/list',       [MyAccountController::class, 'getAddresses']);
            Route::post('/store',      [MyAccountController::class, 'storeAddress']);
            Route::put('/{id}',   [MyAccountController::class, 'updateAddress']);
            Route::delete('/{id}',[MyAccountController::class, 'deleteAddress']);
        });

        Route::prefix('cards')->group(function () {
            Route::post('/store',             [CardController::class, 'store']);
            Route::get('/list',              [CardController::class, 'list']);
            Route::put('/{id}/default',  [CardController::class, 'setDefault']);
            Route::delete('/{id}',       [CardController::class, 'destroy']);
        });
    });
});
