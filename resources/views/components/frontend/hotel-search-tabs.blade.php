<div class="{{$hidden ? 'hidden' : ''}} flex flex-col justify-center gap-4 self-stretch bg-white p-2 md:p-4 rounded-xl shadow-sm border border-slate-100">
    <form method="get" action="{{ route('front.hotelsSearch') }}" id="hotelMain">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 w-full items-stretch">
            <div class="lg:col-span-3">
                <x-frontend.autocomplete
                    label="Where are you going?" 
                    name="city" value="dubai" 
                    display="Dubai"
                    placeholder="Search city…" 
                    type="hotel" 
                    icon="hotel.svg" 
                    class="h-full" 
                    latitude="25.252987" 
                    longitude="55.365035"
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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.5 6C16.5 7.19347 16.0259 8.33807 15.182 9.18198C14.3381 10.0259 13.1935 10.5 12 10.5C10.8065 10.5 9.66193 10.0259 8.81802 9.18198C7.97411 8.33807 7.5 7.19347 7.5 6C7.5 4.80653 7.97411 3.66193 8.81802 2.81802C9.66193 1.97411 10.8065 1.5 12 1.5C13.1935 1.5 14.3381 1.97411 15.182 2.81802C16.0259 3.66193 16.5 4.80653 16.5 6ZM18 6C18 4.4087 17.3679 2.88258 16.2426 1.75736C15.1174 0.632141 13.5913 0 12 0C10.4087 0 8.88258 0.632141 7.75736 1.75736C6.63214 2.88258 6 4.4087 6 6C6 7.5913 6.63214 9.11742 7.75736 10.2426C8.88258 11.3679 10.4087 12 12 12C13.5913 12 15.1174 11.3679 16.2426 10.2426C17.3679 9.11742 18 7.5913 18 6ZM3 23.25C3 22.0681 3.23279 20.8978 3.68508 19.8058C4.13738 18.7139 4.80031 17.7218 5.63604 16.886C6.47177 16.0503 7.46392 15.3874 8.55585 14.9351C9.64778 14.4828 10.8181 14.25 12 14.25C13.1819 14.25 14.3522 14.4828 15.4442 14.9351C16.5361 15.3874 17.5282 16.0503 18.364 16.886C19.1997 17.7218 19.8626 18.7139 20.3149 19.8058C20.7672 20.8978 21 22.0681 21 23.25C21 23.4489 21.079 23.6397 21.2197 23.7803C21.3603 23.921 21.5511 24 21.75 24C21.9489 24 22.1397 23.921 22.2803 23.7803C22.421 23.6397 22.5 23.4489 22.5 23.25C22.5 17.451 17.799 12.75 12 12.75C6.201 12.75 1.5 17.451 1.5 23.25C1.5 23.4489 1.57902 23.6397 1.71967 23.7803C1.86032 23.921 2.05109 24 2.25 24C2.44891 24 2.63968 23.921 2.78033 23.7803C2.92098 23.6397 3 23.4489 3 23.25Z"
                                fill="#94A3B8" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-400 leading-4">Guests</span>
                        <span id="hgLabel" class="text-sm font-semibold text-slate-800">1 adult · 0 children · 1
                            room</span>
                    </div>
                </div>
                <input type="hidden" name="rooms" id="hg_rooms" value="1" />
                <input type="hidden" name="adults" id="hg_adults" value="1" />
                <input type="hidden" name="children" id="hg_children" value="0" />
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
                                    class="w-5 text-center text-sm font-bold text-slate-800">1</span>
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
                                    class="w-5 text-center text-sm font-bold text-slate-800">1</span>
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
                                    class="w-5 text-center text-sm font-bold text-slate-800">0</span>
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
