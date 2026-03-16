<?php

namespace App\Livewire\Frontend\Hotel;

use Livewire\Component;
use App\Services\Common\Duffel\AuthService;
use Illuminate\Support\Facades\Http;

class Listing extends Component
{
    public $city;
    public $latitude;
    public $longitude;
    public $checkIn;
    public $checkOut;
    public $adults   = 1;
    public $children = 0;
    public $rooms    = 1;
    public $radius   = 10;
    public $sortBy   = '';
    public $hotels   = [];
    public $total    = 0;

    protected $queryString = [
        'city',
        'latitude',
        'longitude',
        'checkIn'   => ['as' => 'check_in'],
        'checkOut'  => ['as' => 'check_out'],
        'adults',
        'children',
        'rooms',
        'radius',
    ];

    protected AuthService $authService;

    public function mount(AuthService $authService)
    {
        $this->authService = $authService;

        // URL params se fill karo
        $this->city      = request('city',      $this->city);
        $this->latitude  = request('latitude',  $this->latitude);
        $this->longitude = request('longitude', $this->longitude);
        $this->checkIn   = request('check_in',  $this->checkIn);
        $this->checkOut  = request('check_out', $this->checkOut);
        $this->adults    = request('adults',    $this->adults);
        $this->children  = request('children',  $this->children);
        $this->rooms     = request('rooms',     $this->rooms);

        $this->loadHotels();
    }

    public function updatedSortBy()
    {
        $this->sortHotels();
    }

    public function loadHotels()
    {
        if (!$this->latitude || !$this->longitude || !$this->checkIn || !$this->checkOut) {
            return;
        }

        $authService = $this->authService ?? app(AuthService::class);

        $guests = collect(range(1, max(1, (int) $this->adults)))
            ->map(fn() => ['type' => 'adult'])
            ->merge(
                (int) $this->children > 0
                    ? collect(range(1, (int) $this->children))
                        ->map(fn() => ['type' => 'child', 'age' => 10])
                    : []
            )
            ->values()
            ->all();

        // Step 1: Search create
        $response = $authService
            ->client()
            ->post('https://api.duffel.com/stays/searches', [
                'data' => [
                    'check_in_date'  => $this->checkIn,
                    'check_out_date' => $this->checkOut,
                    'rooms'          => (int) $this->rooms,
                    'guests'         => $guests,
                    'location'       => [
                        'radius'                 => (int) $this->radius,
                        'geographic_coordinates' => [
                            'latitude'  => (float) $this->latitude,
                            'longitude' => (float) $this->longitude,
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            \Log::error('Duffel hotel search failed', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);
            $this->hotels = collect([]);
            $this->total  = 0;
            return;
        }

        $searchId = $response->json('data.id');
        if (!$searchId) return;

        // Step 2: Poll results
        $results = [];
        for ($i = 0; $i < 10; $i++) {
            sleep(1);

            $poll = $authService
                ->client()
                ->get('https://api.duffel.com/stays/searches/' . $searchId);

            if ($poll->failed()) break;

            $pollData = $poll->json('data');

            if (($pollData['status'] ?? '') === 'complete') {
                $results = $pollData['results'] ?? [];
                break;
            }
        }

        // Step 3: Map results
        $hotels = collect($results)->map(fn($result) => [
            'id'           => $result['accommodation']['id']                                              ?? null,
            'name'         => $result['accommodation']['name']                                            ?? '',
            'description'  => $result['accommodation']['description']                                    ?? '',
            'rating'       => $result['accommodation']['rating']                                          ?? null,
            'review_score' => $result['accommodation']['review_score']                                   ?? null,
            'review_count' => $result['accommodation']['review_count']                                   ?? null,
            'photo'        => $result['accommodation']['photos'][0]['url']                                ?? null,
            'location'     => [
                'address'   => $result['accommodation']['location']['address']                            ?? '',
                'city'      => $result['accommodation']['location']['city_name']                          ?? '',
                'latitude'  => $result['accommodation']['location']['geographic_coordinates']['latitude']  ?? null,
                'longitude' => $result['accommodation']['location']['geographic_coordinates']['longitude'] ?? null,
            ],
            'cheapest_rate' => [
                'amount'   => $result['cheapest_rate_total_amount'] ?? null,
                'currency' => $result['cheapest_rate_currency']     ?? 'INR',
            ],
            'amenities' => collect($result['accommodation']['amenities'] ?? [])
                ->pluck('type')
                ->take(6)
                ->values()
                ->all(),
        ]);

        $this->hotels = $this->sortBy ? $this->applySortBy($hotels) : $hotels;
        $this->total  = $this->hotels->count();
    }

    private function sortHotels()
    {
        $this->hotels = $this->applySortBy(collect($this->hotels));
    }

    private function applySortBy($hotels)
    {
        return match($this->sortBy) {
            'price_low'    => $hotels->sortBy(fn($h)    => $h['cheapest_rate']['amount'] ?? 0)->values(),
            'price_high'   => $hotels->sortByDesc(fn($h) => $h['cheapest_rate']['amount'] ?? 0)->values(),
            'rating_high'  => $hotels->sortByDesc(fn($h) => $h['rating'] ?? 0)->values(),
            'review_high'  => $hotels->sortByDesc(fn($h) => $h['review_score'] ?? 0)->values(),
            default        => $hotels->values(),
        };
    }

    public function render()
    {
        return view('livewire.frontend.hotel.listing', [
            'hotels' => $this->hotels,
            'total'  => $this->total,
        ]);
    }
}