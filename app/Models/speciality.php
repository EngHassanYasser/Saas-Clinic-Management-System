<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class speciality extends Model
{
    public $timestapms = false;
    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
    public function clinics()
    {
        return $this->belongsToMany(
            Clinic::class,
            'clinic_specialities'
        );
    }
}
