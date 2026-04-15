@php
    $rooms = request('rooms', 1);
    $adults = request('adults', 1);
    $children = request('childrens', 0);
@endphp
<div class="{{$hidden ? 'hidden' : ''}} flex flex-col justify-center gap-4 self-stretch bg-white p-2 md:p-4 rounded-xl shadow-sm border border-slate-100">
    <form method="get" action="{{ route('front.hotelsSearch') }}" id="hotelMain">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 w-full items-stretch">
            <div class="lg:col-span-3">
                <x-frontend.autocomplete
                    label="Where are you going?" 
                    name="city" 
                    value="{{ request('city', 'dubai') }}" 
                    display="{{ request('city', 'Dubai') }}"
                    placeholder="Search city…" 
                    type="hotel" 
                    icon="hotel.svg" 
                    class="h-full" 
                    latitude="{{ request('latitude', '25.252987') }}" 
                    longitude="{{ request('longitude', '55.365035') }}"
                />
            </div>
            <div class="md:col-span-1 lg:col-span-4">
                <x-frontend.date-picker
                    id="hotel_dates"
                    name="check_in"
                    end-name="check_out"
                    label="Check-in – Check-out"
                    placeholder="Select dates"
                    mode="range"
                    min-date="today"
                    :value="request('check_in')"
                    :end-value="request('check_out')"
                />
            </div>
            <div class="relative lg:col-span-3" id="hgWrapper">
                <div onclick="toggleHG()"
                    class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer h-full">
                    <div class="w-6 h-6 text-slate-400 flex-shrink-0">
                        <img src="{{asset('assets/images/user.svg')}}" alt="icon"/>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-400 leading-4">Guests</span>
                        <span id="hgLabel" class="text-sm font-semibold text-slate-800">
                            {{ $adults }} adult{{ $adults > 1 ? 's' : '' }} · {{ $children }} child{{ $children > 1 ? 'ren' : '' }} · {{ $rooms }} room{{ $rooms > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
                <input type="hidden" name="rooms" id="hg_rooms" value="{{ $rooms }}" />
                <input type="hidden" name="adults" id="hg_adults" value="{{ $adults }}" />
                <input type="hidden" name="childrens" id="hg_children" value="{{ $children }}" />

                <div id="hgDropdown"
                    class="hidden absolute top-full left-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 p-5"
                    onclick="event.stopPropagation()">
                    <div class="space-y-5 mb-5">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-800">Room</p>
                            <div class="flex items-center gap-3">
                                <button type="button" id="btn_rooms_minus" onclick="changeHG('rooms',-1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none disabled:opacity-30">−</button>
                                <span id="hg_disp_rooms"
                                    class="w-5 text-center text-sm font-bold text-slate-800">{{ $rooms }}</span>
                                <button type="button" onclick="changeHG('rooms',1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none">+</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Adults</p>
                                <p class="text-xs text-slate-400">18+ Years</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" id="btn_adults_minus" onclick="changeHG('adults',-1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none disabled:opacity-30">−</button>
                                <span id="hg_disp_adults"
                                    class="w-5 text-center text-sm font-bold text-slate-800">{{ $adults }}</span>
                                <button type="button" onclick="changeHG('adults',1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none">+</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Children</p>
                                <p class="text-xs text-slate-400">0 – 17 Years Old</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" id="btn_children_minus" onclick="changeHG('children',-1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none disabled:opacity-30">−</button>
                                <span id="hg_disp_children"
                                    class="w-5 text-center text-sm font-bold text-slate-800">{{ $children }}</span>
                                <button type="button" onclick="changeHG('children',1)"
                                    class="hg-btn w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50 transition font-bold text-lg leading-none">+</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="applyHG()"
                        class="btn-primary w-full py-2.5 rounded-xl text-white text-sm font-semibold transition cursor-pointer">
                        Apply
                    </button>
                </div>
            </div>
            <button class="btn btn-primary h-full lg:col-span-2 md:col-span-2">
                <i data-tabler="search" class="shrink-0"></i> Search
            </button>        
         </div>
    </form>
</div>
