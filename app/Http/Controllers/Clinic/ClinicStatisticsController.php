<?php

namespace App\http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;

class ClinicStatisticsController extends Controller
{
    public function getStats()
    {
        $this->authorize('viewAny', Clinic::class);

        return view('clinics.stats');
    }
}
