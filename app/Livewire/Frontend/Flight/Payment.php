<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Payment extends Component
{
    public array  $selectedFlight = [];
    public array  $passengers     = [];
    public array  $contact        = [];
    public string $currency       = '';
    public float  $baseTotal      = 0;
    public float  $addonsTotal    = 0;
    public float  $seatTotal      = 0;
    public float  $grandTotal     = 0;
    public array  $services       = [];
    public int    $adults         = 1;
    public int    $children       = 0;
    public int    $infants        = 0;

    public string $paymentMethod  = 'card';
    public string $cardNumber     = '';
    public string $cardHolder     = '';
    public string $cardExpiry     = '';
    public string $cardCvv        = '';

    public bool   $isProcessing   = false;
    public bool   $paymentError   = false;
    public string $errorMessage   = '';

    public function mount(): void
    {
        $bookingInfo = session('booking_info', []);
        $seatsInfo   = session('seats_info',   []);
        $addonsInfo  = session('addons_info',  []);

        $source = $bookingInfo ?: $seatsInfo ?: $addonsInfo;

        $this->selectedFlight = $source['flight']     ?? [];
        $this->passengers     = $source['passengers'] ?? [];
        $this->contact        = $source['contact']    ?? [];
        $this->adults         = (int) ($source['adults']   ?? 1);
        $this->children       = (int) ($source['children'] ?? 0);
        $this->infants        = (int) ($source['infants']  ?? 0);
        $this->services       = $source['services']   ?? [];

        $sf                = $this->selectedFlight;
        $this->currency    = $sf['total_currency']    ?? $source['currency'] ?? '';
        $this->baseTotal   = (float) ($sf['total_amount']    ?? 0);
        $this->addonsTotal = (float) ($source['addonsTotal'] ?? 0);
        $this->seatTotal   = (float) ($source['seatTotal']   ?? 0);
        $this->grandTotal  = (float) ($source['grandTotal']  ?? ($this->baseTotal + $this->addonsTotal + $this->seatTotal));
    }

    public function pay(): void
    {
        if (! Auth::check()) {
            $this->dispatch('require-login');
            return;
        }

        $this->isProcessing = true;
        $this->paymentError = false;
        $this->errorMessage = '';

        try {
            $this->createDuffelOrder();
        } catch (\Throwable $e) {
            Log::error('Payment failed', [
                'message'  => $e->getMessage(),
                'offer_id' => $this->selectedFlight['id'] ?? null,
                'passengers_debug' => collect($this->passengers)->map(fn($p) => [
                    'type'    => $p['type']    ?? null,
                    'born_on' => $p['born_on'] ?? $p['dob'] ?? null,
                    'gender'  => $p['gender']  ?? null,
                ])->toArray(),
            ]);
            $this->paymentError = true;
            $this->errorMessage = $e->getMessage();
        } finally {
            $this->isProcessing = false;
        }
    }

    protected function createDuffelOrder(): void
    {
        $duffel = app(DuffelService::class);

        $sf        = $this->selectedFlight;
        $offerId   = $sf['id']         ?? null;
        $offerPaxs = $sf['passengers'] ?? [];

        if (! $offerId) {
            throw new \Exception('Offer ID missing.');
        }

        $orderPassengers = [];
        $formPaxByType   = [];

        foreach ($this->passengers as $pax) {
            $type = $pax['type'] ?? 'adult';
            $formPaxByType[$type][] = $pax;
        }

        $typeCounters = [];

        $infantIds = [];
        $infantIndex = 0;

        foreach ($offerPaxs as $pax) {
            if (str_starts_with($pax['type'], 'infant')) {
                $infantIds[] = $pax['id'];
            }
        }        

        foreach ($offerPaxs as $offerPax) {
            $offerType      = $offerPax['type'] ?? 'adult';
            $normalizedType = str_starts_with($offerType, 'infant') ? 'infant' : $offerType;

            $typeCounters[$normalizedType] = $typeCounters[$normalizedType] ?? 0;
            $formPax = $formPaxByType[$normalizedType][$typeCounters[$normalizedType]] ?? null;
            $typeCounters[$normalizedType]++;

            if (!$formPax) {
               continue;
            }

            $rawDob = $formPax['born_on'] ?? $formPax['dob'] ?? '';
            $bornOn = '';
            if ($rawDob) {
                try {
                    $bornOn = Carbon::parse($rawDob)->format('Y-m-d');
                } catch (\Throwable $e) {
                    $bornOn = $rawDob;
                }
            }

            $orderPax = [
                'id'           => $offerPax['id'],
                'title'        => strtolower($formPax['title'] ?? 'mr'),
                'given_name'   => $formPax['given_name']  ?? $formPax['first_name']  ?? '',
                'family_name'  => $formPax['family_name'] ?? $formPax['last_name']   ?? '',
                'gender'       => match(strtolower($formPax['gender'] ?? 'm')) {
                    'male', 'm'   => 'm',
                    'female', 'f' => 'f',
                    default       => 'm',
                },
                'born_on'      => $bornOn,
                'email'        => $this->contact['email'] ?? '',
                'phone_number' => ($this->contact['phone_code'] ?? '') . ($this->contact['phone'] ?? ''),
            ];

            if ($normalizedType === 'adult' && isset($infantIds[$infantIndex])) {
                $orderPax['infant_passenger_id'] = $infantIds[$infantIndex];
                $infantIndex++;
            }

            $passportNo = $formPax['identity_documents'][0]['unique_identifier'] ?? $formPax['passport_no'] ?? null;
            $passportNo = $formPax['identity_documents'][0]['unique_identifier']    ?? $formPax['passport_no']     ?? null;
            $expiresOn  = $formPax['identity_documents'][0]['expires_on']           ?? $formPax['passport_expiry'] ?? null;
            $issuing    = $formPax['identity_documents'][0]['issuing_country_code'] ?? $formPax['nationality']     ?? null;

            if ($passportNo && $expiresOn) {
                try {
                    $expiresOn = Carbon::parse($expiresOn)->format('Y-m-d');
                } catch (\Throwable $e) {}

                $orderPax['identity_documents'] = [[
                    'unique_identifier'    => $passportNo,
                    'expires_on'           => $expiresOn,
                    'issuing_country_code' => strtoupper($issuing ?? 'IN'),
                    'type'                 => 'passport',
                ]];
            }

            $orderPassengers[] = $orderPax;
        }

        $response = $duffel->createOrder([
            'offer_id'   => $offerId,
            'passengers' => $orderPassengers,
            'services'   => $this->services,
            'services_amount' => $this->addonsTotal + $this->seatTotal,
            'amount'     => (string) $this->grandTotal,
            'currency'   => $this->currency,
            'contact'    => $this->contact,
        ]);

        if (! empty($response['errors'])) {
            $error = $response['errors'][0] ?? [];
            $msg   = $error['message'] ?? 'Order creation failed.';

            Log::error('Duffel createOrder error', [
                'offer_id'           => $offerId,
                'error'              => $error,
                'payload_passengers' => collect($orderPassengers)->map(fn($p) => [
                    'id'         => $p['id']        ?? null,
                    'born_on'    => $p['born_on']   ?? null,
                    'given_name' => $p['given_name'] ?? null,
                    'gender'     => $p['gender']    ?? null,
                ])->toArray(),
            ]);

            throw new \Exception($msg);
        }

        $order = $response['data'] ?? [];

        session()->forget(['passenger_info', 'addons_info', 'seats_info', 'booking_info']);
        session(['last_order' => $order]);

        $this->redirect(route('airport.confirmation'));
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        $slice   = $sf['slices'][0]      ?? [];
        $segment = $slice['segments'][0] ?? [];

        return view('livewire.frontend.flight.payment', [
            'segment' => $segment,
            'slice'   => $slice,
        ]);
    }
}