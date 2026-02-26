<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="mb-2">
        @if ($paginator->total() > 0)
            <p class="small text-muted mb-1">
                Showing {{ $paginator->firstItem() }}
                to {{ $paginator->lastItem() }}
                of {{ $paginator->total() }} results
            </p>
        @endif
        <b>Total records:</b> {{ $paginator->total() }}
    </div>
    @if($paginator->count() > 0)
    <div>
        <ul class="pagination mb-0">
            {{-- Previous --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <button class="page-link" wire:click="previousPage" wire:loading.attr="disabled">
                    &lsaquo;
                </button>
            </li>
            {{-- Page Numbers --}}
            @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                    <button class="page-link" wire:click="gotoPage({{ $page }})">
                        {{ $page }}
                    </button>
                </li>
            @endfor
            {{-- Next --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled">
                    &rsaquo;
                </button>
            </li>
        </ul>
    </div>
    @endif
</div>
