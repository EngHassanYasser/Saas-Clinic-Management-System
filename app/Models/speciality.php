<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class speciality extends Model
{
    public $timestapms = false;
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function clinics()
    {
        return $this->belongsToMany(
            Clinic::class,
            'clinic_specialities'
        );
    }
}
