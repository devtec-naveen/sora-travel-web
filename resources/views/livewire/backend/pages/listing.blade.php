<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Pages</h6>

        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search Pages">
            </div>

            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>

            <a href="" wire:navigate class="btn ripple btn-main-primary signbtn">
                Add Page
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered border-t0 text-nowrap w-100">
            <thead>
                <tr>
                    <th width="5%" wire:click="sortBy('id')" class="cursor-pointer">
                        Sr. No.
                        @if ($sortField === 'id')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('page_title')" class="cursor-pointer">
                        Page Title
                        @if ($sortField === 'page_title')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Slug</th>
                    <th width="8%">Status</th>
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
                <x-table-loader :rows="10" :columns="6" />
                @forelse($pageList as $page)
                    <tr wire:loading.class.add="d-none">
                        <td>
                            {{ ($pageList->currentPage() - 1) * $pageList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ Str::limit($page->page_title, 50, '...') }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>
                            @if ($page->status === 'active')
                                <span onclick="confirmStatusChange({{ $page->id }})"
                                    class="badge badge-success statusClass" style="cursor:pointer;">
                                    Active
                                </span>
                            @else
                                <span onclick="confirmStatusChange({{ $page->id }})"
                                    class="badge badge-danger statusClass" style="cursor:pointer;">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            {{ optional($page->created_at)->format('F d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a wire:navigate href="{{route('admin.pagesView',$page->id)}}"
                                    class="btn btn-sm btn-success">
                                    <i class="si si-eye" title="View"></i>
                                </a>

                                <a wire:navigate href="{{route('admin.pagesEdit',$page->id)}}"
                                    class="btn btn-sm btn-primary ml-1">
                                    <i class="si si-pencil" title="Edit"></i>
                                </a>
                                {{-- 
                                <button 
                                    onclick="confirmDelete({{ $page->id }})"
                                    class="btn btn-sm btn-danger ml-1">
                                    <i class="si si-trash"></i>
                                </button> 
                                --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="6" class="text-center">
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$pageList" />
</div>
