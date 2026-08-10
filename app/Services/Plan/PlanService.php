<?php

namespace App\Services\Plan;

use App\DTOs\Services\Plan\StorePlanDTO;
use App\DTOs\Services\Plan\UpdatePlanDTO;
use App\Models\Plan;

class PlanService
{
    public function add(StorePlanDTO $dto): Plan
    {
        return Plan::create([
            'name' => $dto->name,
            'monthly_price' => $dto->monthlyPrice,
            'max_doctors' => $dto->maxDoctors,
            'monthly_appointments_limit' => $dto->monthlyAppointmentsLimit
        ]);
    }
    public function update(UpdatePlanDTO $dto, int $id): bool
    {
        $plan = Plan::whereKey($id)->lockForUpdate()->firstOrFail();
        return $plan->update([
            'name' => $dto->name,
            'monthly_price' => $dto->monthlyPrice,
            'max_doctors' => $dto->maxDoctors,
            'monthly_appointments_limit' => $dto->monthlyAppointmentsLimit,
            'status' => $dto->status,
        ]);
    }
}
