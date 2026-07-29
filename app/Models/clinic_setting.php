<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'appointment_duration',
    'cancellation_hours_limit',
    'auth_confirm',
    'working_days',
    'timezone',
    'deposit_percentage',
    'cancellation_fee_percentage',
    'clinic_id'
])]
class Clinic_setting extends Model
{
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
}
