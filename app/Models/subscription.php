<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'start_at',
    'end_at',
    'status',
    'price',
    'auto_renew',
    'clinic_id',
    'plan_id'
])]
class subscription extends Model
{
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
    public function plan() {
        return $this->belongsTo(plan::class);
    }
}
