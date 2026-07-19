<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'monthly_price',
    'max_doctors',
    'monthly_appointments_limit',
    'features',
    'price',
])]
class plan extends Model
{
    public $timestamps = false;
    public function subscriptions()
    {
        return $this->hasMany(subscription::class);
    }
}
