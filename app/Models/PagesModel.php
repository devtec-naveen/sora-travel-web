<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagesModel extends Model
{
    protected $table = "pages";
    
    protected $fillable = [
        'meta_title',
        'meta_keywords',
        'page_title',
        'slug',
        'content',
        'status',
    ];
}
