{{-- <div>
    <tr >
        <td colspan="6" class="text-center py-3">
            <div wire:loading wire:target="nextPage, previousPage,search, sortBy, resetFilters, gotoPage" class="spinner-border text-primary"
                style="width:1.5rem; height:1.5rem;" role="status">
            </div>
        </td>
    </tr>
</div> --}}

@for ($i = 0; $i < $rows; $i++)
    <tr class="d-none" wire:loading.class.remove="d-none">
        @for ($j = 0; $j < $columns; $j++)
            <td>
                <div wire:loading wire:target="changeStatus, deleteConfirmed,nextPage, previousPage, search, sortBy, resetFilters, gotoPage" class="skeleton"></div>
            </td>
        @endfor
    </tr>
@endfor
