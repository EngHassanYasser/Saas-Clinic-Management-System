<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
    public function payments() {
        return $this->hasMany(payment::class);
    }
    public function appointment_status_logs() {
        return $this->hasMany(appointment_status_log::class);
    }
}
