<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Services\DashboardService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}
    public function index()
    {
        if (Auth::user()->type == RoleType::SUPER_ADMIN->value) {
            $stats = $this->dashboardService->getClinicDashboardStats();
            $lastActivities = $this->dashboardService->getLastActivities();
        } else {
            $stats = [];
            $lastActivities = new LengthAwarePaginator(
                items: [],
                total: 0,
                perPage: 15,
                currentPage: 1,
            );
        }
        return view('dashboards.index', compact('stats', 'lastActivities'));
    }
}
