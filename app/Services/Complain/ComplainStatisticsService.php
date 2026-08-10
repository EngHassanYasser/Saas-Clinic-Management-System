<?php

namespace App\Services\Complaint;

use App\Models\Complaint;
use Illuminate\Support\Facades\Cache;

class ComplaintStatisticsService
{
    public function getStatistics(int $clinicId): array
    {
        return Cache::remember(
            "complaintts.statistics.clinic.{$clinicId}",
            now()->addMinutes(5),
            function () use ($clinicId) {
                $statistics = Complaint::where('clinic_id', $clinicId)
                    ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as under_review,
                    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                ")
                    ->first();

                return [
                    'total' => (int) $statistics->total,
                    'pending' => (int) $statistics->pending,
                    'under_review' => (int) $statistics->under_review,
                    'resolved' => (int) $statistics->resolved,
                    'rejected' => (int) $statistics->rejected,
                ];
            }
        );
    }
}
