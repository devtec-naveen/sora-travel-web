<?php

namespace App\Services\Common;

use App\Jobs\SendEmail;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Repositories\Common\Auth\AuthRepository;
use App\Services\Common\Duffel\DuffelService;
use App\Services\Common\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;

class OrderService
{
    public function __construct(protected AuthRepository $authRepo, protected DuffelService $duffel, protected PaymentService $stripe)
    {
        $this->duffel  = $duffel;
        $this->stripe  = $stripe;
        $this->authRepo  = $authRepo;
    }

    public function create(array $data)
    {
        $baseAmount   = (float) ($data['base_amount']  ?? 0);
        $currency     = $data['currency'] ?? 'usd';
        $addonsAmount = (float) ($data['addons_total'] ?? 0);
        $seatAmount   = (float) ($data['seat_total']   ?? 0);
        $platformFee  = (float) ($data['platform_fee'] ?? 0);
        $taxAmount    = (float) ($data['tax_amount']   ?? 0);

        $totalAmount = $baseAmount + $taxAmount + $addonsAmount + $seatAmount + $platformFee;

        if ($totalAmount <= 0) {
            throw new \Exception('Invalid amount: ' . $totalAmount);
        }

        [$order, $payment] = DB::transaction(function () use ($data, $baseAmount, $addonsAmount, $seatAmount, $platformFee, $totalAmount, $currency, $taxAmount) {
            $payment = PaymentModel::create([
                'user_id'        => $data['user_id'],
                'payment_id'     => 'PENDING-' . Str::uuid(),
                'payment_method' => 'stripe',
                'tax_amount'     => $taxAmount,
                'base_amount'    => $baseAmount,
                'platform_fee'   => $platformFee,
                'amount'         => $totalAmount,
                'currency'       => $currency,
                'status'         => 'pending',
            ]);

            $order = OrderModel::create([
                'user_id'           => $data['user_id'],
                'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                'payment_id'        => $payment->id,
                'base_amount'       => $baseAmount,
                'tax_amount'        => $taxAmount,
                'addons_amount'     => $addonsAmount,
                'seat_amount'       => $seatAmount,
                'platform_fee'      => $platformFee,
                'total_amount'      => $totalAmount,
                'amount'            => $totalAmount,
                'currency'          => $currency,
                'status'            => 'pending',
                'payment_intent_id' => null,
            ]);

            return [$order, $payment];
        });

        $intent = $this->stripe->createPaymentIntent([
            'amount'     => $totalAmount,
            'currency'   => $currency,
            'payment_id' => $payment->id,
            'card_number' => $data['card_number'] ?? null,
            'exp_month'   => $data['exp_month'] ?? null,
            'exp_year'    => $data['exp_year'] ?? null,
            'cvc'         => $data['cvc'] ?? null,
            'card_holder' => $data['card_holder'] ?? null,
        ]);

        $order->update(['payment_intent_id' => $intent->id]);

        Log::info('Stripe PaymentIntent Created', [
            'order_id'   => $order->id,
            'payment_id' => $payment->id,
            'intent'     => $intent,
        ]);

        return [
            'order_id'      => $order->id,
            'payment_id'    => $payment->id,
            'client_secret' => $intent->client_secret,
        ];
    }

