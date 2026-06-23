<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'start_time',
    'end_time',
    'slot_duration',
    'start_break',
    'end_break',
    'is_available',
    'created_at',
    'updated_at',
    'notes',
    'clinic_id',
    'doctor_id'
])]
class schedule extends Model
{
    public function doctor()
    {
        return $this->belongsTo(user::class);
    }
    public function days()
    {
        return $this->hasMany(Day::class);
    }
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
}
