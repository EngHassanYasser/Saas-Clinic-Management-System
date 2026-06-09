<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notification_log extends Model
{
    public function user() {
        return $this->belongsTo(user::class);
    }
}
