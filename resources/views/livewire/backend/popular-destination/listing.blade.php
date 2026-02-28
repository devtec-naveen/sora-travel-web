<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Popular Destinations</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search Destinations">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>
            <a wire:navigate href="{{ route('admin.destinationsAdd') }}" class="btn ripple btn-main-primary">
                Add Destination
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
                    <th width="15%">Image</th>
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
                @forelse($destinationList as $destination)
                    <tr wire:loading.class.add="d-none">
                        <td>
                            {{ ($destinationList->currentPage() - 1) * $destinationList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($destination->title, 40, '...') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="showImagePreview('{{ asset('uploads/popular_destination/' . $destination->image) }}')">
                                Preview Image
                            </button>
                        </td>
                        <td>
                            @if ($destination->status === 'active')
                                <span onclick="confirmStatusChange({{ $destination->id }})"
                                    class="badge badge-success statusClass">
                                    Active
                                </span>
                            @else
                                <span onclick="confirmStatusChange({{ $destination->id }})"
                                    class="badge badge-danger statusClass">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td>{{ optional($destination->created_at)->format('F d, Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a wire:navigate href="{{ route('admin.destinationsView', $destination->id) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="si si-eye" title="View"></i>
                                </a>
                                <a wire:navigate href="{{ route('admin.destinationsEdit', $destination->id) }}"
                                    class="btn btn-sm btn-primary ml-1">
                                    <i class="si si-pencil" title="Edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $destination->id }})"
                                    class="btn btn-sm btn-danger ml-1">
                                    <i class="si si-trash"></i>
                                </button>
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
    <x-admin-tabe-pagination :paginator="$destinationList" />
    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function showImagePreview(imagePath) {
            document.getElementById('previewImage').src = imagePath;
            $('#imagePreviewModal').modal('show');
        }
    </script>
@endpush
