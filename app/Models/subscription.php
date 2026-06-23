<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'start_at',
    'end_at',
    'status',
    'price',
    'auto_renew',
    'created_at',
    'updated_at',
    'clinic_id',
    'plan_id'
])]
class subscription extends Model
{
    //
}
