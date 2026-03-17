@props([
    'hotel' => [],
])
@php
    $accommodation = $hotel['accommodation'] ?? [];
    $photo         = $accommodation['photos'][0]['url'] ?? asset('images/hotel-1.jpg');
    $name          = $accommodation['name'] ?? 'Hotel';
    $city          = $accommodation['location']['address']['city_name'] ?? '';
    $country       = $accommodation['location']['address']['country_code'] ?? '';
    $stars         = (int) ($accommodation['rating'] ?? 0);
    $amenities     = $accommodation['amenities'] ?? [];
    $price         = $hotel['cheapest_rate_public_amount'] ?? 0;
    $currency      = $hotel['cheapest_rate_public_currency'] ?? 'USD';
    $resultId      = $hotel['id'] ?? '';
    $ratingColor = match(true) {
        $stars >= 5 => 'bg-green-600',
        $stars >= 3 => 'bg-orange-400',
        default     => 'bg-red-500',
    };    
    $amenityIcons = [
        'wifi'                   => ['icon' => 'wifi',            'label' => 'Free WiFi'],
        'pool'                   => ['icon' => 'pool',            'label' => 'Pool'],
        'spa'                    => ['icon' => 'sparkles',        'label' => 'Spa'],
        'gym'                    => ['icon' => 'barbell',         'label' => 'Gym'],
        'parking'                => ['icon' => 'car',             'label' => 'Parking'],
        'restaurant'             => ['icon' => 'tools-kitchen-2', 'label' => 'Restaurant'],
        'room_service'           => ['icon' => 'bell',            'label' => 'Room Service'],
        'lounge'                 => ['icon' => 'glass-full',      'label' => 'Lounge'],
        'business_centre'        => ['icon' => 'briefcase',       'label' => 'Business Centre'],
        'laundry'                => ['icon' => 'wash',            'label' => 'Laundry'],
        'concierge'              => ['icon' => 'user-check',      'label' => 'Concierge'],
        '24_hour_front_desk'     => ['icon' => 'clock-24',        'label' => '24hr Front Desk'],
        'cash_machine'           => ['icon' => 'cash',            'label' => 'ATM'],
        'childcare_service'      => ['icon' => 'baby-carriage',   'label' => 'Childcare'],
        'accessibility_mobility' => ['icon' => 'wheelchair',      'label' => 'Accessible'],
        'accessibility_hearing'  => ['icon' => 'ear',             'label' => 'Hearing Aid'],
    ];
    $showAmenities = array_slice($amenities, 0, 3);
@endphp
<div class="hotel-card card p-1 group cursor-pointer hover:shadow-xl transition-all duration-300 min-w-0">
    <div class="card-inner flex flex-col h-full">
        <div class="image-wrapper relative rounded-lg overflow-hidden h-[140px] sm:h-[180px] shrink-0">
            <img
                src="{{ $photo }}"
                alt="{{ $name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            >
        </div>
        <div class="content-wrapper flex flex-col p-2 sm:p-2.5 gap-2 sm:gap-4 grow min-w-0">
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex flex-col gap-1 min-w-0">
                        <h3 class="font-semibold text-base sm:text-lg text-slate-950 leading-tight truncate">
                            {{ $name }}
                        </h3>
                        <div class="flex items-center gap-1 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round"
                                style="display:inline-block;vertical-align:middle;stroke:currentcolor;flex-shrink:0">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/>
                                <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                            </svg>
                            <span class="font-normal text-xs sm:text-sm truncate">
                                {{ $city }}{{ $country ? ', ' . $country : '' }}
                            </span>
                        </div>
                    </div>
                    @if($stars > 0)
                        <div class="tag {{ $ratingColor }} text-white px-2 py-1 rounded-lg shrink-0 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"/>
                            </svg>
                            <span class="font-semibold text-sm">{{ $stars }}.0</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    @foreach($showAmenities as $amenity)
                        @php
                            $type     = $amenity['type'] ?? '';
                            $iconData = $amenityIcons[$type] ?? [
                                'icon'  => 'star',
                                'label' => $amenity['description'] ?? $type,
                            ];
                        @endphp
                        <div class="tag tag-gray">
                            <i data-tabler="{{ $iconData['icon'] }}" data-size="13"></i>
                            <span>{{ $iconData['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-between items-center mt-auto pt-1">
                <div class="flex flex-col">
                    <span class="font-normal text-xs text-slate-500">From</span>
                    <div class="flex items-baseline gap-0.5">
                        <span class="font-bold text-xl sm:text-2xl text-blue-600">
                            {{ $currency }} {{ number_format((float) $price, 0) }}
                        </span>
                        <span class="font-normal text-xs text-slate-500">/night</span>
                    </div>
                </div>
                <a href=""
                   class="btn btn-primary btn-sm px-4">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div>