<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">Users</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search Users">
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning mr-2">Reset filter</button>
            <!-- Add User -->
            {{-- <button type="button" class="btn ripple btn-main-primary signbtn">Add User</button> --}}
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered border-t0 key-buttons text-nowrap w-100">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" class="cursor-pointer">
                        Sr. No.
                        @if ($sortField === 'id')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('name')" class="cursor-pointer">
                        Name
                        @if ($sortField === 'name')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('email')" class="cursor-pointer">
                        Email
                        @if ($sortField === 'email')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Phone Number</th>
                    <th wire:click="sortBy('created_at')" class="cursor-pointer">
                        Created Date
                        @if ($sortField === 'created_at')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    {{-- <th>Status</th> --}}
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <x-table-loader :rows="10" :columns="6" />
                @forelse($users as $user)
                    <tr wire:loading.class.add="d-none">
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td>{{ Str::title($user->name) ?? 'N/A' }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('F d, Y') }}</td>
                        {{-- <td>
                            <button class="btn ripple btn-main-primary signbtn">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </button>
                        </td> --}}
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-success">View</button>
                                {{-- <button class="btn ripple btn-success ml-1">Edit</button>
                                <button class="btn ripple btn-secondary ml-1"
                                    wire:click="openModal('delete', {{ $user->id }})">Delete</button>
                                <button class="btn ripple btn-info ml-1">Subscription History</button> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="7" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-admin-tabe-pagination :paginator="$users" />
</div>
