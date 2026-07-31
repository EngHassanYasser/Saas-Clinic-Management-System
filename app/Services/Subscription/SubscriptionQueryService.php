<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionQueryService
{
    public function getAll(): Collection
    {
        return Subscription::select([
            'id',
            'start_at',
            'end_at',
            'status',
            'price',
            'auto_renew',
            'clinic_id',
            'plan_id'
        ])->with(['plan:id,name,monthly_price', 'clinic:id,name'])
            ->get()->makeHidden(['clinic_id', 'plan_id']);
    }
    public function getById() {}
    public function getClinicSubscriptions() {}
    public function getExpiringSubscriptions() {}
}
