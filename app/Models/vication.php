<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'start_date',
    'end_date',
    'reason',
    'created_at',
    'updated_at',
    'doctor_id',
    'status',
])]
class vication extends Model
{
    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
