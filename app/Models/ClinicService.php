<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'name',
    'speciality_id'
])]
class ClinicService extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function servicePrices()
    {
        return $this->hasMany(doctor_service_price::class);
    }
    public function appointments()
    {
        return $this->hasMany(appointment::class, 'clinic_service_id');
    }
    public function doctorServicePrice()
    {
        return $this->belongsTo(doctor_service_price::class);
    }
}
