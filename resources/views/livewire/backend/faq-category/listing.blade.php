<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">FAQs</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search FAQs Category">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>
            <a href="{{ route('admin.faqCategoryAdd') }}" wire:navigate class="btn ripple btn-main-primary signbtn">Add Faq Category</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered border-t0 text-nowrap w-100">
            <thead>
                <tr>
                    <th width="5%" wire:click="sortBy('id')">Sr. No.
                        @if ($sortField === 'id')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th class="cursor-pointer">Name</th>
                    <th width="5%">Status</th>
                    <th width="12%" wire:click="sortBy('created_at')" class="cursor-pointer">
                        Created Date
                        @if ($sortField === 'created_at')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th width="12%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <x-table-loader :rows="10" :columns="5" />
                @forelse($faqCategoryList as $faqCategory)
                    <tr wire:loading.class.add="d-none">
                        <td>
                            {{ ($faqCategoryList->currentPage() - 1) * $faqCategoryList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ Str::limit($faqCategory->name, 50, '...') }}</td>
                        <td>
                            @if ($faqCategory->status === 'active')
                                <span onclick="confirmStatusChange({{ $faqCategory->id }})" class="badge badge-success statusClass">Active</span>
                            @else
                                <span onclick="confirmStatusChange({{ $faqCategory->id }})" class="badge badge-danger statusClass">Inactive</span>
                            @endif
                        </td>
                        <td>
                            {{ optional($faqCategory->created_at)->format('F d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a wire:navigate href="{{route('admin.faqCategoryView',$faqCategory->id)}}" class="btn btn-sm btn-success"><i class="si si-eye" aria-hidden="true" title="View"></i></a>
                                <a wire:navigate href="{{route('admin.faqCategoryEdit',$faqCategory->id)}}" class="btn btn-sm btn-primary ml-1"><i class="si si-pencil" aria-hidden="true" data-original-title="Edit" title="Edit"></i></a>
                                {{-- <button 
                                    onclick="confirmDelete({{ $faqCategory->id }})"
                                    class="btn btn-sm btn-danger ml-1">
                                    <i class="si si-trash" aria-hidden="true"></i>
                                </button> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="6" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$faqCategoryList" />
</div>
