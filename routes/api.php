<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\FlightController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
});

Route::controller(ContentController::class)->group(function () {
    Route::get('/special-offers', 'specialOffers');
    Route::get('/popular-destinations', 'popularDestinations');
});


Route::get('/airports/search', [AirportController::class, 'search']);
Route::post('/flights/search', [FlightController::class, 'search']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
