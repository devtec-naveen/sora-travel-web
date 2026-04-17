<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddressModel extends Model
{
    protected $table = "user_addresses";

    protected $fillable = [
        'user_id',
        'street_address',
        'city',
        'postal_code',
        'county',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}