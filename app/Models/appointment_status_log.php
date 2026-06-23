<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'old_status',
    'new_status',
    'reason',
    'appointment_id',
    'created_at',
    'updated_at'
])]
class appointment_status_log extends Model
{
    public function appointment()
    {
        return $this->belongsTo(appointment::class);
    }
}
