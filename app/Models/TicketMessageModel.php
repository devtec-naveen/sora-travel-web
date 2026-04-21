<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessageModel extends Model
{
    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
        'is_read',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read'     => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicketModel::class, 'ticket_id');
    }

    public function sender()
    {
        return $this->sender_type === 'admin'
            ? $this->belongsTo(User::class, 'sender_id')
            : $this->belongsTo(User::class, 'sender_id');
    }

    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    public function isFromUser(): bool
    {
        return $this->sender_type === 'user';
    }
}
