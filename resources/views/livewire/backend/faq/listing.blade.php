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
                    <th width="5%">Sr. No.</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th width="5%">Status</th>
                    <th width="12%">Created Date</th>
                    <th width="12%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedFaqs = $faqList->getCollection()->groupBy(function ($faq) {
                        return $faq->faqCategory->name ?? 'Uncategorized';
                    });
                    $serial = 1;
                @endphp
                <x-table-loader :rows="10" :columns="6" />
                @forelse($groupedFaqs as $categoryName => $faqs)
                    <tr class="table-secondary" wire:loading.class.add="d-none">
                        <th colspan="6">{{ $categoryName }}</th>
                    </tr>
                    @foreach ($faqs as $faq)
                        <tr wire:loading.class.add="d-none">
                            <td>{{ $serial++ }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ Str::limit(strip_tags($faq->answer), 50, '...') }}</td>
                            <td>
                                @if ($faq->status === 'active')
                                    <span onclick="confirmStatusChange({{ $faq->id }})"
                                        class="badge badge-success statusClass">Active</span>
                                @else
                                    <span onclick="confirmStatusChange({{ $faq->id }})"
                                        class="badge badge-danger statusClass">Inactive</span>
                                @endif
                            </td>
                            <td>{{ optional($faq->created_at)->format('F d, Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a wire:navigate href="{{route('admin.faqView',$faq->id)}}" class="btn btn-sm btn-success"><i class="si si-eye" aria-hidden="true" title="View"></i></a>
                                    <a wire:navigate href="{{route('admin.faqEdit',$faq->id)}}" class="btn btn-sm btn-primary ml-1"><i class="si si-pencil" aria-hidden="true" data-original-title="Edit" title="Edit"></i></a>
                                    <button onclick="confirmDelete({{ $faq->id }})"
                                        class="btn btn-sm btn-danger ml-1">
                                        <i class="si si-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$faqList" />
</div>
