<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'start_date',
    'end_date',
    'reason',
    'doctor_id',
    'status',
])]
class Vication extends Model
{
    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
