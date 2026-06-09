<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
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
