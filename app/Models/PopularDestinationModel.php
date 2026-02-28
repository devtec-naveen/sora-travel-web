<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopularDestinationModel extends Model
{
    protected $table = "popular_destinations";
    protected $fillable = [
        'title',
        'image',
        'slug',
        'status',
    ];
}
