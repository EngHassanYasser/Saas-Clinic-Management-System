<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'start_time',
    'end_time',
    'status',
    'appointment_type',
    'booking_source',
    'notes',
    'cancellation_reason',
    'deposit_amount',
    'cancellation_time',
    'reminder_sent_at',
    'created_at',
    'updated_at',
    'patient_id',
    'clinic_id',
    'doctor_id'
])]
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
    public function payments()
    {
        return $this->hasMany(payment::class);
    }
    public function appointment_status_logs()
    {
        return $this->hasMany(appointment_status_log::class);
    }
}
