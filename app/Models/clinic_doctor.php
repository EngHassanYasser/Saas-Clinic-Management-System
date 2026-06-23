<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'is_active',
    'doctor_id',
    'clinic_id'
])]
class clinic_doctor extends Model {
    public  $timestamps=false;
}
