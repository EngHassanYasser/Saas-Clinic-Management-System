<?php

namespace App\Services\Schedule;

use App\DTOs\Services\Schedule\ScheduleService\StoreScheduleDTO;
use App\DTOs\Services\Schedule\ScheduleService\UpdateScheduleDTO;
use App\Exceptions\ScheduleConflictException;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function __construct(private ScheduleConflictService $scheduleConfilctService) {}

    public function add(StoreScheduleDTO $dto, int $clinicId): Schedule
    {
        return DB::transaction(function () use ($dto, $clinicId) {
            if ($this->scheduleConfilctService->hasScheduleConflict($data, $clinicId)) {
                throw new ScheduleConflictException('يوجد تداخل مع جدول يوم الأحد.');
            }
            $schedule = Schedule::create([
                'start_time' => $dto->startTime,
                'end_time' => $dto->endTime,
                'slot_duration' => $dto->slotDuration,
                'start_break' => $dto->startBreak,
                'end_break' => $dto->endBreak,
                'is_available' => $dto->isAvailable,
                'doctor_id' => $dto->doctorId,
                'clinic_id' => $clinicId,
            ]);
            $schedule->days()->attach($dto->dayIds);

            return $schedule;
        }, 3);
    }

    public function update(UpdateScheduleDTO $dto, int $scheduleId): Schedule
    {
        return DB::transaction(function () use ($dto, $scheduleId) {

            $schedule = Schedule::where('id', $scheduleId)
                ->lockForUpdate()
                ->firstOrFail();

            $conflictData = array_merge($data, [
                'doctor_id' => $schedule->doctor_id,
            ]);

            if ($this->scheduleConfilctService->hasScheduleConflict(
                $conflictData,
                $schedule->clinic_id,
                $schedule->id
            )) {
                throw new ScheduleConflictException(
                    'يوجد تداخل في جدول العمل.'
                );
            }

            $schedule->update([
                'start_time' => $dto->startTime,
                'end_time' => $dto->endTime,
                'slot_duration' => $dto->slotDuration,
                'start_break' => $dto->startBreak,
                'end_break' => $dto->endBreak,
                'is_available' => $dto->isAvailable,
            ]);

            $schedule->days()->sync($dto->dayIds);

            return $schedule->fresh();
        }, 3);
    }

    public function delete(int $scheduleId, int $clinicId): bool
    {
        $schedule = Schedule::where('clinic_id', $clinicId)->findOrFail($scheduleId);

        return $schedule->delete();
    }
}
