<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_status',
    'new_status',
    'reason',
    'appointment_id',
])]
class AppointmentStatusLog extends Model
{
    public function appointment()
    {
        return $this->belongsTo(appointment::class);
    }
}
