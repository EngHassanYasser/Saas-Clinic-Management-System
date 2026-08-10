<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'start_date',
    'end_date',
    'reason',
    'doctor_id',
    'status',
    'clinic_id',
])]
class Vacation extends Model
{
    use HasFactory;
    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
