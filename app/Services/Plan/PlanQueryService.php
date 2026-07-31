<?php

namespace App\Services;

use App\Enums\PlanStatus;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

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
            'status'
        ])->whereStatus(PlanStatus::ACTIVE->value)->get();
    }
    public function getAll(): Collection
    {
        return Plan::select([
            'id',
            'name',
            'monthly_price',
            'max_doctors',
            'monthly_appointments_limit',
            'status'
        ])->get();
    }
}
