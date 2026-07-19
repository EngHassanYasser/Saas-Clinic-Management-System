<?php

namespace App\Services;

use App\Models\Clinic;

class ClinicService
{
    public function getAll()
    {
        return Clinic::select([
            'id',
            'name',
            'email',
            'created_at',
            'city_id',
        ])
        ->with([
            'city:id,name',
            'latestSubscription',
            'latestSubscription.plan:id,name,monthly_price',
        ])
        ->paginate(10)
        ->through(function ($clinic) {
            return [
                'id' => $clinic->id,
                'name' => $clinic->name,
                'email' => $clinic->email,
                'status' => $clinic->latestSubscription?->status,
                'city'=>$clinic->city->name,
                'plan' => $clinic->latestSubscription?->plan?->name,
                'joined_at' => $clinic->created_at->toDateString(),
            ];
        });
    }
}