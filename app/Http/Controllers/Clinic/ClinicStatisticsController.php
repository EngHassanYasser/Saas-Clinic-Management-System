<?php

namespace App\http\Controllers\Clinic;

use App\Http\Controllers\Controller;

class ClinicStatisticsController extends Controller
{
    public function getStats()
    {
        return view('clinics.stats');
    }
}
