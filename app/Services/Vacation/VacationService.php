<?php

namespace App\Services\Vacation;

use App\DTOs\Services\Vacation\StoreVacationDTO;
use App\DTOs\Services\Vacation\UpdateVacationDTO;
use App\Exceptions\HasVacationException;
use App\Models\Vacation;
use Illuminate\Support\Facades\DB;

class VacationService
{
    public function __construct(private VacationValidationService $vactionValidationService) {}

    public function add(StoreVacationDTO $dto, int $clinicId): Vacation
    {
        $hasVacation = $this->vactionValidationService->hasVacation($dto->doctorId, $clinicId);
        if ($hasVacation) {
            throw new HasVacationException('thre are alread vacation for this doctor');
        }

        return Vacation::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'start_date' => $dto->StartDate,
            'end_date' => $dto->endDate,
            'reason' => $dto->reason,
            'status' => $dto->status,
        ]);
    }

    public function update(UpdateVacationDTO $dto, int $vacationId, int $clinicId): bool
    {
        return DB::transaction(function () use ($dto, $vacationId, $clinicId) {

            $vacation = Vacation::lockForUpdate()
                ->where('clinic_id', $clinicId)
                ->findOrFail($vacationId);

            if ($this->vactionValidationService->hasVacation(
                $vacation->doctor_id,
                $clinicId,
                $vacationId
            )) {
                throw new HasVacationException(
                    'there are already vacation for this doctor'
                );
            }

            return $vacation->update([
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'reason' => $dto->reason,
                'status' => $dto->status,
            ]);

        });
    }

    public function delete(int $vacationId, int $clinicId): bool
    {
        return Vacation::whereKey($vacationId)
            ->whereRelation('doctor.clinics', 'clinics.id', $clinicId)
            ->firstOrFail()->delete();
    }
}
