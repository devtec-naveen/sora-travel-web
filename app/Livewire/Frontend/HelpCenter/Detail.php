<?php

namespace App\Livewire\Frontend\HelpCenter;

use App\Models\SupportTicketModel;
use App\Services\Common\TicketService;
use Livewire\Component;

class Detail extends Component
{
    public int $ticketId;
    public string $reply = '';

    public function mount(int $ticketId, TicketService $ticketService): void
    {
        $this->ticketId = $ticketId;
        $ticketService->markMessagesRead($ticketId);
    }

    public function getTicketProperty(): SupportTicketModel
    {
        return app(TicketService::class)->getTicketDetail($this->ticketId)
            ?? abort(404);
    }

    public function sendReply(TicketService $ticketService): void
    {
        $this->validate(['reply' => 'required|string|min:2|max:2000']);

        $ticketService->sendReply($this->ticketId, $this->reply);

        $this->reset('reply');
    }

    public function closeTicket(TicketService $ticketService): void
    {
        $ticketService->closeTicket($this->ticketId);

        $this->dispatch('ticketClosed');
        session()->flash('success', 'Ticket closed successfully.');
        $this->redirect(route('front.helpCenter'));
    }

    public function render(TicketService $ticketService)
    {
        $ticketService->markMessagesRead($this->ticketId);

        return view('livewire.frontend.help-center.detail', [
            'ticket' => $this->ticket,
        ]);
    }
}
