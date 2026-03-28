<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'user_id',
        'payment_id',
        'order_number',     
        'type',           
        'external_id',     
        'amount',
        'tax_amount',
        'discount_amount',
        'currency',
        'status',           
        'booking_date',     
        'expires_at',      
        'data',             
        'notes',
    ];

    protected $casts = [
        'data'          => 'array',
        'amount'        => 'decimal:2',
        'tax_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'booking_date'  => 'date',
        'expires_at'    => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentModel::class, 'payment_id');
    }
}