<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Artisan;


//==================================================== Front-End Routes ======================================= 

Route::get('/',function(){
     return view('index');
})->name('home');

Route::get('/clear-all', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return "All caches cleared successfully!";
});

















//==================================================== Back-End Routes ======================================= 


Route::prefix('admin')->name('admin.')->group( function(){
    Route::get('/login',[AuthController::class,'index'])->name('login');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    

    Route::middleware('auth.admin')->group(function() {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/users',[UserController::class,'index'])->name('users');
    });

});
