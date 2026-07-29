<?php

namespace App\Services;

use App\Enums\PlanStatus;
use App\Models\plan;
use Illuminate\Database\Eloquent\Collection;

class PlanService
{
    public function getAvailablePlans(): Collection {
        return plan::select([
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
        return plan::select([
            'id',
            'name',
            'monthly_price',
            'max_doctors',
            'monthly_appointments_limit',
            'status'
        ])->get();
    }
    public function add(array $data): plan
    {
        return plan::create([
            'name' => $data['name'],
            'monthly_price' => $data['monthly_price'],
            'max_doctors' => $data['max_doctors'],
            'monthly_appointments_limit' => $data['monthly_appointments_limit']
        ]);
    }
    public function update(array $data, $id): bool
    {
        $plan = plan::whereKey($id)->lockForUpdate()->firstOrFail();
        return $plan->update([
            'name' => $data['name'],
            'monthly_price' => $data['monthly_price'],
            'max_doctors' => $data['max_doctors'],
            'monthly_appointments_limit' => $data['monthly_appointments_limit'],
            'status'=>$data['status'],
        ]);
    }
}