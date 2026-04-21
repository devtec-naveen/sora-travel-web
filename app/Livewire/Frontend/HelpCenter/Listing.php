<?php

namespace App\Livewire\Frontend\HelpCenter;

use App\Services\Common\TicketService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Listing extends Component
{
    public string $filterStatus = '';

    protected $listeners = [
        'ticketCreated' => '$refresh',
        'ticketClosed'  => '$refresh',
    ];

    public function getTicketsProperty()
    {
        if (!Auth::check()) {
            return [];
        }

        return app(TicketService::class)->getUserTickets($this->filterStatus);
    }

    public function render()
    {
        return view('livewire.frontend.help-center.listing', [
            'tickets' => $this->tickets, 
        ]);
    }
}