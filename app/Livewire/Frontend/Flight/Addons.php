<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Support\Facades\Log;

class Addons extends Component
{
    public array  $selectedFlight  = [];
    public array  $passengers      = [];
    public array  $contact         = [];
    public int    $adults          = 1;
    public int    $children        = 0;
    public int    $infants         = 0;
    public string $currency        = '';
    public float  $baseTotal       = 0;
    public float  $platformFee     = 0;
    public float  $taxAmount     = 0;

    public array $availableServices = [];
    public array $selectedBaggage   = [];

    public bool $noServicesAvailable = false;
    public bool $fetchError          = false;
    public bool $isLoading = true;

    public function mount(): void {}

    public function loadData(): void
    {
        sleep(1);

        $session = session('passenger_info', []);
        $this->selectedFlight = $session['flight']     ?? [];
        $this->passengers     = $session['passengers'] ?? [];
        $this->contact        = $session['contact']    ?? [];
        $this->adults         = (int) ($session['adults']   ?? 1);
        $this->children       = (int) ($session['children'] ?? 0);
        $this->infants        = (int) ($session['infants']  ?? 0);
        $this->baseTotal   = (float) ($session['base_amount']    ?? $sf['base_amount']    ?? 0);
        $this->taxAmount   = (float) ($session['tax_amount']     ?? $sf['tax_amount']     ?? 0);
        $this->platformFee = (float) ($session['platform_fee']   ?? $sf['platform_fee']   ?? 0);

        $sf              = $this->selectedFlight;
        $this->currency  = $sf['total_currency'] ?? '';   

        Log::info('ADDONS loadData START', [
            'session_base_amount'  => $session['base_amount']  ?? 'NOT FOUND',
            'session_platform_fee' => $session['platform_fee'] ?? 'NOT FOUND',
            'sf_base_amount'       => $sf['base_amount']       ?? 'NOT FOUND',
            'sf_platform_fee'      => $sf['platform_fee']      ?? 'NOT FOUND',
            'sf_total_amount'      => $sf['total_amount']      ?? 'NOT FOUND',
            'final_baseTotal'      => $this->baseTotal,
            'final_platformFee'    => $this->platformFee,
        ]);

        $this->isLoading = false;

        $offerId = $this->selectedFlight['id'] ?? null;
        if ($offerId) {
            $this->fetchServices($offerId);
        } else {
            $this->noServicesAvailable = true;
        }
    }

    protected function fetchServices(string $offerId): void
    {
        try {
            $duffel = app(DuffelService::class);
            $result = $duffel->getOfferWithServices($offerId);

            if ($result['error']) {
                Log::error('Duffel getOfferWithServices error', [
                    'offer_id' => $offerId,
                    'error'    => $result['error'],
                ]);
                $this->fetchError = true;
                return;
            }

            $this->availableServices = $result['services'];

            if (! empty($result['offer'])) {
                $freshOffer = $result['offer'];

                $freshOffer['base_amount']  = $this->baseTotal;
                $freshOffer['platform_fee'] = $this->platformFee;

                $this->selectedFlight = $freshOffer;

                $paxSession = session('passenger_info', []);
                $paxSession['flight']       = $freshOffer;
                $paxSession['base_amount']  = $this->baseTotal;
                $paxSession['platform_fee'] = $this->platformFee;
                session(['passenger_info' => $paxSession]);
            }

            if (empty($this->availableServices)) {
                $this->noServicesAvailable = true;
            }
        } catch (\Throwable $e) {
            Log::error('Seats fetchSeatMaps exception: ' . $e->getMessage());
            $this->fetchError = true;
        }
    }

    public function getServicesForPassenger(string $passengerDuffelId): array
    {
        $matched = collect($this->availableServices)
            ->filter(fn($svc) => in_array($passengerDuffelId, $svc['passenger_ids'] ?? []))
            ->values()
            ->toArray();

        if (! empty($matched)) {
            return $matched;
        }

        $offerPassengers = $this->selectedFlight['passengers'] ?? [];
        $paxIndex        = null;

        foreach ($offerPassengers as $idx => $offerPax) {
            if (($offerPax['id'] ?? '') === $passengerDuffelId) {
                $paxIndex = $idx;
                break;
            }
        }

        if ($paxIndex !== null && isset($offerPassengers[$paxIndex])) {
            $offerPaxId = $offerPassengers[$paxIndex]['id'] ?? null;
            if ($offerPaxId) {
                $matched = collect($this->availableServices)
                    ->filter(fn($svc) => in_array($offerPaxId, $svc['passenger_ids'] ?? []))
                    ->values()
                    ->toArray();

                if (! empty($matched)) {
                    return $matched;
                }
            }
        }

        return collect($this->availableServices)->values()->toArray();
    }

