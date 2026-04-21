<?php

namespace App\Livewire\Frontend\HelpCenter;

use App\Services\Common\TicketService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use WithFileUploads;

    public string $subject     = '';
    public string $description = '';
    public string $order_id    = '';
    public $attachment         = null;

    protected array $rules = [
        'subject'     => 'required|string|min:5|max:255',
        'description' => 'required|string|min:10',
        'order_id'    => 'nullable|string|max:100',
        'attachment'  => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
    ];

    public function submit(TicketService $ticketService): void
    {
        $this->validate();

        $ticketService->createTicket(
            $this->only(['subject', 'description', 'order_id']),
            $this->attachment
        );

        $this->reset(['subject', 'description', 'order_id', 'attachment']);
        $this->resetValidation();

        $this->dispatch('close-modal', id: 'raise_ticket_modal');
        $this->dispatch('ticketCreated');
        session()->flash('success', 'Ticket raised successfully! Our team will respond shortly.');
    }

    public function render()
    {
        return view('livewire.frontend.help-center.create-ticket');
    }
}
