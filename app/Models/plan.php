<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'name',
    'monthly_price',
    'max_doctors',
    'monthly_appointments_limit',
    'features'
])]
class plan extends Model
{
    public $timestamps = false;
}
