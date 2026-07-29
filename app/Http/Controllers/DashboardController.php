<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}
    public function index()
    {
        if (Auth::user()->type == RoleType::SUPER_ADMIN) {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
