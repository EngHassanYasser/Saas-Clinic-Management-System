<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'name',
    'speciality_id'
])]
class ClinicService extends Model
{
    public $timestamp = false;
    public function servicePrices()
    {
        return $this->hasMany(doctor_service_price::class);
    }
}
