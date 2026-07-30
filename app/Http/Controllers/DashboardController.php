<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}
    public function index()
    {
        return redirect()->route(match (auth()->user()->type) {
            RoleType::PATIENT->value => 'appointments.index',
            RoleType::CLINIC->value => 'clinic.stats',
            RoleType::SUPER_ADMIN->value => 'dashboard.getstats',
            default => abort(403),
        });
    }

    public function getstats()
    {
        $stats = $this->dashboardService->getClinicDashboardStats();
        $lastActivities = $this->dashboardService->getLastActivities();
        return view('stats.index', compact('stats', 'lastActivities'));
    }
}
