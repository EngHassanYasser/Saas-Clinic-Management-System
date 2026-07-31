<?php

namespace App\Services;

use App\Enums\RoleType;
use App\Models\Complain;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ComplainService
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
        )->when($user->type === 'clinic', function ($query) use ($user) {
            $query->whereHas('clinic', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        })->when($user->type === 'patient', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['patient:id,name', 'doctor:id,name'])
            ->get();
    }
    public function getStatistics(): Complain 
    {
        $stats = Complain::where('clinic_id', Auth::user()->clinic_id)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as under_review,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    ")->first();
        return $stats;
    }
    public function add(array $data): Complain
    {
        $data['user_id'] = Auth::user()->type === RoleType::PATIENT->value
            ? Auth::id()
            : null;
        $complain = Complain::create([
            'clinic_id' => Auth::user()->clinic_id,
            'user_id' => $data['user_id'],
            'doctor_id' => $data['doctor_id'],
            'department' => $data['department_name'],
            'visit_date' => $data['visit_date'],
            'severity' => $data['severity'],
            'issue_type' => $data['issue_type'],
            'description' => $data['description'],
            'status' => $data['status'],
            'patient_name' => $data['patient_name']
        ]);
        return $complain;
    }
    public function update(array $data, int $complainId): bool
    {
        $complain = Complain::where('id', $complainId)
            ->where('clinic_id', Auth::user()->clinic_id)->firstOrFail();
        $updateData = [
            'doctor_id' => $data['doctor_id'] ?? null,
            'department' => $data['department_name'],
            'visit_date' => $data['visit_date'],
            'severity' => $data['severity'],
            'issue_type' => $data['issue_type'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'patient_name' => $data['patient_name'] ?? null,
            'resolution_notes' => $data['resolution_notes'] ?? null,
        ];
        return $complain->update($updateData);
    }
    public function getById(int $complainId): Complain
    {
        return Complain::where('id', $complainId)
            ->where(function ($query) {
                $query->where('clinic_id', Auth::user()->clinic_id)
                    ->orWhere('user_id', Auth::id());
            })
            ->firstOrFail();
    }
    public function delete(int $complainId): bool
    {
        $complain = $this->getById($complainId);
        return $complain->delete();
    }
}
