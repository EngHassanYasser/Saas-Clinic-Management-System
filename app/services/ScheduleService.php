<?php

namespace App\services;

use App\Exceptions\ScheduleConflictException;
use App\Models\Clinic;
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
        return Doctor::select('id', 'name')
            ->with([
                'specialities:id,name',
                'schedules.days:id,name'
            ])->withCount('schedules')
            ->get();
    }
    public function update(array $data, int $id)
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
    public function addNew(array $data): Schedule
    {
        return  DB::transaction(function () use ($data) {
            if ($this->hasScheduleConflict($data)) {
                throw new ScheduleConflictException('يوجد تداخل مع جدول يوم الأحد.');
            }
            $clinicId=Clinic::where('owner_id',Auth::id())->value('id');
            $schedule = Schedule::create([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'slot_duration' => $data['slot_duration'],
                'start_break' => $data['start_break'],
                'end_break' => $data['end_break'],
                'is_available' => $data['is_available'],
                'doctor_id' => $data['doctor_id'],
                'clinic_id' => $clinicId
            ]);
            $schedule->days()->attach($data['day_ids']);

            return $schedule;
        }, 3);
    }
    public function getWeekDays(): Collection
    {
        return Day::select(['id', 'name'])->get();
    }
    public function delete(int $scheduleId, int $clinicId): bool
    {
        $schedule = Schedule::where('clinic_id', $clinicId)->findOrFail($scheduleId);
        return $schedule->delete();
    }
    public function hasScheduleConflict(array $data, ?int $ignoreId = null): bool
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
