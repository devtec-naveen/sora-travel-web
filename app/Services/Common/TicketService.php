<?php

namespace App\Services\Common;

use App\Models\SupportTicketModel;
use App\Models\TicketMessageModel;
use App\Repositories\Common\TicketRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function __construct(
        protected TicketRepository $repository,
        protected FileService $fileService
    ) {}

    public function getUserTickets(string $filterStatus = ''): Collection
    {
        return $this->repository->getUserTickets(Auth::id(), $filterStatus);
    }

    public function createTicket(array $data, mixed $attachment = null): SupportTicketModel
    {
        $attachments = $this->handleAttachment($attachment);

        $ticket = $this->repository->createTicket([
            'user_id'       => Auth::id(),
            'ticket_number' => SupportTicketModel::generateTicketNumber(),
            'subject'       => $data['subject'],
            'description'   => $data['description'],
            'order_id'      => $data['order_id'] ?: null,
            'status'        => 'open',
        ]);

        $this->repository->createMessage([
            'ticket_id'   => $ticket->id,
            'sender_type' => 'user',
            'sender_id'   => Auth::id(),
            'message'     => $data['description'],
            'attachments' => $attachments,
        ]);

        return $ticket;
    }

    public function getTicketDetail(int $ticketId): ?SupportTicketModel
    {
        return $this->repository->findUserTicket(Auth::id(), $ticketId);
    }

    public function sendReply(int $ticketId, string $message): TicketMessageModel
    {
        $ticket = $this->repository->findUserTicket(Auth::id(), $ticketId);

        abort_if(!$ticket, 404);
        abort_if(!$ticket->isOpen(), 403, 'Ticket is closed.');

        $msg = $this->repository->createMessage([
            'ticket_id'   => $ticketId,
            'sender_type' => 'user',
            'sender_id'   => Auth::id(),
            'message'     => trim($message),
        ]);

        if ($ticket->status === 'resolved') {
            $this->repository->updateTicketStatus($ticketId, 'open');
        }

        return $msg;
    }

    public function closeTicket(int $ticketId): void
    {
        $ticket = $this->repository->findUserTicket(Auth::id(), $ticketId);

        abort_if(!$ticket, 404);
        abort_if($ticket->user_id !== Auth::id(), 403);
        $this->repository->updateTicketStatus($ticketId, 'closed', [
            'closed_at' => now(),
        ]);
    }

    public function markMessagesRead(int $ticketId): void
    {
        $this->repository->markAdminMessagesRead($ticketId);
    }

    private function handleAttachment(mixed $attachment): ?array
    {
        if (!$attachment) {
            return null;
        }

        $fileName = $this->fileService->upload($attachment, 'ticket-attachments', 'ticket');

        if (!$fileName) {
            return null;
        }

        return [[
            'folder' => 'ticket-attachments',
            'file'   => $fileName,
            'name'   => $attachment->getClientOriginalName(),
        ]];
    }
}
