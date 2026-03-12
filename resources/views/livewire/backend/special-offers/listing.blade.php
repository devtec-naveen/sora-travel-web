<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Special Offers</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search Offers">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>
            <a wire:navigate href="{{ route('admin.offersAdd') }}" class="btn ripple btn-main-primary">
                Add Offer
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
                    <th wire:click="sortBy('title')" class="cursor-pointer">
                        Title
                        @if ($sortField === 'title')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Image</th>
                    <th wire:click="sortBy('start_date')" class="cursor-pointer">
                        Start Date
                        @if ($sortField === 'start_date')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('end_date')" class="cursor-pointer">
                        End Date
                        @if ($sortField === 'end_date')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
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
                <x-table-loader :rows="10" :columns="8" />
                @forelse($offerList as $offer)
                    <tr wire:loading.class.add="d-none">
                        <td>{{ ($offerList->currentPage() - 1) * $offerList->perPage() + $loop->iteration }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($offer->title, 40, '...') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="showImagePreview('{{ asset('uploads/special_offer/' . $offer->image) }}')">
                                Preview Image
                            </button>
                        </td>
                        <td>{{ $offer->start_date }}</td>
                        <td>{{ $offer->end_date }}</td>
                        <td>
                            @if ($offer->status === 'active')
                                <span onclick="confirmStatusChange({{ $offer->id }})"
                                    class="badge badge-success statusClass">
                                    Active
                                </span>
                            @else
                                <span onclick="confirmStatusChange({{ $offer->id }})"
                                    class="badge badge-danger statusClass">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            {{ optional($offer->created_at)->format('F d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a wire:navigate href="{{ route('admin.offersView', $offer->id) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="si si-eye" title="View"></i>
                                </a>
                                <a wire:navigate href="{{ route('admin.offersEdit', $offer->id) }}"
                                    class="btn btn-sm btn-primary ml-1">
                                    <i class="si si-pencil" title="Edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $offer->id }})"
                                    class="btn btn-sm btn-danger ml-1">
                                    <i class="si si-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="8" class="text-center">
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$offerList" />
    <x-image-preview-modal />
</div>