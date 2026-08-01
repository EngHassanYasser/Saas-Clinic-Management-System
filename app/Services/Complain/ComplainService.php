<?php

namespace App\Services\Complain;

use App\Enums\RoleType;
use App\Models\Complain;
use App\Models\User;

class ComplainService
{
    public function __construct(private ComplainQueryService $complainQueryService) {}
    public function add(array $data,User $user,int $clinicId): Complain
    {
        $data['user_id'] = $user->type === RoleType::PATIENT->value
            ? $user->id
            : null;
        $complain = Complain::create([
            'clinic_id' => $clinicId,
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
    public function update(array $data, int $complainId,int $clinicId): bool
    {
        $complain = Complain::where('id', $complainId)
            ->where('clinic_id',$clinicId)->firstOrFail();
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
    public function delete(int $complainId): bool
    {
        $complain = $this->complainQueryService->getById($complainId);
        return $complain->delete();
    }
}
