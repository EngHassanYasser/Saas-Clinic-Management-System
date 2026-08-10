<?php

namespace App\Services\Vacation;

use App\Models\Vacation;
use Illuminate\Support\Facades\Cache;

class VacationStatisticsService
{
    public function getStatistics(int $clinicId): array
    {
        return Cache::remember(
            "vacations.statistics.clinic.{$clinicId}",
            now()->addMinutes(5),
            function () use ($clinicId) {
                $statistics = Vacation::whereRelation(
                    'doctor.clinics',
                    'clinics.id',
                    $clinicId
                )
                    ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'upcoming' THEN 1 ELSE 0 END) as upcoming,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'ended' THEN 1 ELSE 0 END) as ended
                ")
                    ->first();

                return [
                    'total' => (int) $statistics->total,
                    'upcoming' => (int) $statistics->upcoming,
                    'active' => (int) $statistics->active,
                    'ended' => (int) $statistics->ended,
                ];
            }
        );
    }
}
