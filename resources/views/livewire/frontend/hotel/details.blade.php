<main class="bg-slate-50">
    <div id="lightbox"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.90); 
    align-items:center; justify-content:center; z-index:9999; overflow:hidden;">

        <button id="lb-close"
            style="position:absolute; top:16px; right:16px; background:rgba(255,255,255,0.12); 
        border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:50%; width:44px; height:44px; 
        font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:10;">
            &#10005;
        </button>

        <button id="lb-prev"
            style="position:absolute; left:16px; top:50%; transform:translateY(-50%); 
        background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.25); color:#fff; 
        border-radius:50%; width:48px; height:48px; font-size:24px; cursor:pointer; 
        display:flex; align-items:center; justify-content:center; z-index:10;">
            &#8249;
        </button>

        {{-- Wrapper: desktop 50% width, mobile almost full screen --}}
        <div id="lb-wrapper" class="lb-wrapper-box"
            style="position:relative; border-radius:24px; overflow:hidden; background:#111;">
            <img id="lb-img"
                style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; border-radius:24px;"
                src="" alt="">
        </div>

        <button id="lb-next"
            style="position:absolute; right:16px; top:50%; transform:translateY(-50%); 
        background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.25); color:#fff; 
        border-radius:50%; width:48px; height:48px; font-size:24px; cursor:pointer; 
        display:flex; align-items:center; justify-content:center; z-index:10;">
            &#8250;
        </button>

        <div id="lb-counter"
            style="position:absolute; bottom:18px; background:rgba(0,0,0,0.45); 
        color:rgba(255,255,255,0.75); font-size:13px; padding:4px 14px; border-radius:20px;">
        </div>
    </div>
    <div class="bg-slate-100 py-3 border-b border-slate-200/60">
        <div class="container">
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}"
                    class="font-normal text-sm text-slate-500 hover:text-blue-600 transition-colors">Home</a>
                <i data-tabler="chevron-right" class="text-slate-500" data-size="14" data-stroke="2"></i>
                <a href="javascript:history.back()"
                    class="font-normal text-sm text-slate-500 hover:text-blue-600 transition-colors">Hotel List</a>
                <i data-tabler="chevron-right" class="text-slate-500" data-size="14" data-stroke="2"></i>
                <span class="font-semibold text-sm text-slate-500">Hotel Details</span>
            </div>
        </div>
    </div>
    <section class="py-8 md:py-10">
        <div class="container">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-3">
                <div class="flex flex-col gap-4 w-full">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-3">
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="font-semibold text-2xl md:text-[36px] md:leading-[48px] text-slate-950">
                                    {{ $hotel['name'] }}
                                </h1>
                                <div
                                    class="tag bg-green-700 text-white shrink-0 flex items-center gap-1 px-2 py-1 rounded">
                                    <i data-tabler="star-filled" data-size="14"></i>
                                    <span>{{ $hotel['rating'] }}</span>
                                </div>
                            </div>
                                <div class="flex items-center gap-1.5">
                                    <i data-tabler="map-pin" class="text-slate-500 shrink-0"></i>
                                    <span class="font-normal text-sm text-slate-500">
                                        @php
                                            $addressParts = array_filter([
                                                $hotel['location']['address']['line_one']    ?? null,
                                                $hotel['location']['address']['line_two']    ?? null,
                                                $hotel['location']['address']['city_name']   ?? null,
                                                $hotel['location']['address']['region']      ?? null,
                                                $hotel['location']['address']['postal_code'] ?? null,
                                                $hotel['location']['address']['country_code'] ?? null,
                                            ]);
                                        @endphp
                                        {{ implode(', ', $addressParts) }}
                                    </span>
                                </div>
                        </div>
                        <div class="flex flex-col md:items-end">
                            <span class="font-normal text-sm text-slate-500">From</span>
                            <span class="font-semibold text-3xl md:text-[36px] md:leading-[48px] text-blue-600">
                                {{ $currency }} {{ number_format((float) $price, 2) }}
                            </span>
                            <span class="font-normal text-sm text-slate-500">/per night</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-10">
                <div class="sticky top-4 z-50 -mx-2 px-2">
                    <div
                        class="flex gap-2 bg-white p-1 rounded-xl shadow-md border border-slate-100 overflow-x-auto no-scrollbar md:w-fit w-full">
                        <a href="#hotel-description" class="btn btn-primary min-w-[100px]">Overview</a>
                        <a href="#hotel-amenities" class="btn btn-white min-w-[100px]">Amenities</a>
                        <a href="#hotel-location" class="btn btn-white min-w-[100px]">Location</a>
                        <a href="#hotel-rooms" class="btn btn-white min-w-[100px]">Rooms</a>
                        <a href="#hotel-reviews" class="btn btn-white min-w-[100px]">Reviews</a>
                    </div>
                </div>
                <div class="w-full rounded-xl overflow-hidden">
                    {{-- DESKTOP LAYOUT --}}
                    <div class="hidden md:flex gap-2" style="height: 400px;">
                        <div class="relative flex-1 overflow-hidden rounded-xl">
                            <img class="main-gallery-img w-full h-full object-cover cursor-pointer transition-opacity duration-200"
                                src="{{ $hotel['photos'][0]['url'] }}" alt="Hotel Main Photo" data-index="0">
                            <button
                                class="gallery-prev absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800
                                rounded-full w-9 h-9 flex items-center justify-center shadow-md transition-all z-10">
                                <i data-tabler="chevron-left" data-size="18"></i>
                            </button>
                            <button
                                class="gallery-next absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800
                                rounded-full w-9 h-9 flex items-center justify-center shadow-md transition-all z-10">
                                <i data-tabler="chevron-right" data-size="18"></i>
                            </button>
                        </div>
                        <div class="flex flex-col gap-2 w-[280px] shrink-0">
                            @foreach (array_slice($hotel['photos'], 1, 3) as $index => $photo)
                                <div class="relative flex-1 overflow-hidden rounded-xl cursor-pointer group"
                                    data-thumb-index="{{ $index + 1 }}">
                                    <img src="{{ $photo['url'] }}"
                                        class="w-full h-full object-cover group-hover:scale-105 group-hover:brightness-90 transition-all duration-500"
                                        alt="Hotel Photo">
                                    @if ($index === 2)
                                        <div
                                            class="see-all-btn absolute inset-0 bg-black/50 hover:bg-black/60 flex flex-col items-center
                                            justify-center cursor-pointer transition-all duration-300 gap-1">
                                            <i data-tabler="layout-grid" class="text-white" data-size="22"></i>
                                            <span class="text-white font-semibold text-sm tracking-wide uppercase">
                                                See all {{ count($hotel['photos']) }} photos
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- MOBILE LAYOUT --}}
                    <div class="flex flex-col gap-2 md:hidden">
                        <div class="relative w-full overflow-hidden rounded-xl" style="height: 240px;">
                            <img class="main-gallery-img w-full h-full object-cover cursor-pointer transition-opacity duration-200"
                                src="{{ $hotel['photos'][0]['url'] }}" alt="Hotel Main Photo" data-index="0">
                            <button
                                class="gallery-prev absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800
                                rounded-full w-9 h-9 flex items-center justify-center shadow-md transition-all z-10">
                                <i data-tabler="chevron-left" data-size="18"></i>
                            </button>
                            <button
                                class="gallery-next absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800
                                rounded-full w-9 h-9 flex items-center justify-center shadow-md transition-all z-10">
                                <i data-tabler="chevron-right" data-size="18"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (array_slice($hotel['photos'], 1, 3) as $index => $photo)
                                <div class="relative overflow-hidden rounded-xl cursor-pointer group"
                                    style="height: 90px;" data-thumb-index="{{ $index + 1 }}">
                                    <img src="{{ $photo['url'] }}"
                                        class="w-full h-full object-cover group-hover:scale-105 group-hover:brightness-90 transition-all duration-500"
                                        alt="Hotel Photo">
                                    @if ($index === 2)
                                        <div
                                            class="see-all-btn absolute inset-0 bg-black/50 hover:bg-black/60 flex flex-col items-center
                                            justify-center cursor-pointer transition-all duration-300 gap-1">
                                            <i data-tabler="layout-grid" class="text-white" data-size="16"></i>
                                            <span
                                                class="text-white font-semibold text-[10px] tracking-wide uppercase text-center leading-tight">
                                                See all {{ count($hotel['photos']) }} photos
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Description -->
                <div id="hotel-description" class="flex flex-col gap-4">
                    <h4 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-slate-950">Description</h4>
                    <div class="flex flex-col gap-3">
                        <p><span class="font-normal text-base text-slate-600">{{ $hotel['description'] }}</span></p>
                    </div>
                </div>
                <!-- Amenities -->
                <div id="hotel-amenities" class="flex flex-col gap-6 w-full">
                    <h4 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-slate-950">Amenities</h4>
                    <div
                        class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 md:gap-6">
                        @foreach ($hotel['amenities'] as $amenity)
                            <div
                                class="flex flex-col items-center gap-3 bg-white p-2 md:p-5 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                                <div
                                    class="w-12 h-12 flex justify-center items-center rounded-lg group-hover:bg-blue-50 transition-colors">
                                    <i data-tabler="{{ $amenity['type'] }}"
                                        class="text-slate-700 group-hover:text-blue-600" data-size="28"></i>
                                </div>
                                <span
                                    class="font-medium text-sm text-center text-slate-950">{{ $amenity['description'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Location -->
                <div id="hotel-location" class="flex flex-col gap-6 w-full">
                    <h4 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-slate-950">Location</h4>

                    @php
                        $lat     = $hotel['location']['geographic_coordinates']['latitude'] ?? null;
                        $lng     = $hotel['location']['geographic_coordinates']['longitude'] ?? null;
                        $city    = $hotel['location']['address']['city_name'] ?? '';
                        $address = $hotel['location']['address']['line_one'] ?? $hotel['name'] ?? '';
                    @endphp

                    @if ($lat && $lng)
                        <div class="flex flex-col gap-3">
                            {{-- Address bar --}}
                            <div class="flex items-center gap-2 text-slate-500">
                                <i data-tabler="map-pin" data-size="18"></i>
                                <span class="font-normal text-sm">
                                    {{ $address }}{{ $city ? ', ' . $city : '' }}
                                </span>
                            </div>

                            {{-- Map --}}
                            <div class="w-full h-[400px] bg-white p-2 rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&z=15&output=embed&hl=en&markers={{ $lat }},{{ $lng }}"
                                    class="w-full h-full rounded-xl border-0"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>

                            {{-- Open in Google Maps link --}}
                            <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}"
                                target="_blank"
                                class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 w-fit transition-colors">
                                <i data-tabler="external-link" data-size="15"></i>
                                Open in Google Maps
                            </a>
                        </div>
                    @else
                        <p class="text-slate-500 text-sm">Location not available.</p>
                    @endif
                </div>
                <!-- Rooms -->
                <div id="hotel-rooms" class="flex flex-col gap-6 w-full">
                    <h4 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-slate-950">Rooms</h4>
                    <div id="rooms-list" class="flex flex-col gap-6 w-full">
                        @forelse($hotel['rooms'] ?? [] as $roomIndex => $room)
                            @php
                                $firstRate   = $room['rates'][0] ?? null;
                                $price       = $firstRate['total_amount'] ?? null;
                                $currency    = $firstRate['total_currency'] ?? 'USD';
                                $boardType   = $firstRate['board_type'] ?? null;
                                $paymentType = $firstRate['payment_type'] ?? null;
                                $photos      = $room['photos'] ?? [];
                            @endphp
                            <div class="room-card card p-2 transition-all hover:shadow-md">
                                <div class="flex flex-col md:flex-row gap-2 md:gap-6">
                                    <div class="w-full md:w-[320px] h-[220px] shrink-0 relative rounded-xl overflow-hidden bg-slate-100"
                                        data-room-slider="{{ $roomIndex }}">
                                        @foreach ($photos as $pIdx => $photo)
                                            <img src="{{ $photo['url'] }}"
                                                class="room-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $pIdx === 0 ? 'opacity-100' : 'opacity-0' }}"
                                                alt="{{ $room['name'] }}"
                                                data-room="{{ $roomIndex }}"
                                                data-slide="{{ $pIdx }}">
                                        @endforeach
                                        @if (count($photos) > 1)
                                            <button class="room-prev absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70
                                                text-white rounded-full w-7 h-7 flex items-center justify-center z-10 transition-all"
                                                data-room="{{ $roomIndex }}">
                                                <i data-tabler="chevron-left" data-size="15"></i>
                                            </button>
                                            <button class="room-next absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70
                                                text-white rounded-full w-7 h-7 flex items-center justify-center z-10 transition-all"
                                                data-room="{{ $roomIndex }}">
                                                <i data-tabler="chevron-right" data-size="15"></i>
                                            </button>

                                            {{-- Dots --}}
                                            <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1 z-10">
                                                @foreach ($photos as $pIdx => $photo)
                                                    <span class="room-dot w-1.5 h-1.5 rounded-full transition-all
                                                        {{ $pIdx === 0 ? 'bg-white' : 'bg-white/40' }}"
                                                        data-room="{{ $roomIndex }}"
                                                        data-dot="{{ $pIdx }}">
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow flex flex-col p-2 md:py-4 md:pr-4 gap-4">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex flex-col gap-2">
                                                <h3 class="font-semibold text-lg md:text-xl text-slate-950">
                                                    {{ $room['name'] }}
                                                </h3>

                                                {{-- Beds --}}
                                                @if (!empty($room['beds']))
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        @foreach ($room['beds'] as $bed)
                                                            <div class="flex items-center gap-1 text-slate-500">
                                                                <i data-tabler="bed" data-size="16"></i>
                                                                <span class="font-normal text-sm">
                                                                    {{ $bed['count'] }} x {{ ucfirst(str_replace('_', ' ', $bed['type'])) }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Badges --}}
                                                <div class="flex flex-wrap gap-2">
                                                    @if ($boardType)
                                                        <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-full">
                                                            <i data-tabler="{{ $boardType === 'breakfast' ? 'coffee' : 'building' }}" data-size="13"></i>
                                                            {{ ucfirst(str_replace('_', ' ', $boardType)) }}
                                                        </span>
                                                    @endif
                                                    @if ($paymentType)
                                                        <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-full">
                                                            <i data-tabler="credit-card" data-size="13"></i>
                                                            {{ ucfirst(str_replace('_', ' ', $paymentType)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tag bg-green-700 text-white flex items-center gap-1 px-2 py-1 rounded shrink-0">
                                                <i data-tabler="star-filled" data-size="14"></i>
                                                <span>{{ $hotel['rating'] }}</span>
                                            </div>
                                        </div>

                                        {{-- Multiple Rates --}}
                                        @if (count($room['rates']) > 1)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($room['rates'] as $rate)
                                                    <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-3 py-2 bg-slate-50 text-sm">
                                                        <i data-tabler="{{ $rate['board_type'] === 'breakfast' ? 'coffee' : 'building' }}" data-size="14" class="text-slate-500"></i>
                                                        <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $rate['board_type'])) }}</span>
                                                        <span class="font-semibold text-slate-900">{{ $rate['total_currency'] }} {{ number_format((float) $rate['total_amount'], 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col">
                                                <span class="font-normal text-xs text-slate-500">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-bold text-2xl text-blue-600">
                                                        {{ $currency }} {{ $price ? number_format((float) $price, 2) : 'N/A' }}
                                                    </span>
                                                    <span class="font-normal text-xs text-slate-500">/per night</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary px-8">Reserve</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500">No rooms available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
</main>
@push('scripts')
    <script>
        const roomStates = {};

        document.querySelectorAll('.room-next, .room-prev').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                const roomIndex = btn.dataset.room;
                const slides    = document.querySelectorAll(`.room-slide[data-room="${roomIndex}"]`);
                const dots      = document.querySelectorAll(`.room-dot[data-room="${roomIndex}"]`);
                const total     = slides.length;

                if (!roomStates[roomIndex]) roomStates[roomIndex] = 0;

                let cur = roomStates[roomIndex];

                // Hide current
                slides[cur].classList.remove('opacity-100');
                slides[cur].classList.add('opacity-0');
                dots[cur].classList.remove('bg-white');
                dots[cur].classList.add('bg-white/40');

                // Calculate next
                if (btn.classList.contains('room-next')) {
                    cur = (cur + 1) % total;
                } else {
                    cur = (cur - 1 + total) % total;
                }

                // Show next
                slides[cur].classList.remove('opacity-0');
                slides[cur].classList.add('opacity-100');
                dots[cur].classList.remove('bg-white/40');
                dots[cur].classList.add('bg-white');

                roomStates[roomIndex] = cur;
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('div[id^="hotel-"]');
            const navLinks = document.querySelectorAll('.sticky a[href^="#hotel-"]');

            const options = {
                root: null,
                rootMargin: '-10% 0px -80% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.remove('btn-primary');
                            link.classList.add('btn-white');
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('btn-primary');
                                link.classList.remove('btn-white');
                            }
                        });
                    }
                });
            }, options);

            sections.forEach(section => {
                observer.observe(section);
            });

            // Smooth scroll with offset on click
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        // Offset for the sticky header
                        const stickyContainer = document.querySelector('.sticky');
                        const offset = stickyContainer ? stickyContainer.offsetHeight + 40 : 100;

                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });

                        // Update active status immediately for snappy feel
                        navLinks.forEach(l => {
                            l.classList.remove('btn-primary');
                            l.classList.add('btn-white');
                        });
                        this.classList.add('btn-primary');
                        this.classList.remove('btn-white');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('div[id^="hotel-"]');
            const navLinks = document.querySelectorAll('.sticky a[href^="#hotel-"]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.remove('btn-primary');
                            link.classList.add('btn-white');
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('btn-primary');
                                link.classList.remove('btn-white');
                            }
                        });
                    }
                });
            }, {
                root: null,
                rootMargin: '-10% 0px -80% 0px',
                threshold: 0
            });

            sections.forEach(section => observer.observe(section));

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetElement = document.querySelector(this.getAttribute('href'));
                    if (targetElement) {
                        const offset = (document.querySelector('.sticky')?.offsetHeight || 60) + 40;
                        window.scrollTo({
                            top: targetElement.getBoundingClientRect().top + window
                                .pageYOffset - offset,
                            behavior: 'smooth'
                        });
                        navLinks.forEach(l => {
                            l.classList.remove('btn-primary');
                            l.classList.add('btn-white');
                        });
                        this.classList.add('btn-primary');
                        this.classList.remove('btn-white');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('div[id^="hotel-"]');
            const navLinks = document.querySelectorAll('.sticky a[href^="#hotel-"]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.remove('btn-primary');
                            link.classList.add('btn-white');
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('btn-primary');
                                link.classList.remove('btn-white');
                            }
                        });
                    }
                });
            }, {
                root: null,
                rootMargin: '-10% 0px -80% 0px',
                threshold: 0
            });

            sections.forEach(section => observer.observe(section));

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetElement = document.querySelector(this.getAttribute('href'));
                    if (targetElement) {
                        const offset = (document.querySelector('.sticky')?.offsetHeight || 60) + 40;
                        window.scrollTo({
                            top: targetElement.getBoundingClientRect().top + window
                                .pageYOffset - offset,
                            behavior: 'smooth'
                        });
                        navLinks.forEach(l => {
                            l.classList.remove('btn-primary');
                            l.classList.add('btn-white');
                        });
                        this.classList.add('btn-primary');
                        this.classList.remove('btn-white');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {

            const allPhotos = @json(array_map(fn($p) => $p['url'], $hotel['photos']));
            const mainImgs = document.querySelectorAll('.main-gallery-img');
            let mainCur = 0;
            let mainIsAnimating = false;

            function setMainImg(i, direction = 'next') {
                if (mainIsAnimating) return;
                mainIsAnimating = true;

                const next = (i + allPhotos.length) % allPhotos.length;

                // Dono wrappers mein animation chalao (desktop + mobile)
                mainImgs.forEach(mainImg => {
                    const wrapper = mainImg.parentElement;

                    const incoming = document.createElement('img');
                    incoming.src = allPhotos[next];
                    incoming.style.cssText = `
                        position:absolute; inset:0; width:100%; height:100%;
                        object-fit:cover;
                        transform: translateX(${direction === 'next' ? '100%' : '-100%'});
                        transition: transform 0.42s cubic-bezier(0.25, 0.46, 0.45, 0.94);`;
                    wrapper.appendChild(incoming);

                    mainImg.style.transition = 'transform 0.42s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    mainImg.style.transform = `translateX(${direction === 'next' ? '-100%' : '100%'})`;

                    requestAnimationFrame(() => requestAnimationFrame(() => {
                        incoming.style.transform = 'translateX(0)';
                    }));

                    setTimeout(() => {
                        mainImg.src = allPhotos[next];
                        mainImg.dataset.index = next;
                        mainImg.style.transition = 'none';
                        mainImg.style.transform = 'translateX(0)';
                        wrapper.removeChild(incoming);
                    }, 440);
                });

                setTimeout(() => {
                    mainCur = next;
                    mainIsAnimating = false;
                }, 440);
            }

            // Prev / Next buttons — desktop + mobile dono
            document.querySelectorAll('.gallery-prev').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    setMainImg(mainCur - 1, 'prev');
                });
            });

            document.querySelectorAll('.gallery-next').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    setMainImg(mainCur + 1, 'next');
                });
            });

            // Thumbnail click
            document.querySelectorAll('[data-thumb-index]').forEach(el => {
                el.addEventListener('click', () => {
                    const i = parseInt(el.dataset.thumbIndex);
                    openLightbox(i);
                });
            });

            // Main image click — lightbox open
            mainImgs.forEach(img => {
                img.addEventListener('click', () => openLightbox(mainCur));
            });

            // See all btn
            document.querySelectorAll('.see-all-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openLightbox(0);
                });
            });

            // ── LIGHTBOX ──────────────────────────────────────────
            const lb = document.getElementById('lightbox');
            const lbWrapper = document.getElementById('lb-wrapper');
            const lbImg = document.getElementById('lb-img');
            const counter = document.getElementById('lb-counter');
            let cur = 0;
            let isAnimating = false;

            function openLightbox(i) {
                cur = (i + allPhotos.length) % allPhotos.length;
                lbImg.src = allPhotos[cur];
                lbImg.style.transition = 'none';
                lbImg.style.transform = 'translateX(0)';
                counter.textContent = `${cur + 1} / ${allPhotos.length}`;
                lb.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lb.style.display = 'none';
                document.body.style.overflow = '';
            }

            function show(i, direction = 'next') {
                if (isAnimating) return;
                isAnimating = true;

                const next = (i + allPhotos.length) % allPhotos.length;
                const incoming = document.createElement('img');
                incoming.src = allPhotos[next];
                incoming.style.cssText = `
                    position:absolute; inset:0; width:100%; height:100%;
                    object-fit:cover; border-radius:24px;
                    transform: translateX(${direction === 'next' ? '100%' : '-100%'});
                    transition: transform 0.38s cubic-bezier(0.25, 0.46, 0.45, 0.94);`;
                lbWrapper.appendChild(incoming);

                lbImg.style.transition = 'transform 0.38s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                lbImg.style.transform = `translateX(${direction === 'next' ? '-100%' : '100%'})`;

                requestAnimationFrame(() => requestAnimationFrame(() => {
                    incoming.style.transform = 'translateX(0)';
                }));

                setTimeout(() => {
                    cur = next;
                    lbImg.src = allPhotos[cur];
                    lbImg.style.transition = 'none';
                    lbImg.style.transform = 'translateX(0)';
                    counter.textContent = `${cur + 1} / ${allPhotos.length}`;
                    lbWrapper.removeChild(incoming);
                    isAnimating = false;
                }, 400);
            }

            document.getElementById('lb-prev')?.addEventListener('click', () => show(cur - 1, 'prev'));
            document.getElementById('lb-next')?.addEventListener('click', () => show(cur + 1, 'next'));
            document.getElementById('lb-close')?.addEventListener('click', closeLightbox);

            document.addEventListener('keydown', e => {
                if (lb.style.display === 'none') return;
                if (e.key === 'ArrowLeft') show(cur - 1, 'prev');
                if (e.key === 'ArrowRight') show(cur + 1, 'next');
                if (e.key === 'Escape') closeLightbox();
            });

        });

    </script>
@endpush
