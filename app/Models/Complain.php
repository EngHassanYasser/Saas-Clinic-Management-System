<?php

namespace App\Models;

use App\Enums\ComplainStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'clinic_id',
    'user_id',
    'doctor_id',
    'patient_name',
    'description',
    'department_name',
    'status',
    'issue_type',
    'severity',
    'visit_date',
    'department',
    'resolution_notes',
    'resolved_by',
    'resolved_at',
])]
class Complain extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ComplainStatus::class,
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
