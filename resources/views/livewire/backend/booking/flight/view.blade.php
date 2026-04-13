<div>
    @php
        $status = $order['status'] ?? 'pending';
        $orderNumber = $order['order_number'] ?? 'N/A';
        $currency = $order['currency'] ?? 'USD';
        $totalAmount = $order['total_amount'] ?? 0;
        $baseAmount = $order['base_amount'] ?? 0;
        $taxAmount = $order['tax_amount'] ?? 0;
        $platformFee = $order['platform_fee'] ?? 0;
        $addonsAmount = $order['addons_amount'] ?? 0;
        $seatAmount = $order['seat_amount'] ?? 0;
        $bookingDate = $order['booking_date'] ?? ($order['created_at'] ?? null);
        $externalId = $order['external_id'] ?? null;
        $notes = $order['notes'] ?? null;

        $statusMap = [
            'confirmed' => ['success', 'Confirmed', '#d1fae5', '#065f46', '#059669'],
            'pending' => ['warning', 'Pending', '#fef3c7', '#78350f', '#d97706'],
            'cancelled' => ['danger', 'Cancelled', '#fee2e2', '#7f1d1d', '#dc2626'],
            'failed' => ['secondary', 'Failed', '#f1f5f9', '#334155', '#64748b'],
        ];
        [$sBs, $sLabel, $sBg, $sText, $sAccent] = $statusMap[$status] ?? [
            'secondary',
            'Unknown',
            '#f1f5f9',
            '#334155',
            '#64748b',
        ];

        $payBadgeMap = [
            'completed' => ['#d1fae5', '#065f46', 'Completed'],
            'refunded' => ['#dbeafe', '#1e3a5f', 'Refunded'],
            'failed' => ['#fee2e2', '#7f1d1d', 'Failed'],
            'pending' => ['#fef3c7', '#78350f', 'Pending'],
        ];
        $payStatus = $payment['status'] ?? 'pending';
        [$payBg, $payTxt, $payLabel] = $payBadgeMap[$payStatus] ?? ['#f1f5f9', '#334155', 'Unknown'];

        $fd = $flightData;
        $bookingRef = $fd['booking_reference'] ?? null;
        $slices = $fd['slices'] ?? [];
        $passengers = $fd['passengers'] ?? [];
        $documents = $fd['documents'] ?? [];
        $conditions = $fd['conditions'] ?? [];
        $owner = $fd['owner'] ?? [];

        $refundable = $conditions['refund_before_departure']['allowed'] ?? false;
        $refundFee = $conditions['refund_before_departure']['penalty_amount'] ?? null;
        $refundCurr = $conditions['refund_before_departure']['penalty_currency'] ?? $currency;
        $changeable = $conditions['change_before_departure']['allowed'] ?? false;
        $changeFee = $conditions['change_before_departure']['penalty_amount'] ?? null;

        $firstSlice = $slices[0] ?? [];
        $firstSeg = $firstSlice['segments'][0] ?? [];
        $airline = $firstSeg['operating_carrier']['name'] ?? ($owner['name'] ?? 'N/A');
        $airlineLogo = $firstSeg['operating_carrier']['logo_symbol_url'] ?? ($owner['logo_symbol_url'] ?? '');
        $flightNo = trim(
            ($firstSeg['operating_carrier']['iata_code'] ?? '') .
                ' ' .
                ($firstSeg['operating_carrier_flight_number'] ?? ''),
        );
    @endphp

    <style>
        .bv-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .bv-card-header {
            padding: 12px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bv-card-title {
            font-size: .78rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin: 0;
        }

        .bv-card-body {
            padding: 20px;
        }

        .bv-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .02em;
        }

        .bv-tag {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: .72rem;
            font-weight: 500;
            display: inline-block;
        }

        .bv-iata {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.02em;
            line-height: 1;
        }

        .bv-time {
            font-size: .88rem;
            font-weight: 700;
            color: #1e293b;
        }

        .bv-city {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .bv-date-sm {
            font-size: .72rem;
            color: #64748b;
        }

        .bv-sep-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .bv-seg-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }

        .bv-kv {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            font-size: .83rem;
            gap: 8px;
        }

        .bv-kv:last-child {
            margin-bottom: 0;
        }

        .bv-kv-label {
            color: #64748b;
            flex-shrink: 0;
        }

        .bv-kv-val {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }

        .bv-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 12px 0;
        }

        .bv-fare-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .bv-fare-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
    </style>

    {{-- ── Page Header ──────────────────────────────────────────────── --}}
    <div
        style="background:linear-gradient(135deg,#1e3a5f 0%,#1d4ed8 100%);border-radius:12px;padding:20px 24px;margin-bottom:20px;color:#fff;">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
            <div class="d-flex align-items-center gap-3">
                @if ($airlineLogo)
                    <div
                        style="width:48px;height:48px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <img src="{{ $airlineLogo }}" alt="{{ $airline }}"
                            style="height:30px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                    </div>
                @endif
                <div>
                    <div style="font-size:1.05rem;font-weight:800;letter-spacing:-.01em;">
                        {{ $airline }}
                        @if ($flightNo)
                            <span
                                style="font-weight:400;opacity:.65;font-size:.82rem;margin-left:6px;">{{ $flightNo }}</span>
                        @endif
                    </div>
                    <div style="font-size:.78rem;opacity:.65;margin-top:3px;">
                        Order #{{ $orderNumber }}
                        @if ($externalId)
                            &nbsp;·&nbsp;
                            <code
                                style="background:rgba(255,255,255,.15);padding:1px 6px;border-radius:4px;font-size:.7rem;">{{ $externalId }}</code>
                        @endif
                        @if ($bookingRef)
                            &nbsp;·&nbsp;
                            PNR: <strong style="opacity:1;font-size:.82rem;">{{ $bookingRef }}</strong>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="bv-pill"
                    style="background:{{ $sBg }};color:{{ $sText }};">{{ $sLabel }}</span>
                <a href="{{ route('admin.booking.flight') }}"
                    style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.15);
                          color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:8px;
                          padding:6px 14px;font-size:.78rem;font-weight:600;text-decoration:none;">
                    ← Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- ── LEFT COLUMN ──────────────────────────────────────────── --}}
        <div class="col-lg-8">

            @foreach ($slices as $sliceIdx => $slice)
                @php
                    $segments = $slice['segments'] ?? [];
                    $firstS = $segments[0] ?? [];
                    $lastS = $segments[count($segments) - 1] ?? [];
                    $fareBrand = $slice['fare_brand_name'] ?? null;
                    $sliceStops = count($segments) - 1;
                    $sliceSecs = 0;
                    foreach ($segments as $sg) {
                        try {
                            $sliceSecs += \Carbon\CarbonInterval::make($sg['duration'] ?? 'PT0S')->totalSeconds;
                        } catch (\Exception $e) {
                        }
                    }
                    $sliceH = intdiv($sliceSecs, 3600);
                    $sliceM = intdiv($sliceSecs % 3600, 60);
                @endphp

                <div class="bv-card">
                    <div class="bv-card-header">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="bv-card-title">
                                ✈ {{ count($slices) > 1 ? 'Leg ' . ($sliceIdx + 1) : 'Flight Details' }}
                            </span>
                            @if ($fareBrand)
                                <span class="bv-tag">{{ $fareBrand }}</span>
                            @endif
                        </div>
                        <span style="font-size:.76rem;color:#64748b;">
                            {{ $sliceH }}h {{ $sliceM }}m &nbsp;·&nbsp;
                            {{ $sliceStops === 0 ? 'Non-stop' : $sliceStops . ' stop(s)' }}
                        </span>
                    </div>
                    <div class="bv-card-body">

                        {{-- Route Visual ────────────────────────────────── --}}
                        <div class="d-flex align-items-center mb-4" style="gap:16px;">
                            {{-- Origin --}}
                            <div style="flex:0 0 auto;min-width:80px;">
                                <div class="bv-iata">{{ $firstS['origin']['iata_code'] ?? '' }}</div>
                                <div class="bv-city">{{ $firstS['origin']['city_name'] ?? '' }}</div>
                                <div class="bv-time mt-1">
                                    {{ $firstS['departing_at'] ? \Carbon\Carbon::parse($firstS['departing_at'])->format('h:i A') : '' }}
                                </div>
                                <div class="bv-date-sm">
                                    {{ $firstS['departing_at'] ? \Carbon\Carbon::parse($firstS['departing_at'])->format('d M Y') : '' }}
                                </div>
                                @if (!empty($firstS['origin_terminal']))
                                    <div class="mt-1"><span class="bv-tag">T{{ $firstS['origin_terminal'] }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Middle --}}
                            <div class="flex-fill text-center">
                                <div style="font-size:.7rem;color:#94a3b8;margin-bottom:5px;">{{ $sliceH }}h
                                    {{ $sliceM }}m</div>
                                <div style="display:flex;align-items:center;">
                                    <div class="bv-sep-line"></div>
                                    <div
                                        style="width:7px;height:7px;border-radius:50%;background:#cbd5e1;flex-shrink:0;margin:0 2px;">
                                    </div>
                                    <div class="bv-sep-line"></div>
                                    <span style="font-size:.85rem;color:#94a3b8;padding:0 6px;flex-shrink:0;">✈</span>
                                    <div class="bv-sep-line"></div>
                                    <div
                                        style="width:7px;height:7px;border-radius:50%;background:#2563eb;flex-shrink:0;margin:0 2px;">
                                    </div>
                                    <div class="bv-sep-line"></div>
                                </div>
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:5px;">
                                    {{ $sliceStops === 0 ? 'Direct' : $sliceStops . ' stop(s)' }}
                                </div>
                            </div>

                            {{-- Destination --}}
                            <div style="flex:0 0 auto;min-width:80px;text-align:right;">
                                <div class="bv-iata">{{ $lastS['destination']['iata_code'] ?? '' }}</div>
                                <div class="bv-city">{{ $lastS['destination']['city_name'] ?? '' }}</div>
                                <div class="bv-time mt-1">
                                    {{ $lastS['arriving_at'] ? \Carbon\Carbon::parse($lastS['arriving_at'])->format('h:i A') : '' }}
                                </div>
                                <div class="bv-date-sm">
                                    {{ $lastS['arriving_at'] ? \Carbon\Carbon::parse($lastS['arriving_at'])->format('d M Y') : '' }}
                                </div>
                                @if (!empty($lastS['destination_terminal']))
                                    <div class="mt-1"><span
                                            class="bv-tag">T{{ $lastS['destination_terminal'] }}</span></div>
                                @endif
                            </div>
                        </div>

                        {{-- Segments ────────────────────────────────────── --}}
                        @foreach ($segments as $segIdx => $seg)
                            @php
                                $segSecs = 0;
                                try {
                                    $segSecs = \Carbon\CarbonInterval::make($seg['duration'] ?? 'PT0S')->totalSeconds;
                                } catch (\Exception $e) {
                                }
                                $segH = intdiv($segSecs, 3600);
                                $segM = intdiv($segSecs % 3600, 60);
                            @endphp
                            <div class="bv-seg-box" style="font-size:.8rem;">
                                <div class="d-flex justify-content-between flex-wrap align-items-start"
                                    style="gap:8px;">
                                    <div>
                                        <strong style="color:#0f172a;">{{ $seg['origin']['iata_code'] ?? '' }}</strong>
                                        @if (!empty($seg['origin_terminal']))
                                            <small class="text-muted">(T{{ $seg['origin_terminal'] }})</small>
                                        @endif
                                        <span style="color:#94a3b8;margin:0 5px;">→</span>
                                        <strong
                                            style="color:#0f172a;">{{ $seg['destination']['iata_code'] ?? '' }}</strong>
                                        @if (!empty($seg['destination_terminal']))
                                            <small class="text-muted">(T{{ $seg['destination_terminal'] }})</small>
                                        @endif
                                        <span class="text-muted ml-2" style="font-size:.76rem;">
                                            {{ $seg['departing_at'] ? \Carbon\Carbon::parse($seg['departing_at'])->format('h:i A, d M') : '' }}
                                            –
                                            {{ $seg['arriving_at'] ? \Carbon\Carbon::parse($seg['arriving_at'])->format('h:i A, d M') : '' }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <span class="bv-tag">{{ $segH }}h {{ $segM }}m</span>
                                        @if (!empty($seg['aircraft']['name']))
                                            <span class="bv-tag">{{ $seg['aircraft']['name'] }}</span>
                                        @endif
                                        @if (!empty($seg['passengers'][0]['cabin_class_marketing_name']))
                                            <span
                                                class="bv-tag">{{ $seg['passengers'][0]['cabin_class_marketing_name'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if (!empty($seg['passengers']))
                                    <div class="mt-2 d-flex flex-wrap" style="gap:6px;">
                                        @foreach ($seg['passengers'] as $pax)
                                            @php
                                                $pi = collect($passengers)->firstWhere('id', $pax['passenger_id']);
                                                $pn = $pi
                                                    ? trim(($pi['given_name'] ?? '') . ' ' . ($pi['family_name'] ?? ''))
                                                    : 'Pax';
                                            @endphp
                                            <div style="display:flex;align-items:center;gap:4px;font-size:.75rem;">
                                                <span style="color:#64748b;">{{ $pn }}:</span>
                                                @foreach ($pax['baggages'] as $bag)
                                                    <span
                                                        style="background:#e0e7ff;color:#3730a3;border-radius:4px;padding:1px 7px;font-size:.69rem;font-weight:600;">
                                                        {{ $bag['quantity'] }}×
                                                        {{ $bag['type'] === 'carry_on' ? 'Cabin' : 'Check-in' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if (!$loop->last)
                                @php
                                    $nextSeg = $segments[$segIdx + 1];
                                    $lSecs = \Carbon\Carbon::parse($seg['arriving_at'])->diffInSeconds(
                                        \Carbon\Carbon::parse($nextSeg['departing_at']),
                                    );
                                    $lh = intdiv($lSecs, 3600);
                                    $lm = intdiv($lSecs % 3600, 60);
                                @endphp
                                <div
                                    style="display:flex;align-items:center;gap:8px;margin:8px 0;font-size:.74rem;color:#94a3b8;">
                                    <div class="bv-sep-line"></div>
                                    <span
                                        style="background:#fef3c7;color:#92400e;border-radius:999px;padding:3px 10px;font-weight:600;white-space:nowrap;flex-shrink:0;">
                                        🕐 Layover {{ $lh }}h {{ $lm }}m ·
                                        {{ $seg['destination']['iata_code'] ?? '' }}
                                    </span>
                                    <div class="bv-sep-line"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Passengers ─────────────────────────────────────────────── --}}
            <div class="bv-card">
                <div class="bv-card-header">
                    <span class="bv-card-title">👤 Passengers</span>
                    <span class="bv-tag">{{ count($passengers) }}
                        traveller{{ count($passengers) !== 1 ? 's' : '' }}</span>
                </div>
                <div style="overflow:hidden;">
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.8rem;">
                            <thead>
                                <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        #</th>
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        Name</th>
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        Type</th>
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        DOB</th>
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        Contact</th>
                                    <th
                                        style="padding:10px 16px;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;border:none;">
                                        e-Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($passengers as $i => $pax)
                                    @php
                                        $ticket = collect($documents)->first(
                                            fn($d) => in_array($pax['id'], $d['passenger_ids'] ?? []),
                                        );
                                        $ticketNo = $ticket['unique_identifier'] ?? null;
                                        $isInfant = str_contains($pax['type'] ?? '', 'infant');
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:12px 16px;color:#94a3b8;font-weight:600;border:none;">
                                            {{ $i + 1 }}</td>
                                        <td style="padding:12px 16px;border:none;">
                                            <div style="font-weight:700;color:#0f172a;">
                                                {{ strtoupper($pax['title'] ?? '') }}
                                                {{ ucwords(strtolower(($pax['given_name'] ?? '') . ' ' . ($pax['family_name'] ?? ''))) }}
                                            </div>
                                            <div style="font-size:.7rem;color:#94a3b8;">
                                                {{ $pax['gender'] === 'm' ? 'Male' : 'Female' }}
                                            </div>
                                        </td>
                                        <td style="padding:12px 16px;border:none;">
                                            <span class="bv-pill"
                                                style="background:{{ $isInfant ? '#fce7f3' : '#dbeafe' }};color:{{ $isInfant ? '#9d174d' : '#1e40af' }};">
                                                {{ ucfirst(str_replace('_', ' ', $pax['type'] ?? '')) }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;color:#475569;border:none;">
                                            {{ $pax['born_on'] ? \Carbon\Carbon::parse($pax['born_on'])->format('d M Y') : 'N/A' }}
                                        </td>
                                        <td style="padding:12px 16px;border:none;">
                                            <div style="color:#0f172a;font-size:.79rem;">{{ $pax['email'] ?? 'N/A' }}
                                            </div>
                                            <div style="color:#94a3b8;font-size:.74rem;">
                                                {{ $pax['phone_number'] ?? '' }}</div>
                                        </td>
                                        <td style="padding:12px 16px;border:none;">
                                            @if ($ticketNo)
                                                <span class="bv-pill" style="background:#d1fae5;color:#065f46;">✓
                                                    #{{ $ticketNo }}</span>
                                            @else
                                                <span style="color:#cbd5e1;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Fare Rules ─────────────────────────────────────────────── --}}
            <div class="bv-card">
                <div class="bv-card-header">
                    <span class="bv-card-title">📋 Fare Rules</span>
                </div>
                <div class="bv-card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="bv-fare-box">
                                <div class="bv-fare-icon"
                                    style="background:{{ $refundable ? '#d1fae5' : '#fee2e2' }};">
                                    {{ $refundable ? '✅' : '❌' }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.82rem;color:#0f172a;">Refund before
                                        departure</div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="bv-fare-box">
                                <div class="bv-fare-icon"
                                    style="background:{{ $changeable ? '#d1fae5' : '#fee2e2' }};">
                                    {{ $changeable ? '✅' : '❌' }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.82rem;color:#0f172a;">Date change before
                                        departure</div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /col-lg-8 --}}

        {{-- ── RIGHT COLUMN ─────────────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Order Summary ──────────────────────────────────────────── --}}
            <div class="bv-card">
                <div class="bv-card-header">
                    <span class="bv-card-title">🧾 Order Summary</span>
                    <span class="bv-pill"
                        style="background:{{ $sBg }};color:{{ $sText }};">{{ $sLabel }}</span>
                </div>
                <div class="bv-card-body">
                    <div class="bv-kv">
                        <span class="bv-kv-label">Order No.</span>
                        <strong class="bv-kv-val">{{ $orderNumber }}</strong>
                    </div>
                    @if ($bookingRef)
                        <div class="bv-kv">
                            <span class="bv-kv-label">PNR / Ref</span>
                            <strong class="bv-kv-val" style="color:#2563eb;">{{ $bookingRef }}</strong>
                        </div>
                    @endif
                    <div class="bv-kv">
                        <span class="bv-kv-label">Booked On</span>
                        <span class="bv-kv-val" style="font-weight:500;">
                            {{ $bookingDate ? \Carbon\Carbon::parse($bookingDate)->format('d M Y, h:i A') : 'N/A' }}
                        </span>
                    </div>
                    <hr class="bv-divider">
                    <div class="bv-kv">
                        <span class="bv-kv-label">Base Fare</span>
                        <span class="bv-kv-val" style="font-weight:500;">{{ $currency }}
                            {{ number_format($baseAmount, 2) }}</span>
                    </div>
                    <div class="bv-kv">
                        <span class="bv-kv-label">Tax</span>
                        <span class="bv-kv-val" style="font-weight:500;">{{ $currency }}
                            {{ number_format($taxAmount, 2) }}</span>
                    </div>
                    @if ($platformFee > 0)
                        <div class="bv-kv">
                            <span class="bv-kv-label">Platform Fee</span>
                            <span class="bv-kv-val" style="font-weight:500;">{{ $currency }}
                                {{ number_format($platformFee, 2) }}</span>
                        </div>
                    @endif
                    @if ($addonsAmount > 0)
                        <div class="bv-kv">
                            <span class="bv-kv-label">Add-ons</span>
                            <span class="bv-kv-val" style="font-weight:500;">{{ $currency }}
                                {{ number_format($addonsAmount, 2) }}</span>
                        </div>
                    @endif
                    @if ($seatAmount > 0)
                        <div class="bv-kv">
                            <span class="bv-kv-label">Seats</span>
                            <span class="bv-kv-val" style="font-weight:500;">{{ $currency }}
                                {{ number_format($seatAmount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="bv-divider">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-weight:700;font-size:.88rem;color:#0f172a;">Total Amount</span>
                        <span style="font-weight:800;font-size:1.1rem;color:#2563eb;">{{ $currency }}
                            {{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Info ───────────────────────────────────────────── --}}
            @if (!empty($payment))
                <div class="bv-card">
                    <div class="bv-card-header">
                        <span class="bv-card-title">💳 Payment Info</span>
                        <span class="bv-pill"
                            style="background:{{ $payBg }};color:{{ $payTxt }};">{{ $payLabel }}</span>
                    </div>
                    <div class="bv-card-body">
                        <div class="bv-kv">
                            <span class="bv-kv-label">Method</span>
                            <span class="bv-kv-val"
                                style="font-weight:500;">{{ ucfirst($payment['payment_method'] ?? 'N/A') }}</span>
                        </div>
                        <div class="bv-kv">
                            <span class="bv-kv-label">Amount</span>
                            <span class="bv-kv-val">{{ $payment['currency'] ?? $currency }}
                                {{ number_format($payment['amount'] ?? 0, 2) }}</span>
                        </div>
                        @if (!empty($payment['payment_id']))
                            <div class="bv-kv" style="align-items:flex-start;">
                                <span class="bv-kv-label">Stripe ID</span>
                                <code
                                    style="font-size:.7rem;color:#475569;word-break:break-all;text-align:right;max-width:175px;">{{ $payment['payment_id'] }}</code>
                            </div>
                        @endif
                        @if (!empty($payment['paid_at']))
                            <div class="bv-kv">
                                <span class="bv-kv-label">Paid At</span>
                                <span class="bv-kv-val"
                                    style="font-weight:500;">{{ \Carbon\Carbon::parse($payment['paid_at'])->format('d M Y, h:i A') }}</span>
                            </div>
                        @endif
                        @if (!empty($payment['failure_reason']))
                            <div class="mt-2 p-2"
                                style="background:#fee2e2;color:#7f1d1d;font-size:.78rem;border-radius:8px;">
                                ⚠ {{ $payment['failure_reason'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Airline Info ───────────────────────────────────────────── --}}
            @if (!empty($owner))
                <div class="bv-card">
                    <div class="bv-card-header">
                        <span class="bv-card-title">✈ Airline</span>
                    </div>
                    <div class="bv-card-body d-flex align-items-center gap-3">
                        @if (!empty($owner['logo_lockup_url']))
                            <img src="{{ $owner['logo_lockup_url'] }}" alt="{{ $owner['name'] }}"
                                style="height:26px;width:auto;object-fit:contain;max-width:110px;">
                        @elseif(!empty($owner['logo_symbol_url']))
                            <img src="{{ $owner['logo_symbol_url'] }}" alt="{{ $owner['name'] }}"
                                style="height:34px;width:auto;object-fit:contain;">
                        @endif
                        <div>
                            <div style="font-weight:700;color:#0f172a;font-size:.86rem;">{{ $owner['name'] ?? '' }}
                            </div>
                            <div style="font-size:.74rem;color:#94a3b8;">{{ $owner['iata_code'] ?? '' }}</div>
                            @if (!empty($owner['conditions_of_carriage_url']))
                                <a href="{{ $owner['conditions_of_carriage_url'] }}" target="_blank"
                                    style="font-size:.74rem;color:#2563eb;">Conditions of Carriage ↗</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Update Status — COMMENTED OUT (uncomment when needed) --}}
            {{--
            <div class="bv-card">
                <div class="bv-card-header">
                    <span class="bv-card-title">🔄 Update Status</span>
                </div>
                <div class="bv-card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach (['confirmed', 'pending', 'cancelled', 'failed'] as $s)
                            <button
                                type="button"
                                onclick="confirmStatusChange({{ $order['id'] }}, '{{ $s }}', '{{ $this->getId() }}')"
                                class="btn btn-sm {{ $status === $s
                                    ? 'btn-' . $statusMap[$s][0]
                                    : 'btn-outline-secondary' }}"
                            >
                                {{ ucfirst($s) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            --}}

            {{-- Notes ─────────────────────────────────────────────────── --}}
            @if ($notes)
                <div class="bv-card">
                    <div class="bv-card-header">
                        <span class="bv-card-title">📝 Notes</span>
                    </div>
                    <div class="bv-card-body">
                        <p style="font-size:.83rem;color:#475569;margin:0;line-height:1.7;">{{ $notes }}</p>
                    </div>
                </div>
            @endif

        </div>{{-- /col-lg-4 --}}
    </div>
</div>
