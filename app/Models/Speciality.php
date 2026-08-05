<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'icon_name',
])]
class Speciality extends Model
{
    use HasFactory;

    public $timestamps = false;

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
