<?php

namespace App\Models;

use App\Enums\EnAppointmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    'medicalService_id',
])]
class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => EnAppointmentStatus::class,
            'visit_date' => 'date',
        ];
    }

    public function medicalServicePrice()
    {
        return $this->hasOne(Clinic_doctor_medicalService::class);
    }

    public function service()
    {
        return $this->belongsTo(MedicalService::class);
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
        return $this->hasMany(appointmentStatusLog::class);
    }

    public function complaintts()
    {
        return $this->hasMany(complaint::class);
    }
}
