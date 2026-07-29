<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type',
    'title',
    'body',
    'sent_at',
    'read_at',
    'user_id'
])]
class Notification extends Model
{
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function notification_logs()
    {
        return $this->hasMany(notification_log::class);
    }
}
