<?php

namespace App\Services;

use App\Exceptions\ScheduleConflictException;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function __construct(private ScheduleConflictService $scheduleConfilctService){}
    public function add(array $data,int $clinicId): Schedule
    {
        return  DB::transaction(function () use ($data,$clinicId) {
            if ($this->scheduleConfilctService->hasScheduleConflict($data,$clinicId)) {
                throw new ScheduleConflictException('يوجد تداخل مع جدول يوم الأحد.');
            }
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

    public function update(array $data, int $scheduleId)
    {
        return DB::transaction(function () use ($data, $scheduleId) {
            if ($this->scheduleConfilctService->hasScheduleConflict($data, $scheduleId)) {
                throw new ScheduleConflictException('يوجد تداخل في جدول العمل.');
            }

            $schedule = Schedule::where('id', $scheduleId)
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
    public function delete(int $scheduleId, int $clinicId): bool
    {
        $schedule = Schedule::where('clinic_id', $clinicId)->findOrFail($scheduleId);
        return $schedule->delete();
    }
}
