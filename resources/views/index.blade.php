<x-frontend.main-layout>
    <main>
        <section class="py-6 bg-cover bg-center relative min-h-[500px]"
            style="background-image: url('{{ asset('assets/images/search-bg.jpg') }}');">
            <div class="bg-gradient-to-b from-blue-950 via-blue-950 to-blue-950/0 absolute top-0 left-0 w-full h-full">
            </div>
            <div class="container ">
                <div class="flex flex-col gap-4 md:gap-9 relative z-[1]">
                    <div class="flex items-center gap-2 md:gap-3.5">
                        <button class="tabs-border flex-1 md:flex-none active">
                            <i data-tabler="plane-inflight" class="size-5 md:size-7"></i> Flights
                        </button>
                        <button class="tabs-border flex-1 md:flex-none">
                            <i data-tabler="building" class="size-5 md:size-7"></i> Hotels
                        </button>
                        <button class="tabs-border flex-1 md:flex-none">
                            <i data-tabler="car" class="size-5 md:size-7"></i> Car
                            Rental
                        </button>
                    </div>
                    <div class="search-tab-content">
                        <x-frontend.flight-search-tabs />
                        <x-frontend.hotel-search-tabs />
                        <x-frontend.car-search-tabs />
                    </div>
                    <div
                        class="w-full inline-flex flex-col justify-center items-center gap-1.5 text-center lg:text-left">
                        <div
                            class="self-stretch text-slate-50 font-semibold text-2xl leading-8 md:text-3xl md:leading-10 lg:text-4xl lg:leading-[48px]">
                            Designed for the way the world moves.
                        </div>
                        <div
                            class="self-stretch text-slate-50 font-medium text-base leading-6 md:text-lg md:leading-7 lg:text-xl lg:leading-8">
                            Go anywhere. Stay everywhere. Drive anything.
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="py-10 pb-0">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="w-full px-4 md:px-12 lg:px-28 py-8 md:py-10 rounded-2xl overflow-hidden flex flex-col items-center gap-8 md:gap-10 lg:gap-12 bg-[url('assets/assets/images/support.jpg')] bg-cover bg-center bg-no-repeat">
                    <!-- Content -->
                    <div class="flex flex-col items-center text-center gap-8 w-full">
                        <div class="w-full flex flex-col items-center gap-3 text-center">
                            <div class="w-[80px] h-[80px] md:w-[100px] md:h-[100px] relative overflow-hidden">
                                <img src="assets/images/contact-icon.svg" alt="" class="w-full">
                            </div>
                            <!-- Heading -->
                            <h2
                                class="text-2xl md:text-3xl lg:text-4xl font-semibold font-['Inter'] text-slate-800 leading-8 md:leading-10 lg:leading-[48px]">
                                Get in Touch
                            </h2>
                            <!-- Description -->
                            <p class="w-full max-w-[800px] text-base text-slate-500 leading-6">
                                Our team is available 24 hours to support your travel needs with exceptional care.
                                Please contact our dedicated support team at your convenience for personalized,
                                priority assistance.
                            </p>
                        </div>
                        <!-- Cards -->
                        <div class="flex flex-row md:gap-5 gap-2 w-full justify-center">
                            <!-- Card 1 -->
                            <div
                                class="w-full sm:w-[328px] p-5 bg-white rounded-2xl flex flex-col items-center gap-3.5 shadow-sm">
                                <div
                                    class="w-[60px] h-[60px] p-3.5 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <img src="assets/images/whatsapp.svg" class="w-full" />
                                </div>
                                <div class="flex flex-col gap-1 text-center">
                                    <h3 class="text-sm md:text-lg font-semibold text-slate-800 leading-7">
                                        WhatsApp Us At
                                    </h3>
                                    <p class="md:text-base text-sm text-slate-500 leading-6">
                                        <a href="https://wa.me/15037023278?text=Welcome%20to%20Sorah%20Travel,%20How%20can%20we%20help%20you"
                                            target="_blank">
                                            +1 503-702-3278
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <!-- Card 2 -->
                            <div
                                class="w-full sm:w-[328px] p-5 bg-white rounded-2xl flex flex-col items-center gap-3.5 shadow-sm">
                                <div
                                    class="w-[60px] h-[60px] p-3.5 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <img src="assets/images/mail.svg" class="w-full" />
                                </div>
                                <div class="flex flex-col gap-1 text-center">
                                    <h3 class="text-sm md:text-lg font-semibold text-slate-800 leading-7">
                                        Email Us At
                                    </h3>
                                    <p class="md:text-base text-sm text-slate-500 leading-6">
                                        <a
                                            href="mailto:sorah@info.com?subject=Inquiry&body=Hello%20Sorah%20Travel,%20I%20need%20help">
                                            sorah@info.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24">
            <div class="container">
                <div class="w-full flex flex-col items-center gap-12">
                    <!-- Heading -->
                    <div class="flex flex-col items-center text-center gap-2">
                        <h2
                            class="text-2xl md:text-3xl lg:text-4xl 
                     font-semibold font-['Inter'] 
                     text-slate-800 
                     leading-8 md:leading-10 lg:leading-[48px]">
                            Why Book With Us
                        </h2>
                        <p class="text-sm md:text-base text-slate-500 leading-6">
                            The platform you can trust
                        </p>
                    </div>
                    <!-- Cards -->
                    <div class="w-full grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-6">
                        <!-- Card 1 -->
                        <div
                            class="p-4 md:px-5 md:py-10 bg-green-50 rounded-2xl flex flex-col items-center gap-4 md:gap-6 text-center">
                            <div
                                class="w-[60px] h-[60px] p-3.5 bg-white rounded-2xl shadow-lg flex items-center justify-center text-purple-600">
                                <img src="assets/images/icon-1.svg" />
                            </div>
                            <div>
                                <h3 class="text-base md:text-lg font-semibold text-slate-800 leading-7">
                                    Fast Search
                                </h3>
                                <p class="text-sm text-slate-500 leading-5">
                                    Search flights and hotels from multiple suppliers in seconds
                                </p>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div
                            class="p-4 md:px-5 md:py-10 bg-blue-50 rounded-2xl flex flex-col items-center gap-4 md:gap-6 text-center">
                            <div
                                class="w-[60px] h-[60px] p-3.5 bg-white rounded-2xl shadow-lg flex items-center justify-center text-green-600">
                                <img src="assets/images/icon-2.svg" />
                            </div>
                            <div>
                                <h3 class="text-base md:text-lg font-semibold text-slate-800 leading-7">
                                    Secure Payments
                                </h3>
                                <p class="text-sm text-slate-500 leading-5">
                                    Your payment information is encrypted and secure
                                </p>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div
                            class="p-4 md:px-5 md:py-10 bg-orange-50 rounded-2xl flex flex-col items-center gap-4 md:gap-6 text-center">
                            <div
                                class="w-[60px] h-[60px] p-3.5 bg-white rounded-2xl shadow-lg flex items-center justify-center text-amber-500">
                                <img src="assets/images/icon-3.svg" />
                            </div>
                            <div>
                                <h3 class="text-base md:text-lg font-semibold text-slate-800 leading-7">
                                    Instant Confirmation
                                </h3>
                                <p class="text-sm text-slate-500 leading-5">
                                    Get instant booking confirmation with e-tickets
                                </p>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div
                            class="p-4 md:px-5 md:py-10 bg-teal-50 rounded-2xl flex flex-col items-center gap-4 md:gap-6 text-center">
                            <div
                                class="w-[60px] h-[60px] p-3.5 bg-white rounded-2xl shadow-lg flex items-center justify-center text-yellow-500">
                                <img src="assets/images/icon-4.svg" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 leading-7">
                                    Best Prices
                                </h3>
                                <p class="text-sm text-slate-500 leading-5">
                                    Compare prices from verified global suppliers
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Partner Logos -->
                    <div class="w-full flex flex-col lg:flex-row justify-center items-center gap-6 lg:gap-12 flex-wrap">
                        <div class="flex justify-center items-center gap-4 sm:gap-8 md:gap-12">
                            <img class="h-4 md:h-7" src="assets/images/paypal.png" />
                            <img class="h-4 md:h-7" src="assets/images/stripe.png" />
                            <img class="h-4 md:h-7" src="assets/images/payoneer.png" />
                            <img class="h-4 md:h-7" src="assets/images/visa.png" />
                        </div>
                        <div class="flex justify-center items-center gap-4 sm:gap-8 md:gap-12">
                            <img class="h-4 md:h-7" src="assets/images/cashapp.png" />
                            <img class="h-4 md:h-7" src="assets/images/bitcoin.png" />
                            <img class="h-4 md:h-7" src="assets/images/discover.png" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="">
            <div class="container">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full">
                    <!-- Item 1 -->
                    <div
                        class="flex flex-col lg:flex-row items-center lg:justify-center lg:items-start gap-4 text-center lg:text-left">
                        <div
                            class="w-[60px] h-[60px] flex justify-center items-center bg-slate-100 p-3.5 rounded-2xl shrink-0">
                            <div class="w-10">
                                <img src="assets/images/icon-info-1.svg" alt="Flights" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-base md:text-lg text-slate-800">
                                Best Flight Deals
                            </span>
                            <span class="font-normal text-xs md:text-sm text-slate-600">
                                Compare thousands of airlines and find the best price instantly.
                            </span>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div
                        class="flex flex-col lg:flex-row items-center lg:justify-center lg:items-start gap-4 text-center lg:text-left">
                        <div
                            class="w-[60px] h-[60px] flex justify-center items-center bg-slate-100 p-3.5 rounded-2xl shrink-0">
                            <div class="w-10">
                                <img src="assets/images/icon-info-2.svg" alt="Hotels" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-base md:text-lg text-slate-800">
                                Top Hotels
                            </span>
                            <span class="font-normal text-xs md:text-sm text-slate-600">
                                Stay anywhere with flexible options and easy cancellation.
                            </span>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div
                        class="flex flex-col lg:flex-row items-center lg:justify-center lg:items-start gap-4 text-center lg:text-left col-span-2 md:col-span-1">
                        <div
                            class="w-[60px] h-[60px] flex justify-center items-center bg-slate-100 p-3.5 rounded-2xl shrink-0">
                            <div class="w-10">
                                <img src="assets/images/icon-info-3.svg" alt="Cars" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-base md:text-lg text-slate-800">
                                Car Rentals
                            </span>
                            <span class="font-normal text-xs md:text-sm text-slate-600">
                                Drive with confidence using trusted global rental partners.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24">
            <div class="container relative">
                <div class="flex flex-col items-center text-center gap-2 mb-12">
                    <h2
                        class="text-2xl md:text-3xl lg:text-4xl 
                  font-semibold font-['Inter'] 
                  text-slate-800 
                  leading-8 md:leading-10 lg:leading-[48px]">
                        Popular Destinations
                    </h2>
                    <p class="text-sm md:text-base text-slate-500 leading-6">
                        Navigate the Globe with Confidence
                    </p>
                </div>
                <div class="relative">
                    <div class="swiper DestinationsSlider">
                        <div class="swiper-wrapper">
                            @foreach ($popularDestinations as $destinations)
                                <div class="swiper-slide flex flex-col items-center text-center">
                                    <div class="w-[120px] h-[160px] rounded-full overflow-hidden shadow-md">
                                        <img src="{{$destinations->image}}"
                                            class="w-full h-full object-cover" alt="{{$destinations->title}}">
                                    </div>
                                    <h4 class="mt-4 font-semibold text-slate-800">{{ $destinations->title }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-10 lg:mt-0">
                        <div
                            class="swiper-button-prev1 !static lg:!absolute !mt-0 !left-auto lg:!-left-12 !right-auto !top-auto lg:!top-1/2 lg:!-translate-y-1/2 w-10 h-10 bg-[#e4e6e8] rounded-full flex items-center justify-center hover:bg-slate-300 transition after:hidden text-gray-900 border border-white shadow-sm">
                            <i data-tabler="chevron-left" class="size-5"></i>
                        </div>
                        <div
                            class="swiper-button-next1 !static lg:!absolute !mt-0 !left-auto !right-auto lg:!-right-12 !top-auto lg:!top-1/2 lg:!-translate-y-1/2 w-10 h-10 bg-[#e4e6e8] rounded-full flex items-center justify-center hover:bg-slate-300 transition after:hidden text-gray-900 border border-white shadow-sm">
                            <i data-tabler="chevron-right" class="size-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="flex flex-col items-start gap-2 mb-5">
                    <h2
                        class="text-2xl md:text-3xl lg:text-4xl font-semibold text-slate-800 leading-8 md:leading-10 lg:leading-[48px]">
                        Special Offers
                    </h2>
                </div>
                <div class="w-full overflow-hidden">
                    <div class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar">
                        @foreach ($specialOffers as $offers)
                            <a href="#" class="snap-start shrink-0 w-[85%] sm:w-[60%] md:w-[45%] lg:w-[32%] rounded-xl overflow-hidden">
                                <img src="{{$offers->image}}" class="w-full aspect-[16/6] object-cover" alt="{{$offers->title}}">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24">
            <div class="container">
                <div
                    class="relative w-full bg-primary-50 rounded-2xl px-6 sm:px-10 lg:px-20 py-10 lg:py-14 overflow-hidden min-h-[500px] flex-col lg:flex-row items-center gap-12 flex">
                    <div class="flex flex-col lg:flex-row items-start lg:justify-between justify-center gap-12">
                        <!-- Right Image -->
                        <img src="assets/images/girl.png"
                            class="w-[260px] sm:w-[320px] lg:w-[380px] object-contain lg:absolute lg:right-10 lg:bottom-0 mx-auto lg:mx-0"
                            alt="" />
                    </div>
                    <!-- Left Content -->
                    <div class="w-full lg:max-w-[500px] flex flex-col gap-8 relative z-10 text-center lg:text-left ">
                        <!-- Badge -->
                        <div
                            class="inline-flex items-center px-3 py-1.5 bg-white rounded-full border border-slate-200  w-fit mx-auto lg:mx-0">
                            <span class="text-slate-600 text-sm font-semibold">
                                Install APP & Get discount code
                            </span>
                        </div>
                        <!-- Heading -->
                        <div class="flex flex-col gap-3 ">
                            <h2 class="text-slate-800 text-3xl sm:text-4xl font-semibold leading-tight">
                                Discover Seamless Travel with Sorah
                            </h2>
                            <p class="text-slate-500 text-base leading-6">
                                Embark on a journey like never before with Travila – your ultimate travel companion.
                            </p>
                        </div>
                        <!-- App Buttons -->
                        <div class="flex flex-wrap items-center gap-4 justify-center lg:justify-start ">
                            <a href="#"><img src="assets/images/googleplay.svg"
                                    class="w-[150px] sm:w-[200px] object-contain" /></a>
                            <a href="#"><img src="assets/images/appstore.svg"
                                    class="w-[150px] sm:w-[200px] object-contain" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-frontend.main-layout>
