<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NotificationSettingModel extends Model
{
    protected $table = 'notification_settings';

    protected $fillable = [
        'user_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * @var \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
