<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingModel extends Model
{
    protected $table = 'global_settings';
    protected $fillable = [
        'label',
        'name',
        'value',
        'input_type',
        'group'
    ];
}
