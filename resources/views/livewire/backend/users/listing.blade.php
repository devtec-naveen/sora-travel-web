<div>
    <!-- Header: Title + Search + Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Users</h6>
        <div class="d-flex align-items-center">
            <!-- Search -->
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live="search" class="form-control" placeholder="Search Users">
            </div>

            <!-- Reset Filter -->
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">Reset filter</button>

            <!-- Add User -->
            <button type="button" class="btn ripple btn-main-primary signbtn">Add User</button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered border-t0 key-buttons text-nowrap w-100">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" class="cursor-pointer">
                        Sr. No.
                        @if($sortField === 'id')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('name')" class="cursor-pointer">
                        Name
                        @if($sortField === 'name')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('email')" class="cursor-pointer">
                        Email
                        @if($sortField === 'email')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Phone Number</th>
                    <th wire:click="sortBy('created_at')" class="cursor-pointer">
                        Created Date
                        @if($sortField === 'created_at')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loader row -->
@if ($loading)
<h1>Hello</h1>
<tr class="border-0">
    <td colspan="7" class="text-center border-0">
        <div class="d-flex justify-content-center align-items-center w-100" style="height: 100px;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </td>
</tr>
@endif

                <!-- Users rows -->
                @forelse($users as $user)
                    <tr wire:loading.remove>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name ?? 'N/A' }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('F d, Y') }}</td>
                        <td>
                            <button class="btn ripple btn-main-primary signbtn">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn ripple btn-main-primary signbtn">View</button>
                                <button class="btn ripple btn-success ml-1">Edit</button>
                                <button class="btn ripple btn-secondary ml-1" wire:click="openModal('delete', {{ $user->id }})">Delete</button>
                                <button class="btn ripple btn-info ml-1">Subscription History</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <b>Total records:</b> {{ $users->total() }}
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>
