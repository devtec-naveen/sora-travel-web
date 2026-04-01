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
    public array  $addons         = [];  
    public array  $selectedSeats  = [];   
    public array  $services       = [];
    public bool $isLoading = true;

    public function mount():void 
    {

    }

    public function loadData(): void
    {
        sleep(1);
        $seatsInfo  = session('seats_info',  []);
        $addonsInfo = session('addons_info', []);
        $paxInfo    = session('passenger_info', []);
        $source = $seatsInfo ?: $addonsInfo;

        $this->selectedFlight = $source['flight']     ?? $paxInfo['flight']     ?? [];
        $this->passengers     = $source['passengers'] ?? $paxInfo['passengers'] ?? [];
        $this->contact        = $source['contact']    ?? $paxInfo['contact']    ?? [];
        $this->adults         = (int) ($source['adults']   ?? $paxInfo['adults']   ?? 1);
        $this->children       = (int) ($source['children'] ?? $paxInfo['children'] ?? 0);
        $this->infants        = (int) ($source['infants']  ?? $paxInfo['infants']  ?? 0);

        $sf               = $this->selectedFlight;
        $this->currency   = $sf['total_currency'] ?? $source['currency'] ?? '';
        $this->baseTotal  = (float) ($sf['total_amount'] ?? 0);
        $this->addonsTotal= (float) ($source['addonsTotal'] ?? 0);
        $this->seatTotal  = (float) ($seatsInfo['seatTotal'] ?? 0);
        $this->grandTotal = (float) ($source['grandTotal']  ?? ($this->baseTotal + $this->addonsTotal + $this->seatTotal));
        $this->addons     = $source['addons']        ?? [];
        $this->selectedSeats = $seatsInfo['selectedSeats'] ?? [];
        $this->services   = $source['services']      ?? [];
    }

    public function confirm(): void
    {
        session([
            'booking_info' => [
                'flight'       => $this->selectedFlight,
                'passengers'   => $this->passengers,
                'contact'      => $this->contact,
                'adults'       => $this->adults,
                'children'     => $this->children,
                'infants'      => $this->infants,
                'addons'       => $this->addons,
                'selectedSeats'=> $this->selectedSeats,
                'services'     => $this->services,
                'addonsTotal'  => $this->addonsTotal,
                'seatTotal'    => $this->seatTotal,
                'grandTotal'   => $this->grandTotal,
                'currency'     => $this->currency,
            ],
        ]);

        $this->redirect(route('airport.payment'));
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        $slices  = $sf['slices'] ?? [];

        return view('livewire.frontend.flight.review', [
            'slices' => $slices,
        ]);
    }
}