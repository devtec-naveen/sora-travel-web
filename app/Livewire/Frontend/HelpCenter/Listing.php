<?php

namespace App\Livewire\Frontend\HelpCenter;

use App\Services\Common\TicketService;
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
        return app(TicketService::class)->getUserTickets($this->filterStatus);
    }

    public function render()
    {
        return view('livewire.frontend.help-center.listing', [
            'tickets' => $this->tickets,
        ]);
    }
}
