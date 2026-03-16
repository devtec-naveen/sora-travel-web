<div class="dtp-field relative flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-blue-400 transition cursor-pointer"
    data-dtp-id="{{ $id }}" data-mode="{{ $mode }}" data-min-date="{{ $minDate }}">
    <div class="w-6 h-6 flex-shrink-0">
        <img src="{{ asset('assets/images/calendar.svg') }}" alt="icon"/>
    </div>
    <div class="flex flex-col min-w-0 flex-1">
        <span class="text-xs text-slate-500 leading-4">
            {{ $label }}
        </span>
        <span id="dtp_lbl_{{ $id }}" class="text-sm font-medium" style="color:#94a3b8">
            {{ $placeholder }}
        </span>
        <input type="hidden"
            name="{{ $name }}"
            id="dtp_val_{{ $id }}"
            value="{{ !empty($value) ? $value : ($mode === 'range' ? now()->format('Y-m-d') : '') }}"
            @if($mode !== 'range' && empty($value)) data-default-today @endif
        />
        @if($mode === 'range')
            <input type="hidden"
                id="dtp_end_{{ $id }}"
                name="{{ $endName }}"
                value="{{ !empty($endValue) ? $endValue : now()->addDay()->format('Y-m-d') }}"
            />
        @endif
    </div>
    <div id="dtp_dd_{{ $id }}" class="dtp-drop" onclick="event.stopPropagation()">
        <div id="dtp_body_{{ $id }}"></div>
    </div>
</div>