    public function toggleBaggage(string $paxId, string $serviceId): void
    {
        $current = $this->selectedBaggage[$paxId] ?? [];

        if (in_array($serviceId, $current)) {
            $this->selectedBaggage[$paxId] = array_values(
                array_filter($current, fn($id) => $id !== $serviceId)
            );
        } else {
            $current[] = $serviceId;
            $this->selectedBaggage[$paxId] = $current;
        }
    }

    public function isSelected(string $paxId, string $serviceId): bool
    {
        return in_array($serviceId, $this->selectedBaggage[$paxId] ?? []);
    }

    public function getAddonsTotal(): float
    {
        $total = 0.0;
        foreach ($this->selectedBaggage as $serviceIds) {
            foreach ($serviceIds as $serviceId) {
                $svc    = $this->availableServices[$serviceId] ?? null;
                $total += $svc ? (float) ($svc['total_amount'] ?? 0) : 0;
            }
        }
        return $total;
    }

    public function continue(): void
    {
        $addonsTotal = $this->getAddonsTotal();

        $servicesToBook = [];
        foreach ($this->selectedBaggage as $serviceIds) {
            foreach ($serviceIds as $serviceId) {
                $servicesToBook[] = ['id' => $serviceId, 'quantity' => 1];
            }
        }

        $selectedAddons = [];
        foreach ($this->passengers as $pax) {
            $paxId = $pax['id'] ?? null;
            if (! $paxId || ($pax['type'] ?? '') === 'infant') continue;

            $serviceIds = $this->selectedBaggage[$paxId] ?? [];
            $labels     = [];
            $totalPrice = 0.0;
            $cur        = $this->currency;

            foreach ($serviceIds as $serviceId) {
                $svc = $this->availableServices[$serviceId] ?? null;
                if (! $svc) continue;
                $meta     = $svc['metadata'] ?? [];
                $labels[] = trim(($meta['maximum_weight_kg'] ?? '') . 'kg ' . ucfirst(str_replace('_', ' ', $meta['baggage_type'] ?? 'bag')));
                $totalPrice += (float) ($svc['total_amount'] ?? 0);
                $cur         = $svc['total_currency'] ?? $this->currency;
            }

            $selectedAddons[$paxId] = [
                'baggage_service_ids' => $serviceIds,
                'baggage_label'       => empty($labels) ? 'No extra baggage' : implode(', ', $labels),
                'baggage_price'       => $totalPrice,
                'baggage_currency'    => $cur,
                'baggage_service_id'  => $serviceIds[0] ?? null,
            ];
        }

        session([
            'addons_info' => [
                'flight'      => $this->selectedFlight,
                'passengers'  => $this->passengers,
                'contact'     => $this->contact,
                'adults'      => $this->adults,
                'children'    => $this->children,
                'infants'     => $this->infants,
                'addons'      => $selectedAddons,
                'services'    => $servicesToBook,
                'addonsTotal' => $addonsTotal,
                'currency'    => $this->currency,
                'base_amount'  => $this->baseTotal,
                'platform_fee' => $this->platformFee,
                'grandTotal' => $this->baseTotal + $this->taxAmount + $this->platformFee + $addonsTotal,
            ],
        ]);

        $this->redirect(route('airport.seats'));
    }

    public function render()
    {
        $addonsTotal = $this->getAddonsTotal();
        $grandTotal = $this->baseTotal + $this->taxAmount + $this->platformFee + $addonsTotal;

        $sf      = $this->selectedFlight;
        $slice   = $sf['slices'][0]      ?? [];
        $segment = $slice['segments'][0] ?? [];

        return view('livewire.frontend.flight.addons', [
            'sf'          => $sf,
            'slice'       => $slice,
            'segment'     => $segment,
            'addonsTotal' => $addonsTotal,
            'grandTotal'  => $grandTotal,
        ]);
    }
}
