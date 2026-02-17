<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;


//==================================================== Front-End Routes ======================================= 

Route::get('/',function(){
     return view('index');
})->name('home');

















//==================================================== Back-End Routes ======================================= 


Route::prefix('admin')->name('admin.')->group( function(){
    Route::get('/login',[AuthController::class,'index'])->name('login');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');



    Route::middleware('auth.admin')->group(function() {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/users',[DashboardController::class,'index'])->name('users');
    });

});
