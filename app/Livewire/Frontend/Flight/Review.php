<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;

class Review extends Component
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
    public float  $seatTotal      = 0;
    public float  $grandTotal     = 0;
    public float $taxAmount    = 0;
    public float $platformFee  = 0;
    public array  $addons         = [];
    public array  $selectedSeats  = [];
    public array  $services       = [];
    public bool $isLoading = true;

    public function mount(): void {}

    public function loadData(): void
    {
        sleep(1);
        $seatsInfo  = session('seats_info',  []);
        $addonsInfo = session('addons_info', []);
        $paxInfo    = session('passenger_info', []);
        $selFlight  = session('selected_flight', []);

        $source = $seatsInfo ?: $addonsInfo ?: $paxInfo;

        $this->selectedFlight = $source['flight']     ?? $paxInfo['flight']     ?? [];
        $this->passengers     = $source['passengers'] ?? $paxInfo['passengers'] ?? [];
        $this->contact        = $source['contact']    ?? $paxInfo['contact']    ?? [];
        $this->adults         = (int) ($source['adults']   ?? $paxInfo['adults']   ?? 1);
        $this->children       = (int) ($source['children'] ?? $paxInfo['children'] ?? 0);
        $this->infants        = (int) ($source['infants']  ?? $paxInfo['infants']  ?? 0);

        $sf             = $this->selectedFlight;
        $this->currency = $sf['total_currency'] ?? $source['currency'] ?? '';

        $this->baseTotal   = (float) ($source['base_amount']  ?? $selFlight['base_amount']  ?? $sf['base_amount']  ?? 0);
        $this->taxAmount   = (float) ($source['tax_amount']   ?? $selFlight['tax_amount']   ?? $sf['tax_amount']   ?? 0);
        $this->platformFee = (float) ($source['platform_fee'] ?? $selFlight['platform_fee'] ?? $sf['platform_fee'] ?? 0);
        $this->addonsTotal = (float) ($source['addonsTotal']  ?? 0);
        $this->seatTotal   = (float) ($seatsInfo['seatTotal'] ?? 0);
        $this->grandTotal  = $this->baseTotal + $this->taxAmount + $this->platformFee + $this->addonsTotal + $this->seatTotal;

        $this->addons        = $source['addons']        ?? [];
        $this->selectedSeats = $seatsInfo['selectedSeats'] ?? [];
        $this->services      = $source['services']      ?? [];

        $this->isLoading = false;
    }

    public function confirm(): void
    {
        session([
            'booking_info' => [
                'flight'        => $this->selectedFlight,
                'passengers'    => $this->passengers,
                'contact'       => $this->contact,
                'adults'        => $this->adults,
                'children'      => $this->children,
                'infants'       => $this->infants,
                'addons'        => $this->addons,
                'selectedSeats' => $this->selectedSeats,
                'services'      => $this->services,
                'base_amount'   => $this->baseTotal,    // Fare excluding taxes
                'tax_amount'    => $this->taxAmount,    // Tax only
                'platform_fee'  => $this->platformFee,  // Platform commission
                'addonsTotal'   => $this->addonsTotal,
                'seatTotal'     => $this->seatTotal,
                'grandTotal'    => $this->grandTotal,   // Final amount charged to user
                'currency'      => $this->currency,
            ],
        ]);

        $this->redirect(route('airport.payment'));
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        return view('livewire.frontend.flight.review', [
            'slices'      => $sf['slices'] ?? [],
            'taxAmount'   => $this->taxAmount,
            'platformFee' => $this->platformFee,
        ]);
    }
}
