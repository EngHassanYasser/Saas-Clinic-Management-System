<?php

namespace App\Services\Schedule;

use App\Models\Schedule;

class ScheduleConflictService
{

    public function hasScheduleConflict(array $data,int $clinicId, ?int $ignoreId = null): bool
    {
        return Schedule::where('doctor_id', $data['doctor_id'])
            ->where('clinic_id', $clinicId)
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
