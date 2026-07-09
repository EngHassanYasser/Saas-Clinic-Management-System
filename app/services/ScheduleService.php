<?php

namespace App\services;

use App\Models\day;
use App\Models\doctor;
use App\Models\schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function getAll()
    {
        // DB::enableQueryLog();
        return doctor::with(['specialities','schedules.days'])
            ->withCount('schedules')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'speciality_name' => $doctor->specialities->first()->name,
                    'schedules' => $doctor->schedules,
                    'schedules_count' => $doctor->schedules_count,
                ];
            });
        // dd(DB::getQueryLog());
    }
    public function addNew($data)
    {
        return  DB::transaction(function() use ($data) {
            $schedule = schedule::create([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'slot_duration' => $data['slot_duration'],
                'start_break' => $data['start_break'],
                'end_break' => $data['end_break'],
                'is_available' => $data['is_available'],
                'doctor_id' => $data['doctor_id'],
                'clinic_id' => Auth::User()->clinic_id
            ]);
            $schedule->days()->attach($data['day_ids']);

            return $schedule;
        });
    }
    public function getWeekDays(){
        return day::select(['id','name'])->get();
    }
}
