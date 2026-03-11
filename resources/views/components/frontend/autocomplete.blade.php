<div {{ $attributes->merge([
        'class' => 'ap-field relative flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer'
    ]) }} 
    data-type="{{ $type }}">
    <div class="w-6 h-6 text-slate-400 flex-shrink-0">
        <img src="{{asset('assets/images/'.$icon)}}" alt="icon"/>
    </div>
    <div class="flex flex-col min-w-0">
        <span class="text-xs text-slate-800 leading-4">
            {{ $label }}
        </span>
        <span class="ap-display text-sm font-semibold text-slate-800 leading-5 capitalize">
            {{ $display }}
        </span>
        <input type="hidden" class="ap-hidden" name="{{ $name }}" value="{{ $value }}" />
        @if($type == 'airport')
            <input type="hidden" class="ap-city-hidden" name="{{$cityInputName}}" value="{{ $cityValue }}" />
        @endif
    </div>
    <div class="ap-dropdown hidden">
        <div class="ap-search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="text" class="ap-search-input" placeholder="{{ $placeholder }}" autocomplete="off" />
        </div>
        <div class="ap-results"></div>
    </div>
</div>
