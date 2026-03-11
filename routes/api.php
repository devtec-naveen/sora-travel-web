<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
});

Route::controller(ContentController::class)->group(function () {
    Route::get('/special-offers', 'specialOffers');
    Route::get('/popular-destinations', 'popularDestinations');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
