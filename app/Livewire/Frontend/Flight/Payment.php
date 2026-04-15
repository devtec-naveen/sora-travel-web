<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    public string $paymentMethod  = 'stripe';
    public string $cardNumber     = '';
    public string $cardHolder     = '';
    public string $cardExpiry     = '';
    public string $cardCvv        = '';
    public bool   $isProcessing   = false;
    public bool   $paymentError   = false;
    public string $errorMessage   = '';
    public bool $isLoading = true;
    public float $platformFee = 0;
    public float $taxAmount = 0;

    protected $listeners = ['confirm-payment' => 'confirmPayment'];

    protected function rules(): array
    {
        $this->cardNumber = str_replace(' ', '', $this->cardNumber);
        return [
            'cardHolder' => 'required|string|min:2',
            'cardNumber' => ['required', 'regex:/^(\d{4}\s?){3,4}\d{1,4}$/'],
            'cardExpiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cardCvv'    => 'required|digits_between:3,4',
        ];
    }

    public function mount(): void {}

    public function loadData(): void
    {
        sleep(1);
        $bookingInfo = session('booking_info', []);
        $seatsInfo   = session('seats_info',   []);
        $addonsInfo  = session('addons_info',  []);
        $selFlight   = session('selected_flight', []);

        $source = $bookingInfo ?: $seatsInfo ?: $addonsInfo;

        $this->selectedFlight = $source['flight']     ?? [];
        $this->passengers     = $source['passengers'] ?? [];
        $this->contact        = $source['contact']    ?? [];
        $this->adults         = (int) ($source['adults']   ?? 1);
        $this->children       = (int) ($source['children'] ?? 0);
        $this->infants        = (int) ($source['infants']  ?? 0);
        $this->services       = $source['services']   ?? [];

        $sf             = $this->selectedFlight;
        $this->currency = $sf['total_currency'] ?? $source['currency'] ?? '';

        $this->baseTotal   = (float) ($source['base_amount']  ?? $selFlight['base_amount']  ?? $sf['base_amount']  ?? 0);
        $this->taxAmount   = (float) ($source['tax_amount']   ?? $selFlight['tax_amount']   ?? $sf['tax_amount']   ?? 0);
        $this->platformFee = (float) ($source['platform_fee'] ?? $selFlight['platform_fee'] ?? $sf['platform_fee'] ?? 0);
        $this->addonsTotal = (float) ($source['addonsTotal']  ?? 0);
        $this->seatTotal   = (float) ($source['seatTotal']    ?? 0);
        $this->grandTotal  = $this->baseTotal + $this->taxAmount + $this->platformFee + $this->addonsTotal + $this->seatTotal;

        $this->isLoading = false;
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function pay()
    {
        if (!Auth::check()) {
            $this->dispatch('require-login');
            return;
        }
        $this->validate();
        $this->isProcessing = true;

        try {
            $bookingInfo = session('booking_info', []);
            $services = $bookingInfo['services'] ?? [];

            $orderService = app(OrderService::class);
            $response = $orderService->create([
                'user_id'      => Auth::id(),
                'currency'     => $bookingInfo['currency']     ?? $this->currency,
                'base_amount'  => $bookingInfo['base_amount']  ?? $this->baseTotal,
                'tax_amount'   => $bookingInfo['tax_amount']   ?? $this->taxAmount,
                'addons_total' => $bookingInfo['addonsTotal']  ?? $this->addonsTotal,
                'seat_total'   => $bookingInfo['seatTotal']    ?? $this->seatTotal,
                'platform_fee' => $bookingInfo['platform_fee'] ?? $this->platformFee,
                'card_number'  => str_replace(' ', '', $this->cardNumber),
                'exp_month'    => explode('/', $this->cardExpiry)[0],
                'exp_year'     => '20' . explode('/', $this->cardExpiry)[1],
                'cvc'          => $this->cardCvv,
                'card_holder'  => $this->cardHolder,
            ]);
            $orderPassengers = $this->mapPassengersForDuffel($this->passengers, $this->selectedFlight, $this->contact);
            $orderService->confirmPayment($response['payment_id'], $this->selectedFlight['id'], $orderPassengers, $services);
            session()->forget(['booking_info', 'addons_info', 'seats_info']);
            return redirect()->route('airport.confirmation');
        } catch (\Throwable $e) {
            $this->paymentError = true;
            $this->errorMessage = $e->getMessage();
        } finally {
            $this->isProcessing = false;
        }
    }

    protected function mapPassengersForDuffel(array $formPassengers, array $flight, array $contact): array
    {
        $orderPassengers = [];
        $offerPaxs       = $flight['passengers'] ?? [];

        $adultCount   = 0;
        $childCount   = 0;
        $infantCount  = 0;
        foreach ($offerPaxs as $pax) {
            $t = $pax['type'] ?? 'adult';
            if (str_starts_with($t, 'infant'))  $infantCount++;
            elseif ($t === 'child')             $childCount++;
            else                                $adultCount++;
        }

        $adultOffset  = 0;
        $childOffset  = $adultCount;
        $infantOffset = $adultCount + $childCount;

        $typeCounters = ['adult' => 0, 'child' => 0, 'infant' => 0];
        $infantIds    = [];
        $infantIndex  = 0;

        foreach ($offerPaxs as $pax) {
            if (str_starts_with($pax['type'] ?? '', 'infant')) {
                $infantIds[] = $pax['id'];
            }
        }

        foreach ($offerPaxs as $offerPax) {
            $offerType      = $offerPax['type'] ?? 'adult';
            $normalizedType = str_starts_with($offerType, 'infant') ? 'infant' : $offerType;

            $formIndex = match ($normalizedType) {
                'child'  => $childOffset  + $typeCounters['child'],
                'infant' => $infantOffset + $typeCounters['infant'],
                default  => $adultOffset  + $typeCounters['adult'],
            };

            $typeCounters[$normalizedType]++;

            $formPax = $formPassengers[$formIndex] ?? null;

            if (!$formPax) {
                Log::warning("mapPassengersForDuffel: no form passenger at index {$formIndex} for type {$normalizedType}");
                continue;
            }

            $bornOn = $formPax['dob'] ?? $formPax['born_on'] ?? '';
            try {
                $bornOn = $bornOn ? \Carbon\Carbon::parse($bornOn)->format('Y-m-d') : '';
            } catch (\Throwable) {
            }

            $orderPax = [
                'id'           => $offerPax['id'],
                'title'        => strtolower($formPax['title'] ?? 'mr'),
                'given_name'   => $formPax['given_name'] ?? $formPax['first_name'] ?? '',
                'family_name'  => $formPax['family_name'] ?? $formPax['last_name'] ?? '',
                'gender'       => match (strtolower($formPax['gender'] ?? 'm')) {
                    'male', 'm'   => 'm',
                    'female', 'f' => 'f',
                    default      => 'm',
                },
                'born_on'      => $bornOn,
                'email'        => $contact['email'] ?? '',
                'phone_number' => ($contact['phone_code'] ?? '') . ($contact['phone'] ?? ''),
            ];

            if ($normalizedType === 'adult' && isset($infantIds[$infantIndex])) {
                $orderPax['infant_passenger_id'] = $infantIds[$infantIndex];
                $infantIndex++;
            }

            $orderPassengers[] = $orderPax;
        }

        Log::info('mapPassengersForDuffel result', [
            'form_passenger_count'  => count($formPassengers),
            'offer_passenger_count' => count($offerPaxs),
            'adult_count'           => $adultCount,
            'child_count'           => $childCount,
            'infant_count'          => $infantCount,
            'mapped'                => array_map(fn($p) => [
                'id'      => $p['id'],
                'born_on' => $p['born_on'],
                'type_inferred' => match (true) {
                    \Carbon\Carbon::parse($p['born_on'])->age < 2  => 'infant',
                    \Carbon\Carbon::parse($p['born_on'])->age < 12 => 'child',
                    default => 'adult',
                },
            ], $orderPassengers),
        ]);

        return $orderPassengers;
    }

    public function render()
    {
        $sf      = $this->selectedFlight;
        $slice   = $sf['slices'][0]      ?? [];
        $segment = $slice['segments'][0] ?? [];

        return view('livewire.frontend.flight.payment', [
            'segment'     => $segment,
            'slice'       => $slice,
            'taxAmount'   => $this->taxAmount,
            'platformFee' => $this->platformFee,
        ]);
    }
}
