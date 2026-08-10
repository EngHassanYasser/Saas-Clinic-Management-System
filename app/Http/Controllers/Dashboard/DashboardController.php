<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\EnRoleType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Dashboard::class);

        return redirect()->route(match (Auth::user()->type) {
            EnRoleType::PATIENT => 'appointments.index',
            EnRoleType::CLINIC => 'clinic.stats',
            EnRoleType::SUPER_ADMIN => 'dashboard.getstats',
            default => abort(403),
        });
    }
}
