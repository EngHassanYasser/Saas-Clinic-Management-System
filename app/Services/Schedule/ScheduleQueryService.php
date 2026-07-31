<?php

namespace App\Services;

use App\Models\Day;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class ScheduleQueryService
{
    public function getAll()
    {
        return Doctor::select('id', 'name')
            ->with([
                'specialities:id,name',
                'schedules.days:id,name'
            ])->withCount('schedules')
            ->get();
    }
    public function getWeekDays(): Collection
    {
        return Day::select(['id', 'name'])->get();
    }
}
