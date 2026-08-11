<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'channel',
    'status',
    'response',
    'user_id',
    'notification_id'
])]
class Notification_log extends Model
{
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function notification()
    {
        return $this->belongsTo(notification::class);
    }
}
