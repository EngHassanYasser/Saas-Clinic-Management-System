<?php

namespace App\Services\Vacation;

use App\Models\Vication;

class VacationValidationService
{
    public function hasVacation(int $doctorId, int $clinicId, int $ignoreId = 0): bool
    {
        return Vication::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->whereIn('status', [
                'active',
                'upcoming',
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
