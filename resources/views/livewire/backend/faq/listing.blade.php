<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">FAQs</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search FAQs">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">
                Reset Filter
            </button>
            <a href="{{ route('admin.faqAdd') }}" wire:navigate class="btn ripple btn-main-primary signbtn">Add Faq</a>
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
                    <th class="cursor-pointer">Question</th>
                    <th>Answer</th>
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
                <x-table-loader :rows="10" :columns="6" />
                @forelse($faqList as $faq)
                    <tr wire:loading.class.add="d-none">
                        <td>
                            {{ ($faqList->currentPage() - 1) * $faqList->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ Str::limit($faq->question, 50, '...') }}</td>
                        <td>{{ Str::limit(strip_tags($faq->answer), 50, '...') }}</td>
                        <td>
                            @if ($faq->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            {{ optional($faq->created_at)->format('F d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-success">View</button>
                                <button class="btn btn-sm btn-primary ml-1">Edit</button>
                                <button class="btn btn-sm btn-danger ml-1">Delete</button>
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
    <x-admin-tabe-pagination :paginator="$faqList" />
</div>
