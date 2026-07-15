<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'clinic_id',
    'patient_name',
    'description',
    'status',
    'issue_type',
    'severity',
    'visit_date',
    'department',
    'resolution_notes',
    'resolved_by',
    'resolved_at',
])]
class complain extends Model
{

    public function patient()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function doctor() {
        return $this->belongsTo(doctor::class);
    }
}
