<?php

namespace App\Services\Complain;

use App\Enums\RoleType;
use App\Models\Complain;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ComplainQueryService
{
    public function getClinicComplains(User $user): Collection
    {
        return Complain::select(
            'id',
            'clinic_id',
            'user_id',
            'doctor_id',
            'patient_name',
            'issue_type',
            'severity',
            'description',
            'visit_date',
            'status',
            'department',
            'resolution_notes',
            'resolved_at',
            'updated_at',
            'created_at'
        )->when($user->type === RoleType::CLINIC, function ($query) use ($user) {
            $query->whereHas('clinic', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        })->when($user->type === RoleType::PATIENT, function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['patient:id,name', 'doctor:id,name'])
            ->get();
    }
    public function getById(int $complainId): Complain
    {
        return Complain::where('id', $complainId)->firstOrFail();
    }
}
