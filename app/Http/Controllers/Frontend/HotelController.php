<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Common\Duffel\AuthService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function suggest(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);
 
        $response = $this->authService
            ->client()
            ->get($this->authService->baseUrl() . '/places/suggestions', [
                'query' => $request->input('keyword'),
            ]);
 
        if ($response->failed()) {
            return response()->json(['data' => []], 500);
        }
 
        $countryNames = [
            'IN' => 'India',          'US' => 'United States',   'GB' => 'United Kingdom',
            'AE' => 'UAE',            'TH' => 'Thailand',        'SG' => 'Singapore',
            'FR' => 'France',         'DE' => 'Germany',         'JP' => 'Japan',
            'AU' => 'Australia',      'CA' => 'Canada',          'IT' => 'Italy',
            'ES' => 'Spain',          'NL' => 'Netherlands',     'CH' => 'Switzerland',
            'AT' => 'Austria',        'SE' => 'Sweden',          'NO' => 'Norway',
            'DK' => 'Denmark',        'FI' => 'Finland',         'PT' => 'Portugal',
            'BE' => 'Belgium',        'PL' => 'Poland',          'CZ' => 'Czech Republic',
            'HU' => 'Hungary',        'GR' => 'Greece',          'TR' => 'Turkey',
            'RU' => 'Russia',         'CN' => 'China',           'HK' => 'Hong Kong',
            'TW' => 'Taiwan',         'KR' => 'South Korea',     'MY' => 'Malaysia',
            'ID' => 'Indonesia',      'PH' => 'Philippines',     'VN' => 'Vietnam',
            'NZ' => 'New Zealand',    'ZA' => 'South Africa',    'EG' => 'Egypt',
            'MA' => 'Morocco',        'KE' => 'Kenya',           'NG' => 'Nigeria',
            'SA' => 'Saudi Arabia',   'QA' => 'Qatar',           'KW' => 'Kuwait',
            'BH' => 'Bahrain',        'OM' => 'Oman',            'JO' => 'Jordan',
            'LB' => 'Lebanon',        'IL' => 'Israel',          'PK' => 'Pakistan',
            'BD' => 'Bangladesh',     'LK' => 'Sri Lanka',       'NP' => 'Nepal',
            'MV' => 'Maldives',       'MM' => 'Myanmar',         'KH' => 'Cambodia',
            'MX' => 'Mexico',         'BR' => 'Brazil',          'AR' => 'Argentina',
            'CL' => 'Chile',          'CO' => 'Colombia',        'PE' => 'Peru',
        ];
 
        // Duffel sirf airport type deta hai — city_name + iata_city_code se
        // unique cities nikaalte hain
        $data['data'] = collect($response->json('data') ?? [])
            ->filter(fn($place) =>
                !empty($place['iata_city_code']) &&
                !empty($place['city_name'])
            )
            ->unique('iata_city_code')        // ek city ek baar
            ->map(fn($place) => [
                'code'      => $place['iata_city_code'],
                'city'      => $place['city_name'],
                'name'      => 'Hotels in ' . $place['city_name'],
                'country'   => $countryNames[$place['iata_country_code'] ?? ''] ?? ($place['iata_country_code'] ?? ''),
                'latitude'  => $place['latitude']          ?? null,
                'longitude' => $place['longitude']         ?? null,
            ])
            ->values()
            ->all();
 
        return response()->json($data);
    }

    public function search(Request $request)
    {
        return view('hotel.listing');

        // $guests = collect(range(1, $request->integer('adults', 1)))
        //     ->map(fn() => ['type' => 'adult'])
        //     ->merge(
        //         $request->integer('children', 0) > 0
        //             ? collect(range(1, $request->integer('children')))
        //                 ->map(fn() => ['type' => 'child', 'age' => 10])
        //             : []
        //     )
        //     ->values()
        //     ->all();
    
        // // Step 1: Search create karo
        // $response = $this->authService
        //     ->client()
        //     ->post($this->authService->baseUrl() . '/stays/search', [
        //         'data' => [
        //             'check_in_date'  => $request->input('check_in'),
        //             'check_out_date' => $request->input('check_out'),
        //             'rooms'          => $request->integer('rooms', 1),
        //             'guests'         => $guests,
        //             'location'       => [
        //                 'radius'                 => $request->integer('radius', 10),
        //                 'geographic_coordinates' => [
        //                     'latitude'  => (float) $request->input('latitude'),
        //                     'longitude' => (float) $request->input('longitude'),
        //                 ],
        //             ],
        //         ],
        //     ]);
    
        //     dd($response->json());
        // if ($response->failed()) {
        //     $error = $response->json()['errors'][0]['message'] ?? 'Search failed';
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => $error], 422);
        //     }
        //     return back()->withErrors(['search' => $error]);
        // }
    
        // $searchId = $response->json('data.id');
    
        // if (!$searchId) {
        //     return back()->withErrors(['search' => 'Could not initiate search']);
        // }
    
        // // Step 2: Poll karo jab tak status = "complete"
        // $results = [];
        // for ($i = 0; $i < 10; $i++) {
        //     sleep(1);
    
        //     $poll = $this->authService
        //         ->client()
        //         ->get($this->authService->baseUrl() . '/stays/searches/' . $searchId);
    
        //     if ($poll->failed()) break;
    
        //     $pollData = $poll->json('data');
    
        //     if (($pollData['status'] ?? '') === 'complete') {
        //         $results = $pollData['results'] ?? [];
        //         break;
        //     }
        // }
    
        // // Step 3: Results map karo
        // $hotels = collect($results)
        //     ->map(fn($result) => [
        //         'id'           => $result['accommodation']['id']                               ?? null,
        //         'name'         => $result['accommodation']['name']                             ?? '',
        //         'description'  => $result['accommodation']['description']                     ?? '',
        //         'rating'       => $result['accommodation']['rating']                           ?? null,
        //         'review_score' => $result['accommodation']['review_score']                    ?? null,
        //         'review_count' => $result['accommodation']['review_count']                    ?? null,
        //         'photo'        => $result['accommodation']['photos'][0]['url']                 ?? null,
        //         'location'     => [
        //             'address'   => $result['accommodation']['location']['address']             ?? '',
        //             'city'      => $result['accommodation']['location']['city_name']           ?? '',
        //             'latitude'  => $result['accommodation']['location']['geographic_coordinates']['latitude']  ?? null,
        //             'longitude' => $result['accommodation']['location']['geographic_coordinates']['longitude'] ?? null,
        //         ],
        //         'cheapest_rate' => [
        //             'amount'   => $result['cheapest_rate_total_amount'] ?? null,
        //             'currency' => $result['cheapest_rate_currency']     ?? 'INR',
        //         ],
        //         'amenities' => collect($result['accommodation']['amenities'] ?? [])
        //             ->pluck('type')
        //             ->take(6)
        //             ->values()
        //             ->all(),
        //     ])
        //     ->values()
        //     ->all();
    
        // if ($request->expectsJson()) {
        //     return response()->json(['data' => $hotels]);
        // }
    
        // return view('frontend.hotels.index', [
        //     'hotels'       => $hotels,
        //     'searchParams' => $request->only([
        //         'city', 'latitude', 'longitude',
        //         'check_in', 'check_out', 'adults', 'children', 'rooms', 'radius'
        //     ]),
        // ]);
    }

    /**
     * Hotel detail + rates
     * GET /hotels/{id}?check_in=...&check_out=...&adults=1
     */
    public function show(Request $request, string $id)
    {
        $request->validate([
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults'    => 'required|integer|min:1',
        ]);

        $guests = collect(range(1, $request->integer('adults', 1)))
            ->map(fn() => ['type' => 'adult'])
            ->values()
            ->all();

        $response = $this->authService
            ->client()
            ->post($this->authService->baseUrl() . '/stays/search', [
                'data' => [
                    'check_in_date'  => $request->input('check_in'),
                    'check_out_date' => $request->input('check_out'),
                    'guests'         => $guests,
                    'accommodation'  => ['id' => $id],
                ],
            ]);

        $result = collect($response['results'] ?? [])->first();

        if (! $result) {
            abort(404, 'Hotel not found or unavailable for selected dates.');
        }

        $hotel = $result['accommodation'];

        return view('frontend.hotels.show', [
            'hotel'        => $hotel,
            'rooms'        => $hotel['rooms']        ?? [],
            'searchParams' => $request->only(['check_in', 'check_out', 'adults']),
        ]);
    }
}