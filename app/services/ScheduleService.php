<?php

namespace App\services;

use App\Models\doctor;
use App\Models\schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function getAll()
    {
        // DB::enableQueryLog();
        return doctor::with(['schedules', 'specialities'])
            ->withCount('schedules')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'speciality_name' =>$doctor->specialities->first()->name,
                    'schedules' => $doctor->schedules,
                    'schedules_count' => $doctor->schedules_count,
                ];
            });
        // dd(DB::getQueryLog());
    }
}
