<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'clinic_id',
    'doctor_id',
    'medicalService_id',
    'description',
    'price',
])]
class Clinic_doctor_medical_service extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'price'=>'decimal:2',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medical_service()
    {
        return $this->belongsTo(Medical_service::class);
    }
}
