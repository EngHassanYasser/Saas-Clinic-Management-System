<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardStatisticsService;
use Illuminate\Support\Facades\Concurrency;

class DashboardStatisticsController extends Controller
{
    public function __construct(
        private DashboardStatisticsService $dashboardStatisticsService
    ) {}

    public function getstats()
    {
        [$stats,$lastActivities] = Concurrency::run([
            fn () => $this->dashboardStatisticsService->getClinicDashboardStats(),
            fn () => $this->dashboardStatisticsService->getLastActivities(),
        ]);

        return view('stats.index', compact('stats', 'lastActivities'));
    }
}
