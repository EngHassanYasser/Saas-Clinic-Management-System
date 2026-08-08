<?php

namespace App\Services\Vacation;

use App\Models\Vication;
use Illuminate\Support\Facades\Cache;

class VacationStatisticsService
{
    public function getStatistics(int $clinicId): object
    {
        return Cache::remember(
            "vications.statistics.clinic.{$clinicId}",
            now()->addMinutes(5),
            function () use ($clinicId) {
                $statistics = Vication::whereRelation(
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

                foreach ([
                    'total',
                    'upcoming',
                    'active',
                    'ended',
                ] as $field) {
                    $statistics->{$field} = (int) $statistics->{$field};
                }

                return $statistics;
            }
        );
    }
}
