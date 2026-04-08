<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = "payments";

    protected $fillable = [
        'user_id',
        'payment_id',       
        'payment_method',   
        'amount',
        'currency',
        'status',           
        'gateway_response', 
        'failure_reason',
        'paid_at',
        'amount',	
	    'base_amount'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'amount'           => 'decimal:2',
    ];
}