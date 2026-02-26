<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CmsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;


//==================================================== Front-End Routes ======================================= 

Route::get('/', function () {
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

Route::get('/run-migration', function () {
    $process = new Process(['php', 'artisan', 'migrate', '--force']);
    $process->setTimeout(null);
    $process->start();

    return response()->stream(function () use ($process) {
        foreach ($process as $type => $data) {
            echo nl2br($data);
            flush();
        }
    }, 200, [
        "Content-Type" => "text/html",
        "Cache-Control" => "no-cache",
        "X-Accel-Buffering" => "no" // nginx ke liye
    ]);
});
// Route::get('/flight-test', function () {

//     $baseUrl = config('services.amadeus.base_url');
//     $clientId = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     // ===================== 1️⃣ Get Access Token =====================
//     $tokenResponse = Http::asForm()->post($baseUrl . '/v1/security/oauth2/token', [
//         'grant_type' => 'client_credentials',
//         'client_id' => $clientId,
//         'client_secret' => $clientSecret,
//     ]);

//     if ($tokenResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Token generation failed',
//             'error' => $tokenResponse->json()
//         ], 500);
//     }

//     $accessToken = $tokenResponse->json()['access_token'];

//     // ===================== 2️⃣ Flight Search =====================
//     $flightResponse = Http::withToken($accessToken)
//         ->get($baseUrl . '/v2/shopping/flight-offers', [
//             'originLocationCode' => 'JAI',
//             'destinationLocationCode' => 'BOM',
//             'departureDate' => '2026-03-10',
//             'currencyCode' => 'INR',
//             'adults' => 1,
//             'max' => 5
//         ]);

//     if ($flightResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Flight search failed',
//             'error' => $flightResponse->json()
//         ], 500);
//     }

//     return response()->json([
//         'status' => true,
//         'data' => $flightResponse->json()
//     ]);
// });



// Route::get('/search-city', function (\Illuminate\Http\Request $request) {

//     $keyword = 'mumbai';

//     $baseUrl = config('services.amadeus.base_url');
//     $clientId = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     // 1️⃣ Get Token
//     $tokenResponse = Http::asForm()->post($baseUrl . '/v1/security/oauth2/token', [
//         'grant_type' => 'client_credentials',
//         'client_id' => $clientId,
//         'client_secret' => $clientSecret,
//     ]);

//     if ($tokenResponse->failed()) {
//         return response()->json($tokenResponse->json(), 500);
//     }

//     $accessToken = $tokenResponse->json()['access_token'];

//     // 2️⃣ Search Locations (CITY + AIRPORT)
//     $locationResponse = Http::withToken($accessToken)
//         ->get($baseUrl . '/v1/reference-data/locations', [
//             'subType' => 'AIRPORT',
//             'keyword' => $keyword,
//             'page[limit]' => 10
//         ]);

//     if ($locationResponse->failed()) {
//         return response()->json($locationResponse->json(), 500);
//     }

//     dd($locationResponse->json()['data']);

//     $results = collect($locationResponse->json()['data'])->map(function ($item) {
//         return [
//             'name' => $item['name'],
//             'iataCode' => $item['iataCode'],
//             'display' => $item['name'] . ' (' . $item['iataCode'] . ')'
//         ];
//     });

//     return response()->json($results);
// });


// Route::get('/flight-test', function (\Illuminate\Http\Request $request) {
//     $baseUrl = config('services.amadeus.base_url');
//     $clientId = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     $tokenResponse = Http::asForm()->post($baseUrl . '/v1/security/oauth2/token', [
//         'grant_type' => 'client_credentials',
//         'client_id' => $clientId,
//         'client_secret' => $clientSecret,
//     ]);

//     if ($tokenResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Token generation failed',
//             'error' => $tokenResponse->json()
//         ], 500);
//     }

//     $accessToken = $tokenResponse->json()['access_token'];


//     $searchResponse = Http::withToken($accessToken)
//         ->get($baseUrl . '/v2/shopping/flight-offers', [
//             'originLocationCode' => 'JAI',
//             'destinationLocationCode' => 'BOM',
//             'departureDate' => '2026-03-10',
//             'adults' => 1,
//             'currencyCode' => 'INR',
//             'max' => 5,
//         ]);

//     if ($searchResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Flight search failed',
//             'error' => $searchResponse->json()
//         ], 500);
//     }

//     $flightOffers = $searchResponse->json()['data'];

//     if (empty($flightOffers)) {
//         return response()->json([
//             'status' => false,
//             'message' => 'No flight offers found'
//         ], 404);
//     }

//     $selectedOffer = $flightOffers[0]; // Example: first flight

//     $pricingResponse = Http::withToken($accessToken)
//         ->post($baseUrl . '/v1/shopping/flight-offers/pricing', [
//             'data' => [
//                 'type' => 'flight-offers-pricing',
//                 'flightOffers' => [$selectedOffer]
//             ]
//         ]);


//     dd($pricingResponse->json()['data']);

//     if ($pricingResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Pricing API failed',
//             'error' => $pricingResponse->json()
//         ], 500);
//     }

//     $pricedData = $pricingResponse->json()['data']['flightOffers'][0];

//     $base = $pricedData['price']['base'];
//     $grandTotal = $pricedData['price']['grandTotal'];

//     // -----------------------------
//     // 6️⃣ Tax Calculation + Markup
//     // -----------------------------
//     $taxes = $grandTotal - $base;
//     $markup = 300; // aapka profit
//     $finalPrice = $grandTotal + $markup;

//     // -----------------------------
//     // 7️⃣ Return Combined Response
//     // -----------------------------
//     return response()->json([
//         'status' => true,
//         'search_offers_count' => count($flightOffers),
//         'selected_flight_offer' => $selectedOffer,
//         'pricing' => [
//             'base_fare' => $base,
//             'taxes' => $taxes,
//             'api_total' => $grandTotal,
//             'your_markup' => $markup,
//             'final_payable' => $finalPrice,
//         ],
//     ]);
// });


// Route::get('/search-hotel-city', function (\Illuminate\Http\Request $request) {

//     $keyword = $request->input('keyword', 'indore'); // default
//     $subType = $request->input('subType', 'HOTEL_LEISURE'); // Mandatory
//     $countryCode = $request->input('countryCode', 'IN');

//     $baseUrl = config('services.amadeus.base_url');
//     $clientId = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     // 1️⃣ Get Token
//     $tokenResponse = Http::asForm()->post($baseUrl . '/v1/security/oauth2/token', [
//         'grant_type' => 'client_credentials',
//         'client_id' => $clientId,
//         'client_secret' => $clientSecret,
//     ]);

//     if ($tokenResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Token generation failed',
//             'error' => $tokenResponse->json()
//         ], 500);
//     }

//     $accessToken = $tokenResponse->json()['access_token'];

//     // 2️⃣ Search Locations for Hotels
//     $locationResponse = Http::withToken($accessToken)
//         ->get($baseUrl . '/v1/reference-data/locations/hotel', [
//             'keyword' => strtoupper($keyword), // Amadeus recommends all caps
//             'subType' => $subType, // Must send
//             'countryCode' => $countryCode,
//             'max' => 10
//         ]);


//     dd($locationResponse->json()['data']);

//     if ($locationResponse->failed()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Hotel location search failed',
//             'error' => $locationResponse->json()
//         ], 500);
//     }

//     $results = collect($locationResponse->json()['data'])->map(function ($item) {
//         return [
//             'name' => $item['name'] ?? '',
//             'iataCode' => $item['iataCode'] ?? '',
//             'subType' => $item['subType'] ?? '',
//             'hotelIds' => $item['hotelIds'] ?? [],
//             'city' => $item['address']['cityName'] ?? '',
//             'country' => $item['address']['countryCode'] ?? '',
//             'geo' => $item['geoCode'] ?? '',
//             'display' => ($item['name'] ?? '') . ' (' . ($item['iataCode'] ?? '') . ')'
//         ];
//     });

//     return response()->json($results);
// });


// Route::get('/hotels/by-city', function (\Illuminate\Http\Request $request) {
//     $cityCode = $request->input('cityCode', 'IDR'); // e.g., BOM
//     $radius = $request->input('radius', 5);
//     $radiusUnit = $request->input('radiusUnit', 'KM');
//     // $radiusUnit = $request->input('hotelSource', 'ALL');

//     $baseUrl = config('services.amadeus.base_url');
//     $clientId = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     // Get token
//     $tokenResponse = Http::asForm()->post($baseUrl.'/v1/security/oauth2/token', [
//         'grant_type'=>'client_credentials',
//         'client_id'=>$clientId,
//         'client_secret'=>$clientSecret
//     ]);

//     $accessToken = $tokenResponse->json()['access_token'];

//     // Fetch hotels in city
//     $response = Http::withToken($accessToken)->get($baseUrl.'/v1/reference-data/locations/hotels/by-city', [
//         'cityCode'=>$cityCode,
//         'radius'=>$radius,
//         'radiusUnit'=>$radiusUnit,
//     ]);

//     dd($response->json()['data']);

//     return $response->json();
// });


// Route::get('/hotel/test', function () {

//     $baseUrl      = config('services.amadeus.base_url');
//     $clientId     = config('services.amadeus.client_id');
//     $clientSecret = config('services.amadeus.client_secret');

//     $tokenResponse = Http::asForm()->post($baseUrl.'/v1/security/oauth2/token', [
//         'grant_type'=>'client_credentials',
//         'client_id'=>$clientId,
//         'client_secret'=>$clientSecret
//     ]);

//     $accessToken = $tokenResponse->json()['access_token'];

//     $response = Http::withToken($accessToken)
//         ->get($baseUrl.'/v3/shopping/hotel-offers', [
//             'hotelIds'      => 'HSIDRAAI',
//             'checkInDate'   => now()->addDays(7)->format('Y-m-d'),
//             'checkOutDate'  => now()->addDays(8)->format('Y-m-d'),
//             'adults'        => 1,
//             'currency'      => 'INR',
//             'countryOfResidence' => 'IN',
//         ]);

//     return $response->json();
// });





//==================================================== Back-End Routes ======================================= 


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
        

        Route::get('/email-template', [CmsController::class, 'emailTemplate'])->name('emailTemplate'); 

        Route::get('/faq-category', [CmsController::class, 'faqCategoryList'])->name('faqCategoryList');
        Route::get('/faq-category/add', [CmsController::class, 'faqCategoryAdd'])->name('faqCategoryAdd');
        Route::get('/faq-category/view/{id}', [CmsController::class, 'faqCategoryView'])->name('faqCategoryView');
        Route::get('/faq-category/edit/{id}', [CmsController::class, 'faqCategoryEdit'])->name('faqCategoryEdit');



        Route::get('/faq', [CmsController::class, 'faqList'])->name('faqList');
        Route::get('/faq/add', [CmsController::class, 'addFaq'])->name('faqAdd');
        Route::get('/faq/view/{id}', [CmsController::class, 'viewFaq'])->name('faqView');
        Route::get('/faq/edit/{id}', [CmsController::class, 'editFaq'])->name('faqEdit');


    });
});
