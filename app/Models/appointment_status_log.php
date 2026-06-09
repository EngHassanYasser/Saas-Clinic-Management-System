<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class appointment_status_log extends Model
{
    public function appointment() {
        return $this->belongsTo(appointment::class);
    }
}
