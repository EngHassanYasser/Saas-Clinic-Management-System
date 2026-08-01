<?php

namespace App\Services\Plan;

use App\Models\Plan;

class PlanService
{
    public function add(array $data): Plan
    {
        return Plan::create([
            'name' => $data['name'],
            'monthly_price' => $data['monthly_price'],
            'max_doctors' => $data['max_doctors'],
            'monthly_appointments_limit' => $data['monthly_appointments_limit']
        ]);
    }
    public function update(array $data, int $id): bool
    {
        $plan = Plan::whereKey($id)->lockForUpdate()->firstOrFail();
        return $plan->update([
            'name' => $data['name'],
            'monthly_price' => $data['monthly_price'],
            'max_doctors' => $data['max_doctors'],
            'monthly_appointments_limit' => $data['monthly_appointments_limit'],
            'status' => $data['status'],
        ]);
    }
}
