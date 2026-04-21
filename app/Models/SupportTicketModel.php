<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketModel extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'ticket_number',
        'subject',
        'description',
        'order_id',
        'category',
        'priority',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . strtoupper(substr(uniqid(), -6));
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessageModel::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(TicketMessageModel::class, 'ticket_id')->latestOfMany();
    }

    public function unreadCount()
    {
        return $this->messages()->where('sender_type', 'admin')->where('is_read', false)->count();
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'open'        => 'Open',
            'in_progress' => 'In Progress',
            'resolved'    => 'Resolved',
            'closed'      => 'Closed',
            default       => 'Unknown',
        };
    }

    public function statusTag(): string
    {
        return match ($this->status) {
            'open'        => 'tag-blue',
            'in_progress' => 'tag-orange',
            'resolved'    => 'tag-green',
            'closed'      => 'tag-gray',
            default       => 'tag-gray',
        };
    }
}
