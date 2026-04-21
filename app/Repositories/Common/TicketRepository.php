<?php

namespace App\Repositories\Common;

use App\Models\SupportTicketModel;
use App\Models\TicketMessageModel;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    public function getUserTickets(int $userId, string $status = ''): Collection
    {
        return SupportTicketModel::where('user_id', $userId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->with('latestMessage')
            ->latest()
            ->get();
    }

    public function findUserTicket(int $userId, int $ticketId): ?SupportTicketModel
    {
        return SupportTicketModel::where('id', $ticketId)
            ->where('user_id', $userId)
            ->with('messages')
            ->first();
    }

    public function createTicket(array $data): SupportTicketModel
    {
        return SupportTicketModel::create($data);
    }

    public function createMessage(array $data): TicketMessageModel
    {
        return TicketMessageModel::create($data);
    }

    public function updateTicketStatus(int $ticketId, string $status, array $extra = []): bool
    {
        return (bool) SupportTicketModel::where('id', $ticketId)
            ->update(array_merge(['status' => $status], $extra));
    }

    public function markAdminMessagesRead(int $ticketId): void
    {
        TicketMessageModel::where('ticket_id', $ticketId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
