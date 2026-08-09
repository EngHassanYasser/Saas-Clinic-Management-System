<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'clinic_id',
    'doctor_id',
    'doctorService_id',
    'description',
    'price',
])]
class Doctor_service_price extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'price'=>'decimal:2',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function doctorService()
    {
        return $this->belongsTo(DoctorService::class, 'doctorService_id');
    }
}
