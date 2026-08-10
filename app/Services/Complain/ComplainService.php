<?php

namespace App\Services\Complaint;

use App\DTOs\Services\Complaint\StoreComplaintDTO;
use App\DTOs\Services\Complaint\UpdateComplaintDTO;
use App\Enums\EnRoleType;
use App\Models\Complaint;
use App\Models\User;

class ComplaintService
{
    public function __construct(private ComplaintQueryService $complaintQueryService) {}

    public function add(StoreComplaintDTO $dto, User $user, int $clinicId): Complaint
    {
        $complaint = Complaint::create([
            'clinic_id' => $clinicId,
            'user_id' => $user->type ===EnRoleType::PATIENT ? $user->id : null,
            'doctor_id' => $dto->doctorId,
            'department' => $dto->departmentName,
            'visit_date' => $dto->visiteDate,
            'severity' => $dto->severity,
            'issue_type' => $dto->issueType,
            'description' => $dto->description,
            'status' => $dto->status,
            'patient_name' => $dto->patientName,
        ]);

        return $complaint;
    }

    public function update(UpdateComplaintDTO $dto, int $complaintId, int $clinicId): bool
    {
        $complaint = Complaint::where('id', $complaintId)
            ->where('clinic_id', $clinicId)->firstOrFail();

        return $complaint->update([
            'doctor_id' => $dto->doctorId,
            'department' => $dto->departmentName,
            'visit_date' => $dto->visiteDate,
            'severity' => $dto->severity,
            'issue_type' => $dto->issueType,
            'status' => $dto->status,
            'patient_name' => $dto->patientName ?? null,
            'resolution_notes' => $dto->resolutionNotes ?? null,
            'description' => $dto->description ?? null,
        ]);
    }

    public function delete(int $complaintId): bool
    {
        $complaint = $this->complaintQueryService->getById($complaintId);

        return $complaint->delete();
    }
}
