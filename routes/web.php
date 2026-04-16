<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\AirportController;
use App\Http\Controllers\Frontend\HotelController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\CmsController as FrontendCmsController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BookingController as BackendBookingController;
use App\Http\Controllers\Backend\CmsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PopularDestinationController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SpecialOffersController;
use App\Http\Middleware\BookingSessionMiddleware;
use App\Http\Middleware\CheckUserActive;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::middleware([CheckUserActive::class])->group(function () {

    // Public
    Route::get('/', [FrontendCmsController::class, 'homePage'])->name('home');
    Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout');
    Route::get('/flight-search', [AirportController::class, 'index'])->name('front.flightSearch');
    Route::get('/hotels-search',       [HotelController::class, 'index'])->name('front.hotelsSearch');
    Route::get('/hotels/suggestions',  [HotelController::class, 'suggest'])->name('hotels.suggestions');
    Route::get('/hotels/details/{id}', [HotelController::class, 'details'])->name('hotels.details');

    // Protected
    Route::middleware(['user.auth'])->group(function () {
        Route::prefix('my-account')->group(function () {
            Route::get('/personal-information', fn() => view('myaccount.personal-information'))->name('my-account.personal-information');
            Route::get('/notification-preferences', fn() => view('myaccount.notification-preferences'))->name('my-account.notification-preferences');
        });
        Route::get('/my-booking',             [BookingController::class, 'myBooking'])->name('my-booking');
        Route::get('/my-booking/flight/{id}', [BookingController::class, 'myFlightViewBooking'])->name('booking.flight.show');
    });


    Route::middleware([BookingSessionMiddleware::class])->group(function () {
        Route::get('/airport-search', [AirportController::class, 'search'])->name('airport.search');
        Route::get('/passengers',     [AirportController::class, 'passengers'])->name('airport.passengers');
        Route::get('/addon',          [AirportController::class, 'addon'])->name('airport.addon');
        Route::get('/seats',          [AirportController::class, 'seats'])->name('airport.seats');
        Route::get('/review',         [AirportController::class, 'review'])->name('airport.review');
        Route::get('/payment',        [AirportController::class, 'payment'])->name('airport.payment');
        Route::get('/confirmation',   [AirportController::class, 'confirmation'])->name('airport.confirmation');
    });
});

/*
|--------------------------------------------------------------------------
| Utility
|--------------------------------------------------------------------------
*/

Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return 'All caches cleared successfully!';
});

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',   [AuthController::class, 'index'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {

        Route::get('/dashboard',       [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile',         [ProfileController::class, 'index'])->name('profile');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');

        Route::get('/users',           [UserController::class, 'index'])->name('users');
        Route::get('/users/view/{id}', [UserController::class, 'view'])->name('userView');

        Route::controller(CmsController::class)->group(function () {
            Route::prefix('email-template')->name('emailTemplate')->group(function () {
                Route::get('/',          'emailTemplate')->name('');
                Route::get('/view/{id}', 'viewEmailTemplate')->name('View');
            });
            Route::prefix('faq-category')->name('faqCategory')->group(function () {
                Route::get('/',          'faqCategoryList')->name('List');
                Route::get('/add',       'faqCategoryAdd')->name('Add');
                Route::get('/view/{id}', 'faqCategoryView')->name('View');
                Route::get('/edit/{id}', 'faqCategoryEdit')->name('Edit');
            });
            Route::prefix('faq')->name('faq')->group(function () {
                Route::get('/',          'faqList')->name('List');
                Route::get('/add',       'addFaq')->name('Add');
                Route::get('/view/{id}', 'viewFaq')->name('View');
                Route::get('/edit/{id}', 'editFaq')->name('Edit');
            });
            Route::prefix('pages')->name('pages')->group(function () {
                Route::get('/',          'pagesList')->name('List');
                Route::get('/view/{id}', 'viewPages')->name('View');
                Route::get('/edit/{id}', 'editPages')->name('Edit');
            });
            Route::get('/global-settings', 'globalSettingList')->name('globalSettingList');
        });

        Route::resource('special-offers', SpecialOffersController::class)->names([
            'index'  => 'offersList',
            'create' => 'offersAdd',
            'store'  => 'offersStore',
            'edit'   => 'offersEdit',
            'show'   => 'offersView',
        ]);

        Route::resource('popular-destinations', PopularDestinationController::class)->names([
            'index'  => 'destinationsList',
            'create' => 'destinationsAdd',
            'store'  => 'destinationsStore',
            'edit'   => 'destinationsEdit',
            'show'   => 'destinationsView',
        ]);

        Route::get('booking/flight', [BackendBookingController::class, 'flightIndex'])->name('booking.flight');
        Route::get('booking/flight/view/{id}', [BackendBookingController::class, 'flightView'])->name('booking.flight.view');
    });
});

/*
|--------------------------------------------------------------------------
| Dynamic CMS Pages
|--------------------------------------------------------------------------
*/

Route::get('/{slug}', [FrontendCmsController::class, 'show'])->name('page.show');
