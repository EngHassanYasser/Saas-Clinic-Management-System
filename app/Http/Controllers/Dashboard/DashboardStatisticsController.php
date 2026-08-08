<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\services\ActivityLog\ActivityLogQueryService;
use App\Services\Clinic\ClinicStatisticsService;
use Illuminate\Support\Facades\Concurrency;

class DashboardStatisticsController extends Controller
{
    public function __construct(
        private ClinicStatisticsService $ClinicStatisticsService,
        private ActivityLogQueryService $activityLogQueryService
    ) {}

    public function getstats()
    {
        [$stats,$lastActivities] = Concurrency::run([
            fn () => $this->ClinicStatisticsService->getClinicDashboardStats(),
            fn () => $this->activityLogQueryService->getLastActivities(),
        ]);

        return view('stats.index', compact('stats', 'lastActivities'));
    }
}
