<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Enums\RoleType;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route(match (Auth::user()->type) {
            RoleType::PATIENT->value => 'appointments.index',
            RoleType::CLINIC->value => 'clinic.stats',
            RoleType::SUPER_ADMIN->value => 'dashboard.getstats',
            default => abort(403),
        });
    }
}
