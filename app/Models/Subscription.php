<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'start_at',
    'end_at',
    'status',
    'price',
    'auto_renew',
    'clinic_id',
    'plan_id'
])]
class Subscription extends Model
{
    use HasFactory;
       protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
    public function plan() {
        return $this->belongsTo(plan::class);
    }
}
