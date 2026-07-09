<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)
                ->setTimezone(config('app.client_timezone'))
                ->format('Y-m-d h:i A'),
        );
    }
    protected function startTime(): Attribute
    {
      return Attribute::make(
            get: fn($value) => Carbon::parse($value)
                ->setTimezone(config('app.client_timezone'))
                ->format('Y-m-d h:i A'),
        );
    }
    public function service()
    {
        return  $this->belongsTo(ClinicService::class, 'clinic_service_id');
    }
    public function patient()
    {
        return $this->belongsTo(user::class);
    }
    public function doctor()
    {
        return $this->belongsTo(doctor::class);
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
