<?php

namespace App\Models;

use App\Enums\PlanStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'monthly_price',
    'max_doctors',
    'monthly_appointments_limit',
    'features',
    'price',
    'status',
])]
class Plan extends Model
{
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'status' => PlanStatus::class,
        ];
    }

    use HasFactory;

    public $timestamps = false;

    public function subscriptions()
    {
        return $this->hasMany(subscription::class);
    }
}
