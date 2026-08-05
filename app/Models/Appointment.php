<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
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
    'patient_id',
    'clinic_id',
    'doctor_id',
    'visit_date',
])]
class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected function casts(): array
    {
        return [
            'status' => AppointmentStatus::class,
        ];
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
    public function complains()
    {
        return $this->hasMany(complain::class);
    }
}
