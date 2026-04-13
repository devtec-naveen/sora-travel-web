<div>
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="main-content-label">My Bookings</h6>
        <div class="d-flex align-items-center">
            <div class="form-group mb-0 mr-2">
                <input type="search" wire:model.live.debounce.700ms="search" class="form-control"
                    placeholder="Search order no, name, email..." />
            </div>
            <button type="button" wire:click="resetFilters" class="btn btn-warning">
                Reset Filter
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="d-flex mb-3" style="gap:10px">
        @foreach ($types as $key => $type)
            <button type="button" wire:click="switchTab('{{ $key }}')"
                class="btn {{ $activeTab === $key ? 'btn-primary' : 'btn-outline-secondary' }} d-flex align-items-center gap-1">
                <i class="fa {{ $type['icon'] }} mr-1"></i>
                {{ $type['label'] }}
                <span class="badge {{ $activeTab === $key ? 'badge-light text-primary' : 'badge-secondary' }} ml-1">
                    {{ $counts[$key] ?? 0 }}
                </span>
            </button>
        @endforeach
    </div>

    {{-- Table --}}
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
                    <th wire:click="sortBy('order_number')" class="cursor-pointer">
                        Order No.
                        @if ($sortField === 'order_number')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Customer</th>

                    {{-- Flight specific --}}
                    @if ($activeTab === 'flight')
                        <th>Route / Airline</th>
                    @endif

                    {{-- Hotel specific --}}
                    @if ($activeTab === 'hotel')
                        <th>Hotel / Check-in</th>
                    @endif

                    {{-- Car specific --}}
                    @if ($activeTab === 'car')
                        <th>Car / Pickup</th>
                    @endif

                    <th wire:click="sortBy('total_amount')" class="cursor-pointer">
                        Amount
                        @if ($sortField === 'total_amount')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('status')" class="cursor-pointer">
                        Status
                        @if ($sortField === 'status')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th wire:click="sortBy('booking_date')" class="cursor-pointer">
                        Booking Date
                        @if ($sortField === 'booking_date')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Skeleton Loader --}}
                <x-table-loader :rows="10" :columns="$activeTab === 'flight' ? 9 : 9" />

                {{-- Rows --}}
                @forelse ($bookings as $booking)
                    @php
                        $data = $booking->data ?? [];
                    @endphp
                    <tr wire:loading.class.add="d-none">
                        <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $booking->order_number }}</strong></td>
                        <td>
                            <div>{{ Str::title($booking->user->name ?? 'N/A') }}</div>
                            <small class="text-muted">{{ $booking->user->email ?? '' }}</small>
                        </td>

                        {{-- Flight --}}
                        @if ($activeTab === 'flight')
                            @php
                                $origin = data_get($data, 'slices.0.segments.0.origin.iata_code', 'N/A');
                                $destination = data_get($data, 'slices.0.segments.0.destination.iata_code', 'N/A');
                                $airline = data_get($data, 'slices.0.segments.0.operating_carrier.name', 'N/A');
                            @endphp
                            <td>
                                <span class="badge badge-light">{{ $origin }} → {{ $destination }}</span>
                                <div><small class="text-muted">{{ $airline }}</small></div>
                            </td>
                        @endif

                        {{-- Hotel --}}
                        @if ($activeTab === 'hotel')
                            @php
                                $hotelName = data_get($data, 'hotel_name', 'N/A');
                                $checkIn = data_get($data, 'check_in', null);
                                $checkOut = data_get($data, 'check_out', null);
                            @endphp
                            <td>
                                <div>{{ $hotelName }}</div>
                                @if ($checkIn)
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($checkIn)->format('d M Y') }}
                                        @if ($checkOut)
                                            – {{ \Carbon\Carbon::parse($checkOut)->format('d M Y') }}
                                        @endif
                                    </small>
                                @endif
                            </td>
                        @endif

                        {{-- Car --}}
                        @if ($activeTab === 'car')
                            @php
                                $carName = data_get($data, 'vehicle.name', 'N/A');
                                $pickupLoc = data_get($data, 'pickup_location', 'N/A');
                            @endphp
                            <td>
                                <div>{{ $carName }}</div>
                                <small class="text-muted">{{ $pickupLoc }}</small>
                            </td>
                        @endif

                        <td>
                            <strong>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</strong>
                            @if ($booking->platform_fee > 0)
                                <div><small class="text-muted">Fee:
                                        {{ number_format($booking->platform_fee, 2) }}</small></div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $service->getStatusBadgeClass($booking->status) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            {{ $booking->booking_date
                                ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, h:i A')
                                : $booking->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td>
                            @if ($booking->payment)
                                <span class="badge {{ $service->getPaymentBadgeClass($booking->payment->status) }}">
                                    {{ ucfirst($booking->payment->status) }}
                                </span>
                                <div><small class="text-muted">{{ $booking->payment->payment_method ?? '' }}</small>
                                </div>
                            @else
                                <span class="badge badge-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a wire:navigate
                            href="{{ route('admin.booking.flight.view', ['id' => $booking->id, 'type' => $activeTab]) }}"
                            class="btn btn-sm btn-success">
                            View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="9" class="text-center text-muted py-4">
                            No {{ ucfirst($activeTab) }} bookings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <x-admin-tabe-pagination :paginator="$bookings" />
</div>
