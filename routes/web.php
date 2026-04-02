<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CmsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PopularDestinationController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SpecialOffersController;
use App\Http\Controllers\Frontend\AirportController;
use App\Http\Controllers\Frontend\HotelController;
use App\Http\Middleware\BookingSessionMiddleware;
use App\Http\Controllers\frontend\BookingController;
use Illuminate\Support\Facades\Artisan;

//==================================================== Front Routes ======================================= 

Route::get('/', function () {
    return view('index');
})->name('home');

//================================= Auth Routes ================================= 

Route::post('/logout', [App\Http\Controllers\Frontend\AuthController::class, 'logout'])->name('logout');



//================================= Flight and Airports Routes ================================= 

Route::get('/flight-search', [AirportController::class, 'index'])->name('front.flightSearch');
Route::middleware([BookingSessionMiddleware::class])->group(function () {
    Route::get('/airport-search', [AirportController::class, 'search'])->name('airport.search');
    Route::get('/passengers',     [AirportController::class, 'passengers'])->name('airport.passengers');
    Route::get('/addon',          [AirportController::class, 'addon'])->name('airport.addon');
    Route::get('/seats',          [AirportController::class, 'seats'])->name('airport.seats');
    Route::get('/review',         [AirportController::class, 'review'])->name('airport.review');
    Route::get('/payment',        [AirportController::class, 'payment'])->name('airport.payment');
    Route::get('/confirmation',        [AirportController::class, 'confirmation'])->name('airport.confirmation');
});

//================================= Hotels Routes ================================= 

Route::get('/hotels-search', [HotelController::class, 'index'])->name('front.hotelsSearch');
Route::get('/hotels/suggestions', [HotelController::class, 'suggest'])->name('hotels.suggestions');
Route::get('/hotels/details/{id}', [HotelController::class, 'details'])->name('hotels.details');


//================================= Frontend Protected Routes ================================= 


Route::prefix('my-account')->group(function () {
    Route::get('/personal-information', function () {
        return view('myaccount.personal-information');
    })->name('my-account.personal-information');
});


Route::middleware(['user.auth'])->group(function () {
    Route::get('/my-booking', [BookingController::class, 'myBooking'])->name('my-booking');
    Route::get('/my-booking/flight/{id}', [BookingController::class, 'myFlightViewBooking'])->name('booking.flight.show');
});

//================================= Frontend Protected Routes End ================================= 




//================================= Migration Routes ============================================== 


Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "All caches cleared successfully!";
});

//==================================================== Front End Routes ======================================= 




//==================================================== Back-End Routes ======================================= 


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {

        /*========== Auth Profile Users and Dashboard ============*/
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/view/{id}', [UserController::class, 'view'])->name('userView');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');

        /*========== CMS ============*/
        Route::get('/email-template', [CmsController::class, 'emailTemplate'])->name('emailTemplate');
        Route::get('/email-template/view/{id}', [CmsController::class, 'viewEmailTemplate'])->name('emailTemplateView');
        Route::get('/faq-category', [CmsController::class, 'faqCategoryList'])->name('faqCategoryList');
        Route::get('/faq-category/add', [CmsController::class, 'faqCategoryAdd'])->name('faqCategoryAdd');
        Route::get('/faq-category/view/{id}', [CmsController::class, 'faqCategoryView'])->name('faqCategoryView');
        Route::get('/faq-category/edit/{id}', [CmsController::class, 'faqCategoryEdit'])->name('faqCategoryEdit');
        Route::get('/faq', [CmsController::class, 'faqList'])->name('faqList');
        Route::get('/faq/add', [CmsController::class, 'addFaq'])->name('faqAdd');
        Route::get('/faq/view/{id}', [CmsController::class, 'viewFaq'])->name('faqView');
        Route::get('/faq/edit/{id}', [CmsController::class, 'editFaq'])->name('faqEdit');
        Route::get('/pages', [CmsController::class, 'pagesList'])->name('pagesList');
        Route::get('/pages/view/{id}', [CmsController::class, 'viewPages'])->name('pagesView');
        Route::get('/pages/edit/{id}', [CmsController::class, 'editPages'])->name('pagesEdit');
        Route::get('/global-settings', [CmsController::class, 'globalSettingList'])->name('globalSettingList');

        /*========== Special Offers ============*/
        Route::resource('special-offers', SpecialOffersController::class)->names([
            'index' => 'offersList',
            'create' => 'offersAdd',
            'store' => 'offersStore',
            'edit' => 'offersEdit',
            'show' => 'offersView',
        ]);

        /*========== Popular Destinations ============*/
        Route::resource('popular-destinations', PopularDestinationController::class)->names([
            'index'   => 'destinationsList',
            'create'  => 'destinationsAdd',
            'store'   => 'destinationsStore',
            'edit'    => 'destinationsEdit',
            'show'    => 'destinationsView',
        ]);
    });
});
