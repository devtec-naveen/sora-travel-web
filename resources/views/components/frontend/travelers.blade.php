<div>
@props([
    'id',
    'adults' => request('adults',1),
    'children' => request('childrens',0),
    'infants' => request('infants',0),
    'cabinClass' => request('cabin_class','Economy'),
])
<div class="relative" id="{{ $id }}Wrapper">
    <div id="{{ $id }}Btn"
        onclick="toggleTravelers('{{ $id }}')"
        class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer">
        <div class="w-6 h-6 text-slate-400 flex-shrink-0">
          <img src="{{asset('assets/images/user.svg')}}" alt="icon"/>
       </div>
        <div class="flex flex-col min-w-0 flex-1">
            <span class="text-xs text-slate-500 leading-4">
                Travelers & Class
            </span>
            <span id="{{ $id }}Label"
                class="text-sm font-medium text-slate-800 truncate block">
                {{ $adults + $children + $infants }} Traveler, {{ $cabinClass }}
            </span>
        </div>
    </div>
    <input type="hidden" name="flightType" value="{{ $id }}">
    <input type="hidden" name="adults" id="{{ $id }}_inp_adults" value="{{ $adults }}">
    <input type="hidden" name="childrens" id="{{ $id }}_inp_children" value="{{ $children }}">
    <input type="hidden" name="infants" id="{{ $id }}_inp_infants" value="{{ $infants }}">
    <input type="hidden" name="cabin_class" id="{{ $id }}_inp_class" value="{{ $cabinClass }}">
    <div id="{{ $id }}Dropdown" class="hidden absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 p-4">
        <div class="space-y-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Adults</p>
                    <p class="text-xs text-slate-400">12+ Years</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button"
                        onclick="changePax('{{ $id }}','adults',-1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        −
                    </button>
                    <span id="{{ $id }}_adults-count"
                        class="w-5 text-center text-sm font-semibold text-slate-800">
                        {{ $adults }}
                    </span>
                    <button type="button"
                        onclick="changePax('{{ $id }}','adults',1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        +
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Children</p>
                    <p class="text-xs text-slate-400">2–12 Years</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button"
                        onclick="changePax('{{ $id }}','children',-1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        −
                    </button>
                    <span id="{{ $id }}_children-count"
                        class="w-5 text-center text-sm font-semibold text-slate-800">
                        {{ $children }}
                    </span>
                    <button type="button"
                        onclick="changePax('{{ $id }}','children',1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        +
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Infant</p>
                    <p class="text-xs text-slate-400">0–2 Years</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button"
                        onclick="changePax('{{ $id }}','infants',-1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        −
                    </button>
                    <span id="{{ $id }}_infants-count"
                        class="w-5 text-center text-sm font-semibold text-slate-800">
                        {{ $infants }}
                    </span>
                    <button type="button"
                        onclick="changePax('{{ $id }}','infants',1)"
                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center">
                        +
                    </button>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-5">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio"
                    name="{{ $id }}_cabinClass"
                    value="Economy"
                    {{ $cabinClass == 'Economy' ? 'checked' : '' }}
                    onchange="updateTravelersLabel('{{ $id }}')">
                <span class="text-sm">Economy</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio"
                    name="{{ $id }}_cabinClass"
                    value="Premium Economy"
                    {{ $cabinClass == 'Premium Economy' ? 'checked' : '' }}
                    onchange="updateTravelersLabel('{{ $id }}')">
                <span class="text-sm">Premium Economy</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio"
                    name="{{ $id }}_cabinClass"
                    value="Business"
                    {{ $cabinClass == 'Business' ? 'checked' : '' }}
                    onchange="updateTravelersLabel('{{ $id }}')">
                <span class="text-sm">Business</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio"
                    name="{{ $id }}_cabinClass"
                    value="First Class"
                    {{ $cabinClass == 'First Class' ? 'checked' : '' }}
                    onchange="updateTravelersLabel('{{ $id }}')">
                <span class="text-sm">First Class</span>
            </label>
        </div>
        <button type="button"
            onclick="closeTravelers('{{ $id }}')"
            class="w-full py-2.5 rounded-xl bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold">
            Done
        </button>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const id = "{{ request('flightType','FlightOneway') }}";
    const adults = {{ request('adults',1) }};
    const children = {{ request('children',0) }};
    const infants = {{ request('infants',0) }};
    initPax(id, adults, children, infants);
    document.getElementById(id + "_adults-count").textContent = adults;
    document.getElementById(id + "_children-count").textContent = children;
    document.getElementById(id + "_infants-count").textContent = infants;
    const cabin = "{{ request('cabin_class','Economy') }}";
    const radio = document.querySelector(`input[name='${id}_cabinClass'][value='${cabin}']`);
    if(radio) radio.checked = true;
    updateTravelersLabel(id);
});
</script>
@endpush
</div>