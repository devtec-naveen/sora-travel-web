<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Support\Facades\Log;

class Seats extends Component
{
    public array  $selectedFlight = [];
    public array  $passengers     = [];
    public array  $contact        = [];
    public int    $adults         = 1;
    public int    $children       = 0;
    public int    $infants        = 0;
    public string $currency       = '';
    public float  $baseTotal      = 0;
    public float  $addonsTotal    = 0;
    public array  $addonsInfo     = [];
    public array $seatMaps        = [];
    public int   $activeMapIndex  = 0;
    public array $selectedSeats   = [];
    public bool   $noSeatsAvailable  = false;
    public bool   $fetchError        = false;
    public string $activePassengerKey = '';
    public bool $isLoading = true;
    public array $passengerMeta = [];
    public float $platformFee = 0;


    public function mount(): void {}

    public function loadData(): void
    {
        sleep(1);

        $session    = session('passenger_info', []);
        $addonsInfo = session('addons_info', []);

        $this->selectedFlight = $addonsInfo['flight']     ?? $session['flight']     ?? [];
        $this->passengers     = $addonsInfo['passengers'] ?? $session['passengers'] ?? [];
        $this->contact        = $addonsInfo['contact']    ?? $session['contact']    ?? [];
        $this->adults         = (int) ($addonsInfo['adults']   ?? $session['adults']   ?? 1);
        $this->children       = (int) ($addonsInfo['children'] ?? $session['children'] ?? 0);
        $this->infants        = (int) ($addonsInfo['infants']  ?? $session['infants']  ?? 0);
        $this->addonsInfo     = $addonsInfo;

        $sf             = $this->selectedFlight;
        $this->currency = $sf['total_currency'] ?? '';

        $this->baseTotal   = (float) ($addonsInfo['base_amount']  ?? $session['base_amount']  ?? $sf['base_amount']  ?? 0);
        $this->platformFee = (float) ($addonsInfo['platform_fee'] ?? $session['platform_fee'] ?? $sf['platform_fee'] ?? 0);
        $this->addonsTotal = (float) ($addonsInfo['addonsTotal']  ?? 0);

        foreach ($this->passengers as $idx => $pax) {
            if (($pax['type'] ?? '') === 'infant') continue;

            $duffelId = $pax['id'] ?? null;
            $paxKey   = $duffelId ?: "pax_{$idx}";
            $name     = trim(($pax['first_name'] ?? $pax['given_name'] ?? '') . ' ' . ($pax['last_name'] ?? $pax['family_name'] ?? ''))
                ?: ucfirst($pax['type'] ?? 'Passenger') . ' ' . ($idx + 1);

            $this->passengerMeta[$paxKey] = [
                'name'      => $name,
                'type'      => $pax['type'] ?? 'adult',
                'duffel_id' => $duffelId,
                'index'     => $idx,
            ];
        }

        $this->activePassengerKey = array_key_first($this->passengerMeta) ?? '';

        $savedSeats   = session('seats_info.selectedSeats', []);
        $segmentCount = count($sf['slices'] ?? []);

        foreach (range(0, max($segmentCount - 1, 0)) as $si) {
            $this->selectedSeats[$si] = [];
            foreach (array_keys($this->passengerMeta) as $paxKey) {
                $this->selectedSeats[$si][$paxKey] = $savedSeats[$si][$paxKey] ?? null;
            }
        }

        $offerId = $sf['id'] ?? null;
        if ($offerId) {
            $this->fetchSeatMaps($offerId);
        } else {
            $this->noSeatsAvailable = true;
        }

        $this->isLoading = false;
    }

    protected function fetchSeatMaps(string $offerId): void
    {
        try {
            /** @var DuffelService $duffel */
            $duffel = app(DuffelService::class);
            $result = $duffel->getSeatMaps($offerId);

            if ($result['error']) {
                Log::error('Duffel getSeatMaps error', [
                    'offer_id' => $offerId,
                    'error'    => $result['error'],
                ]);
                $this->fetchError = true;
                return;
            }

            $this->seatMaps = $result['seat_maps'];

            if (empty($this->seatMaps)) {
                $this->noSeatsAvailable = true;
            }
        } catch (\Throwable $e) {
            Log::error('Seats fetchSeatMaps exception: ' . $e->getMessage());
            $this->fetchError = true;
        }
    }

    public function selectSeat(int $mapIndex, string $designator, string $serviceId, float $amount, string $cur): void
    {
        $paxKey = $this->activePassengerKey;
        if (! $paxKey) return;

        $current = $this->selectedSeats[$mapIndex][$paxKey] ?? null;
        if ($current && $current['designator'] === $designator) {
            $this->selectedSeats[$mapIndex][$paxKey] = null;
            return;
        }

        foreach ($this->selectedSeats[$mapIndex] as $pk => $seat) {
            if ($pk !== $paxKey && $seat && $seat['designator'] === $designator) {
                return;
            }
        }

        $this->selectedSeats[$mapIndex][$paxKey] = [
            'designator' => $designator,
            'service_id' => $serviceId ?: null,
            'amount'     => $amount,
            'currency'   => $cur,
        ];
    }

    public function setActivePassenger(string $paxKey): void
    {
        $this->activePassengerKey = $paxKey;
    }

    public function setActiveMap(int $index): void
    {
        $this->activeMapIndex     = $index;
        $this->activePassengerKey = array_key_first($this->passengerMeta) ?? '';
    }

    public function getSeatTotal(): float
    {
        $total = 0.0;
        foreach ($this->selectedSeats as $segSeats) {
            foreach ($segSeats as $seat) {
                $total += $seat ? (float) ($seat['amount'] ?? 0) : 0;
            }
        }
        return $total;
    }

    public function skipSeats(): void
    {
        $this->saveAndRedirect([], 0.0);
    }

    public function continue(): void
    {
        $seatServices = [];
        foreach ($this->selectedSeats as $segSeats) {
            foreach ($segSeats as $seat) {
                if ($seat && ! empty($seat['service_id'])) {
                    $seatServices[] = ['id' => $seat['service_id'], 'quantity' => 1];
                }
            }
        }
        $this->saveAndRedirect($seatServices, $this->getSeatTotal());
    }

    protected function saveAndRedirect(array $seatServices, float $seatTotal): void
    {
        $addonsServices = $this->addonsInfo['services'] ?? [];

        session([
            'seats_info' => [
                'flight'        => $this->selectedFlight,
                'passengers'    => $this->passengers,
                'contact'       => $this->contact,
                'adults'        => $this->adults,
                'children'      => $this->children,
                'infants'       => $this->infants,
                'addons'        => $this->addonsInfo['addons'] ?? [],
                'services'      => array_merge($addonsServices, $seatServices),
                'selectedSeats' => $this->selectedSeats,
                'addonsTotal'   => $this->addonsTotal,
                'seatTotal'     => $seatTotal,
                'grandTotal'    => $this->baseTotal + $this->platformFee + $this->addonsTotal + $seatTotal,
                'currency'      => $this->currency,
                'base_amount'   => $this->baseTotal,    
                'platform_fee'  => $this->platformFee, 
            ],
        ]);

        $this->redirect(route('airport.review'));
    }

    public function render()
    {
        $seatTotal  = $this->getSeatTotal();
        $grandTotal = $this->platformFee + $this->baseTotal + $this->addonsTotal + $seatTotal;

        return view('livewire.frontend.flight.seats', [
            'seatTotal'  => $seatTotal,
            'grandTotal' => $grandTotal,
        ]);
    }
}
