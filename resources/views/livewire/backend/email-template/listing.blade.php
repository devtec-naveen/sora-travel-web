<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Email Templates</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search Templates">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered border-t0 text-nowrap w-100">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th wire:click="sortBy('name')" class="cursor-pointer">
                        Name
                        @if ($sortField === 'name')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Slug</th>
                    <th wire:click="sortBy('subject')" class="cursor-pointer">
                        Subject
                        @if ($sortField === 'subject')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Body</th>
                    <th wire:click="sortBy('status')" class="cursor-pointer">
                        Status
                        @if ($sortField === 'status')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('created_at')" class="cursor-pointer">
                        Created Date
                        @if ($sortField === 'created_at')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <x-table-loader :rows="10" :columns="8" />
                @forelse($emailTemplateList as $template)
                    <tr wire:loading.class.add="d-none">
                        <td>
                            {{ ($emailTemplateList->currentPage() - 1) * $emailTemplateList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ Str::title($template->name) }}</td>

                        <td>{{ $template->slug }}</td>
                        <td>
                            {{ Str::limit($template->subject, 30, '...') }}
                        </td>
                        <td>
                            {{ Str::limit(strip_tags($template->body), 50, '...') }}
                        </td>
                        <td>
                            @if ($template->status === 'active')
                                <span onclick="confirmStatusChange({{ $template->id }})" class="badge badge-success statusClass">Active</span>
                            @else
                                <span onclick="confirmStatusChange({{ $template->id }})" class="badge badge-danger statusClass">Inactive</span>
                            @endif
                        </td>
                        <td>
                            {{ optional($template->created_at)->format('F d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a wire:navigate href="{{route('admin.emailTemplateView',$template->id)}}" class="btn btn-sm btn-success">View</a>
                                {{-- <button class="btn btn-sm btn-primary ml-1">Edit</button> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="8" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$emailTemplateList" />
</div>
