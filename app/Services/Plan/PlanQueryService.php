<?php

namespace App\Services\Plan;

use App\Enums\EnPlanStatus;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PlanQueryService
{
    public function getAvailablePlans(): Collection
    {
        return Plan::select([
            'id',
            'name',
            'monthly_price',
            'max_doctors',
            'monthly_appointments_limit',
            'status',
        ])->whereStatus(EnPlanStatus::ACTIVE->value)->get();
    }

    public function getAll(): array
    {
        return Cache::remember(
            'plans.all',
            now()->addMinutes(5),
            fn () => Plan::select([
                'id',
                'name',
                'monthly_price',
                'max_doctors',
                'monthly_appointments_limit',
                'status',
            ])->get()->toArray()
        );
    }
}
