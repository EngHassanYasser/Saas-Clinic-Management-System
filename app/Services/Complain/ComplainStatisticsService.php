<?php

namespace App\Services\Complain;

use App\Models\Complain;

class ComplainStatisticsService
{
    public function getStatistics(int $clinicId): Complain
    {
        $statistics = Complain::where('clinic_id', $clinicId)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as under_review,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    ")->first();

        foreach (['total', 'pending', 'under_review', 'resolved', 'rejected'] as $field) {
            $statistics->{$field} = (int) $statistics->{$field};
        }

        return $statistics;
    }
}
