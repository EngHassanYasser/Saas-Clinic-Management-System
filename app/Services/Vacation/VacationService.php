<?php

namespace App\Services\Vacation;

use App\Exceptions\HasVicationException;
use App\Models\Vication;
use Illuminate\Support\Facades\DB;

class VacationService
{
    public function __construct(private VacationValidationService $vactionValidationService) {}
    public function add(array $data, int $clinicId): Vication
    {
        $hasVication = $this->vactionValidationService->hasVacation($data['doctor_id'], $clinicId);
        if ($hasVication) {
            throw new HasVicationException('thre are alread vication for this doctor');
        }
        return Vication::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $data['doctor_id'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'reason'     => $data['reason'],
            'status'     => $data['status'],
        ]);
    }

    public function update(array $data, int $vicationId, int $clinicId): bool
    {
        return DB::transaction(function () use ($data, $vicationId, $clinicId) {
            $vacation = Vication::lockForUpdate()
                ->whereRelation('doctor.clinics', 'clinics.id', $clinicId)
                ->findOrFail($vicationId);

            if ($this->vactionValidationService->hasVacation($data['doctor_id'], $vicationId)) {
                throw new HasVicationException('thre are alread vication for this doctor');
            }

            return $vacation->update([
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'reason'     => $data['reason'],
                'status'     => $data['status'],
            ]) > 0;
        });
    }

    public function delete(int $vicationId, int $clinicId): bool
    {
        return Vication::whereKey($vicationId)
            ->whereRelation('doctor.clinics', 'clinics.id', $clinicId)
            ->firstOrFail()->delete();
    }
}
