<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'channel',
    'status',
    'response',
    'created_at',
    'updated_at',
    'user_id',
    'notification_id'
])]
class notification_log extends Model
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
