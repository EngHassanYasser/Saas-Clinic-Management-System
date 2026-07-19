<?php

namespace App\services;

use App\Models\clinic;

class ClinicService
{
    public function getAll()
    {
        return clinic::select([
            'id',
            'name',
            'email',
            'created_at',
            'city_id',
        ])->with(['city:id,name', 'subscriptions:id,plan_id,clinic_id,status', 'subscriptions.plan:id,monthly_price'])
            ->paginate(10);
    }
}
