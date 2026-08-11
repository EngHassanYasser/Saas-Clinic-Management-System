<?php

namespace App\Services\Complaint;

use App\Enums\EnRoleType;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ComplaintQueryService
{
    public function getClinicComplaints(User $user): Collection
    {
        return Complaint::select(
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
        )->when($user->type ===EnRoleType::CLINIC, function ($query) use ($user) {
            $query->whereHas('clinic', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        })->when($user->type ===EnRoleType::PATIENT, function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['patient:id,name', 'doctor:id,name'])
            ->get();
    }
    public function getById(int $complaintId): Complaint
    {
        return Complaint::where('id', $complaintId)->firstOrFail();
    }
}
