<?php

namespace App\Livewire\Frontend\MyAccount;

use Livewire\Component;
use App\Services\Common\MyAccountService;
use App\Traits\Toast;

class NotificationPreferences extends Component
{
    use Toast;

    public bool $booking_updates = false;
    public bool $promotions      = false;
    public bool $payment_alerts  = false;

    public function mount(MyAccountService $service): void
    {
        $settings = $service->getNotificationSettings();

        $this->booking_updates = $settings['booking_updates'];
        $this->promotions      = $settings['promotions'];
        $this->payment_alerts  = $settings['payment_alerts'];
    }

    public function updated(): void
    {
        app(MyAccountService::class)->updateNotificationSettings([
            'booking_updates' => $this->booking_updates,
            'promotions'      => $this->promotions,
            'payment_alerts'  => $this->payment_alerts,
        ]);

        $this->Toast('success', 'Settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.frontend.my-account.notification-preferences');
    }
}
