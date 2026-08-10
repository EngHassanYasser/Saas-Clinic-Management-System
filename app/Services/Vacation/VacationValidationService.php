<?php

namespace App\Services\Vacation;

use App\Enums\EnVacationStatus;
use App\Models\Vacation;

class VacationValidationService
{
    public function hasVacation(int $doctorId, int $clinicId, int $ignoreId = 0): bool
    {
        return Vacation::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->whereIn('status', [
                EnVacationStatus::ACTIVE->value,
                EnVacationStatus::UPCOMING->value,
            ])
            ->when($ignoreId > 0, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }

    public function hasVacationOverlap() {}

    public function canCreateVacation() {}

    public function canUpdateVacation() {}

    public function validateVacationDates() {}
}
