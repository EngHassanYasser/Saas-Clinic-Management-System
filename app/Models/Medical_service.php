<?php

namespace App\Models;

use App\Policies\Medical_servicePolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[Fillable([
    'name',
    'speciality_id'
])]
#[UsePolicy(Medical_servicePolicy::class)]
class Medical_service extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function servicePrices()
    {
        return $this->hasMany(Clinic_doctor_medical_service::class);
    }
    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }
    public function medicalServicePrice()
    {
        return $this->belongsTo(Clinic_doctor_medical_service::class);
    }
}
