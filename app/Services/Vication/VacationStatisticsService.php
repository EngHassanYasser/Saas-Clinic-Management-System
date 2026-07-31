<?php

namespace App\Services;

use App\Models\Vication;

class VacationStatisticsService
{
    public function getStatistics(int $clinicId): array
    {
        $stats = Vication::whereRelation('doctor.clinics', 'clinics.id', $clinicId)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'upcoming' THEN 1 ELSE 0 END) as upcoming,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 'ended' THEN 1 ELSE 0 END) as ended
    ")->first();
        return $stats;
    }
}
