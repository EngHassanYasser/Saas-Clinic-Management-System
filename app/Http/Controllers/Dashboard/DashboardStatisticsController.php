<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\services\ActivityLog\ActivityLogQueryService;
use App\Services\Clinic\ClinicStatisticsService;

class DashboardStatisticsController extends Controller
{
    public function __construct(
        private ClinicStatisticsService $ClinicStatisticsService,
        private ActivityLogQueryService $activityLogQueryService
    ) {}

    public function getstats()
    {
        // $this->authorize('viewAny',Dashboard::class);

           $stats= $this->ClinicStatisticsService->getClinicDashboardStats();
            $lastActivities= $this->activityLogQueryService->getLastActivities();

        return view('stats.index', compact('stats', 'lastActivities'));
    }
}