    public function confirmPayment($paymentId, $offerId, array $passengers, array $services = [])
    {
        if (!$offerId) {
            throw new \Exception('offer_id is missing');
        }

        $order = DB::transaction(function () use ($paymentId, $offerId, $passengers, $services) {
            $payment = PaymentModel::lockForUpdate()->findOrFail($paymentId);
            $order   = OrderModel::lockForUpdate()->where('payment_id', $payment->id)->firstOrFail();

            if ($order->status === 'confirmed') {
                return $order;
            }

            $offerAmountStr = number_format(
                (float) $order->base_amount + (float) $order->tax_amount + (float) $order->seat_amount + (float) $order->addons_amount,
                2,
                '.',
                ''
            );
            $offerCurrency = strtoupper($order->currency);

            $formattedPassengers = array_map(function ($p, $index) {
                $bornOnRaw = $p['dob'] ?? $p['born_on'] ?? '';
                $bornOn    = '';

                try {
                    $bornOn = $bornOnRaw ? \Carbon\Carbon::parse($bornOnRaw)->format('Y-m-d') : '';
                } catch (\Throwable $e) {
                    Log::error("Passenger [{$index}] born_on parse error", ['raw' => $bornOnRaw, 'error' => $e->getMessage()]);
                }

                $formatted = [
                    'id'           => $p['id'] ?? null,
                    'title'        => strtolower($p['title'] ?? 'mr'),
                    'given_name'   => $p['given_name'] ?? $p['first_name'] ?? '',
                    'family_name'  => $p['family_name'] ?? $p['last_name'] ?? '',
                    'gender'       => match (strtolower($p['gender'] ?? 'm')) {
                        'male', 'm'   => 'm',
                        'female', 'f' => 'f',
                        default       => 'm',
                    },
                    'born_on'      => $bornOn,
                    'email'        => $p['email'] ?? $p['contact']['email'] ?? '',
                    'phone_number' => $p['phone_number']
                        ?? (($p['contact']['phone_code'] ?? '') . ($p['contact']['phone'] ?? '')),
                ];

                if (!empty($p['infant_passenger_id'])) {
                    $formatted['infant_passenger_id'] = $p['infant_passenger_id'];
                }

                return $formatted;
            }, $passengers, array_keys($passengers));

            $payload = [
                'data' => [
                    'type'            => 'instant',
                    'selected_offers' => [$offerId],
                    'passengers'      => $formattedPassengers,
                    'payments'        => [[
                        'type'     => 'balance',
                        'currency' => $offerCurrency,
                        'amount'   => $offerAmountStr,
                    ]],
                ],
            ];

            if (!empty($services)) {
                $payload['data']['services'] = array_map(fn($s) => [
                    'id'       => $s['service_id'] ?? $s['id'],
                    'quantity' => $s['quantity'] ?? 1,
                ], $services);
            }

            $orderData = $this->duffel->createDuffelOrder($payload);

            if (!empty($orderData['errors'])) {
                Log::error('Duffel order failed', ['errors' => $orderData['errors']]);

                $intent = PaymentIntent::retrieve($order->payment_intent_id);

                if ($intent->status === 'succeeded') {
                    $intent->refunds->create(['amount' => $intent->amount]);
                } elseif ($intent->status === 'requires_capture') {
                    $intent->cancel();
                }

                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'failed']);

                return $orderData;
            }

            $payment->update([
                'status'           => 'completed',
                'paid_at'          => now(),
                'payment_id'       => $orderData['id'],
                'gateway_response' => $orderData,
            ]);

            $order->update([
                'status'       => 'confirmed',
                'external_id'  => $orderData['id'],
                'booking_date' => $this->resolveBookingDate($orderData),
                'data'         => $orderData,
            ]);

            $order->refresh();

            session(['last_order' => $orderData]);

            return $order;
        });

        try {
            $emailTemplate = $this->authRepo->findEmailTemplate('booking-confirmation');

            if ($emailTemplate) {
                $user = getUser($order->user_id, ['id', 'name', 'email']);
                 $orderData = $order->data;

                $flightInfo      = $this->extractFlightInfo($orderData);
                $slicesHtml      = $this->buildSlicesHtml($flightInfo);
                $passengersHtml  = $this->buildPassengersHtml($flightInfo);

                SendEmail::dispatch(
                    $user->email,
                    str_replace(
                        ['{booking_type}', '{order_number}', '{app_name}'],
                        [ucfirst($order->type), $order->order_number, config('app.name')],
                        $emailTemplate->subject
                    ),
                    $emailTemplate->body,
                    [
                        'name'                  => ucfirst($user->name),
                        'app_name'              => config('app.name'),
                        'order_number'          => $order->order_number,
                        'booking_type'          => ucfirst($order->type),
                        'booking_ref'           => $flightInfo['booking_ref'],
                        'trip_type'             => $flightInfo['trip_type_label'],
                        'booking_date'          => $order->booking_date
                            ? \Carbon\Carbon::parse($order->booking_date)->format('d M Y, h:i A')
                            : now()->format('d M Y, h:i A'),
                        'expires_at'            => $order->expires_at
                            ? \Carbon\Carbon::parse($order->expires_at)->format('d M Y, h:i A')
                            : 'N/A',
                        'external_id'           => $order->external_id,
                        'base_amount'           => number_format($order->base_amount, 2),
                        'seat_amount'           => number_format($order->seat_amount, 2),
                        'addons_amount'         => number_format($order->addons_amount, 2),
                        'platform_fee'          => number_format($order->platform_fee, 2),
                        'tax_amount'            => number_format($order->tax_amount, 2),
                        'discount_amount'       => number_format($order->discount_amount, 2),
                        'total_amount'          => number_format($order->total_amount, 2),
                        'currency'              => strtoupper($order->currency),
                        'booking_url'           => config('app.url') . '/my-booking/' . $order->id,
                        'flight_slices_html'    => $slicesHtml,
                        'flight_passengers_html'=> $passengersHtml,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Booking confirmation email failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        return $order;
    }

    private function resolveBookingDate(array $orderData): ?string
    {
        $departing = $orderData['slices'][0]['segments'][0]['departing_at'] ?? null;
        return $departing ? date('Y-m-d', strtotime($departing)) : null;
    }

    private function extractFlightInfo(array $orderData): array
    {
        $slices   = $orderData['slices'] ?? [];
        $tripType = count($slices) === 1 ? 'one_way' : (count($slices) === 2 ? 'round_trip' : 'multi_city');

        $tripTypeLabel = match ($tripType) {
            'one_way'    => 'One Way',
            'round_trip' => 'Round Trip',
            default      => 'Multi City',
        };

        $sliceDetails = [];
        foreach ($slices as $slice) {
            $segments = $slice['segments'] ?? [];
            $firstSeg = $segments[0] ?? [];
            $lastSeg  = end($segments) ?: [];

            $stops = collect($segments)->sum(fn($s) => count($s['stops'] ?? []));
            $stopsCount = count($segments) - 1 + $stops;

            $sliceDetails[] = [
                'origin_city'          => $firstSeg['origin']['city_name'] ?? $firstSeg['origin']['city']['name'] ?? $firstSeg['origin']['iata_code'] ?? '',
                'origin_airport'       => $firstSeg['origin']['name'] ?? '',
                'origin_iata'          => $firstSeg['origin']['iata_code'] ?? '',
                'origin_terminal'      => $firstSeg['origin_terminal'] ?? '',
                'destination_city'     => $lastSeg['destination']['city_name'] ?? $lastSeg['destination']['city']['name'] ?? $lastSeg['destination']['iata_code'] ?? '',
                'destination_airport'  => $lastSeg['destination']['name'] ?? '',
                'destination_iata'     => $lastSeg['destination']['iata_code'] ?? '',
                'destination_terminal' => $lastSeg['destination_terminal'] ?? '',
                'departing_at'         => $firstSeg['departing_at'] ?? '',
                'arriving_at'          => $lastSeg['arriving_at'] ?? '',
                'duration'             => $slice['duration'] ?? '',
                'airline_name'         => $firstSeg['marketing_carrier']['name'] ?? '',
                'airline_logo'         => $firstSeg['marketing_carrier']['logo_symbol_url'] ?? '',
                'flight_number'        => ($firstSeg['marketing_carrier']['iata_code'] ?? '') . ($firstSeg['marketing_carrier_flight_number'] ?? ''),
                'cabin_class'          => $firstSeg['passengers'][0]['cabin_class_marketing_name'] ?? '',
                'fare_brand'           => $slice['fare_brand_name'] ?? '',
                'stops'                => $stopsCount,
            ];
        }

        $passengerList = [];
        foreach ($orderData['passengers'] ?? [] as $p) {
            $passengerList[] = [
                'name'  => ucfirst($p['title'] ?? '') . ' ' . ucfirst($p['given_name']) . ' ' . ucfirst($p['family_name']),
                'type'  => ucfirst($p['type'] ?? 'Adult'),
                'email' => $p['email'] ?? '',
            ];
        }

        return [
            'trip_type'       => $tripType,
            'trip_type_label' => $tripTypeLabel,
            'slices'          => $sliceDetails,
            'passengers'      => $passengerList,
            'booking_ref'     => $orderData['booking_reference'] ?? '',
        ];
    }

    private function buildSlicesHtml(array $flightInfo): string
    {
        $html = '';

        foreach ($flightInfo['slices'] as $i => $slice) {
            $label = match ($flightInfo['trip_type']) {
                'round_trip' => $i === 0 ? 'Outbound Flight' : 'Return Flight',
                'multi_city' => 'Flight ' . ($i + 1),
                default      => 'Flight Details',
            };

            $dep       = $slice['departing_at'] ? \Carbon\Carbon::parse($slice['departing_at'])->format('d M Y, h:i A') : 'N/A';
            $arr       = $slice['arriving_at']  ? \Carbon\Carbon::parse($slice['arriving_at'])->format('d M Y, h:i A')  : 'N/A';
            $dur       = preg_replace('/PT(\d+)H(\d+)M/', '$1h $2m', $slice['duration']);
            $stopsText = $slice['stops'] === 0 ? 'Non Stop' : $slice['stops'] . ' Stop(s)';
            $origTerm  = $slice['origin_terminal']      ? ' · T' . $slice['origin_terminal']      : '';
            $destTerm  = $slice['destination_terminal'] ? ' · T' . $slice['destination_terminal'] : '';

            $html .= '
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin:0 0 16px 0;">
          <tr>
            <td style="padding:16px 20px;">

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                <tr>
                  <td style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;font-weight:600;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;">' . $label . '</td>
                  <td align="right">
                    <span style="background:#e1f5ee;color:#0f6e56;border-radius:20px;padding:3px 12px;font-family:\'Poppins\',Arial,sans-serif;font-size:11px;font-weight:600;">' . $stopsText . '</span>
                  </td>
                </tr>
              </table>

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                <tr>
                  <td width="35%" style="font-family:\'Poppins\',Arial,sans-serif;">
                    <p style="font-size:26px;font-weight:700;color:#0F3869;margin:0;line-height:1;">' . $slice['origin_iata'] . '</p>
                    <p style="font-size:12px;color:#475569;margin:4px 0 2px;">' . $slice['origin_city'] . '</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">' . $slice['origin_airport'] . $origTerm . '</p>
                  </td>
                  <td align="center" style="font-family:\'Poppins\',Arial,sans-serif;">
                    <p style="font-size:11px;color:#94a3b8;margin:0 0 4px;">' . $dur . '</p>
                    <p style="font-size:18px;color:#cbd5e1;margin:0;">&#9992;</p>
                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">' . $slice['airline_name'] . ' ' . $slice['flight_number'] . '</p>
                  </td>
                  <td width="35%" align="right" style="font-family:\'Poppins\',Arial,sans-serif;">
                    <p style="font-size:26px;font-weight:700;color:#0F3869;margin:0;line-height:1;">' . $slice['destination_iata'] . '</p>
                    <p style="font-size:12px;color:#475569;margin:4px 0 2px;">' . $slice['destination_city'] . '</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">' . $slice['destination_airport'] . $destTerm . '</p>
                  </td>
                </tr>
              </table>

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #e2e8f0;padding-top:12px;margin-top:4px;">
                <tr>
                  <td style="font-family:\'Poppins\',Arial,sans-serif;font-size:12px;color:#475569;">
                    <strong style="color:#0f172a;">Dep:</strong> ' . $dep . '
                  </td>
                  <td align="right" style="font-family:\'Poppins\',Arial,sans-serif;font-size:12px;color:#475569;">
                    <strong style="color:#0f172a;">Arr:</strong> ' . $arr . '
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;color:#94a3b8;padding-top:6px;">
                    ' . $slice['cabin_class'] . ($slice['fare_brand'] ? ' · ' . $slice['fare_brand'] : '') . '
                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>';
        }

        return $html;
    }

    private function buildPassengersHtml(array $flightInfo): string
    {
        $rows = '';
        foreach ($flightInfo['passengers'] as $p) {
            $rows .= '
        <tr>
          <td style="font-family:\'Poppins\',Arial,sans-serif;font-size:13px;color:#0f172a;font-weight:500;padding:7px 0;border-bottom:1px solid #f1f5f9;">' . $p['name'] . '</td>
          <td align="center" style="font-family:\'Poppins\',Arial,sans-serif;font-size:12px;color:#64748b;padding:7px 0;border-bottom:1px solid #f1f5f9;">' . $p['type'] . '</td>
          <td align="right" style="font-family:\'Poppins\',Arial,sans-serif;font-size:12px;color:#94a3b8;padding:7px 0;border-bottom:1px solid #f1f5f9;">' . $p['email'] . '</td>
        </tr>';
        }

        return '
     <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin:0 0 20px 0;">
      <tr>
        <td style="padding:16px 20px;">
          <p style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;font-weight:600;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;margin:0 0 12px 0;">Passengers</p>
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <th align="left" style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;color:#94a3b8;font-weight:500;padding-bottom:8px;">Name</th>
              <th align="center" style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;color:#94a3b8;font-weight:500;padding-bottom:8px;">Type</th>
              <th align="right" style="font-family:\'Poppins\',Arial,sans-serif;font-size:11px;color:#94a3b8;font-weight:500;padding-bottom:8px;">Email</th>
            </tr>
            ' . $rows . '
          </table>
        </td>
      </tr>
     </table>';
    }
}
