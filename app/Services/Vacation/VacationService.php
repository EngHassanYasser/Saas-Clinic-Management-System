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

    public function update(UpdateVacationDTO $dto, Vacation $vacation): bool
    {
        return DB::transaction(function () use ($dto, $vacation) {

            $vacation = Vacation::lockForUpdate()
                ->where('clinic_id', $vacation->clinic_id)
                ->findOrFail($vacation->clinic_id);

            if ($this->vactionValidationService->hasVacation(
                $vacation->doctor_id,
                $vacation->clinic_id,
                $vacation->id
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

    public function delete(Vacation $vacation): bool
    {
        return $vacation->delete();
    }
}
