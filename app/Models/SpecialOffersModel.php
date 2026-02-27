<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialOffersModel extends Model
{
    protected $table = 'special_offers';
    protected $fillable = [
        'title',
        'image',
        'start_date_time',
        'end_date_time',
        'status',
    ];
}
