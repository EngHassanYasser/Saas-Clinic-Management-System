<?php

namespace App\http\Controllers;

class ClinicStatisticsController extends Controller
{
    public function getStats()
    {
        return view('clinics.stats');
    }
}
