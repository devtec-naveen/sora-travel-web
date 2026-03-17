@props([
    'currentPage' => 1,
    'totalPages'  => 1,
    'total'       => 0,
    'perPage'     => 20,
])

@php
    $current = (int) $currentPage;
    $last    = (int) $totalPages;
    $window = [];
    if ($last <= 7) {
        $window = range(1, $last);
    } else {
        $window[] = 1;
        if ($current > 3) {
            $window[] = '...';
        }
        $start = max(2, $current - 1);
        $end   = min($last - 1, $current + 1);

        for ($i = $start; $i <= $end; $i++) {
            $window[] = $i;
        }

        if ($current < $last - 2) {
            $window[] = '...';
        }

        $window[] = $last;
    }

    $showing = min($perPage * $current, $total);
@endphp

@if($last > 1)
<div class="mt-8 flex flex-col md:flex-row flex-col-reverse justify-between items-center gap-6 self-stretch">
    <span class="font-normal text-sm text-slate-600 order-2 md:order-1">
        Showing {{ $showing }} of {{ number_format($total) }} hotels
    </span>
    <div class="flex items-center gap-2 md:gap-2.5 order-1 md:order-2 flex-wrap justify-center">
        @if($current > 1)
            <button
                wire:click="setPage({{ $current - 1 }})"
                class="flex items-center gap-1.5 transition-all text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block;vertical-align:middle;stroke:currentcolor;">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 6l-6 6l6 6"/>
                </svg>
                <span class="font-normal text-sm">Back</span>
            </button>
        @else
            <button disabled
                class="flex items-center gap-1.5 text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 opacity-40 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block;vertical-align:middle;stroke:currentcolor;">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 6l-6 6l6 6"/>
                </svg>
                <span class="font-normal text-sm">Back</span>
            </button>
        @endif
        @foreach($window as $page)
            @if($page === '...')
                <span class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-400">
                    ...
                </span>
            @elseif($page === $current)
                <button class="w-9 h-9 flex justify-center items-center font-semibold text-sm text-white bg-[#f3b515] rounded-lg transition-transform hover:scale-105">
                    {{ $page }}
                </button>
            @else
                <button
                    wire:click="setPage({{ $page }})"
                    class="w-9 h-9 flex justify-center items-center font-normal text-sm text-slate-950 bg-white rounded-lg border border-slate-100 hover:bg-slate-50 transition-all">
                    {{ $page }}
                </button>
            @endif
        @endforeach
        @if($current < $last)
            <button
                wire:click="setPage({{ $current + 1 }})"
                class="flex items-center gap-1.5 transition-all text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 hover:bg-slate-50">
                <span class="font-normal text-sm">Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block;vertical-align:middle;stroke:currentcolor;">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 6l6 6l-6 6"/>
                </svg>
            </button>
        @else
            <button disabled
                class="flex items-center gap-1.5 text-slate-950 px-3 py-2 bg-white rounded-lg border border-slate-100 opacity-40 cursor-not-allowed">
                <span class="font-normal text-sm">Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block;vertical-align:middle;stroke:currentcolor;">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 6l6 6l-6 6"/>
                </svg>
            </button>
        @endif
    </div>
</div>
@endif