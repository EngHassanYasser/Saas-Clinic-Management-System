<?php

namespace App\Services\Schedule;

use App\DTOs\Services\Schedule\HasScheduleConflictDTO;
use App\Models\Schedule;

class ScheduleConflictService
{
    public function hasScheduleConflict(HasScheduleConflictDTO $dto, Schedule $schedule): bool
    {
        $ignoreId = $schedule->id;
        
        return Schedule::where('doctor_id', $schedule->doctor_id)
            ->where('clinic_id', $schedule->clinic_id)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->where(function ($query) use ($dto) {
                $query->where('start_time', '<', $dto->endTime)
                    ->where('end_time', '>', $dto->startTime);
            })
            ->whereHas('days', function ($query) use ($dto) {
                $query->whereIn('days.id', $dto->dayIds);
            })->exists();
    }
}
