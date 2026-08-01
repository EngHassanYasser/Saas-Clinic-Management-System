<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatisticsService;

class DashboardStatisticsController extends Controller
{
    public function __construct(
        private DashboardStatisticsService $dashboardStatisticsService
    ) {}
    public function getstats()
    {
        $stats = $this->dashboardStatisticsService->getClinicDashboardStats();
        $lastActivities = $this->dashboardStatisticsService->getLastActivities();
        return view('stats.index', compact('stats', 'lastActivities'));
    }
}
