<?php

namespace App\services;

use App\Exceptions\ScheduleConflictException;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function getAll()
    {
        return Doctor::with(['specialities', 'schedules.days'])
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
    }
    public function update(array $data,int $id)
    {
        return DB::transaction(function () use ($data, $id) {
            if ($this->hasScheduleConflict($data, $id)) {
                throw new ScheduleConflictException('يوجد تداخل في جدول العمل.');
            }

            $schedule = Schedule::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $schedule->update([
                'start_time'    => $data['start_time'],
                'end_time'      => $data['end_time'],
                'slot_duration' => $data['slot_duration'],
                'start_break'   => $data['start_break'],
                'end_break'     => $data['end_break'],
                'is_available'  => $data['is_available'],
            ]);

            $schedule->days()->sync($data['day_ids']);

            return $schedule->fresh();
        }, 3);
    }
    public function addNew(array $data):schedule
    {
        return  DB::transaction(function () use ($data) {
            if ($this->hasScheduleConflict($data)) {
                throw new ScheduleConflictException('يوجد تداخل مع جدول يوم الأحد.');
            }
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
        }, 3);
    }
    public function getWeekDays():Collection
    {
        return day::select(['id', 'name'])->get();
    }
    public function delete(int $scheduleId,int $clinicId): bool
    {
        $schedule = Schedule::where('clinic_id', $clinicId)->findOrFail($scheduleId);
        return $schedule->delete();
    }
    public function hasScheduleConflict(array $data, ?int $ignoreId = 0): bool
    {
        return Schedule::where('doctor_id', $data['doctor_id'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->where(function ($query) use ($data) {
                $query->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            })
            ->whereHas('days', function ($query) use ($data) {
                $query->whereIn('days.id', $data['day_ids']);
            })
            ->exists();
    }
}
