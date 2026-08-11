<?php

namespace App\services\ActivityLog;

use App\Models\Activity_log;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogQueryService
{
    public function getLastActivities(): LengthAwarePaginator
    {
        return Activity_log::select([
            'id',
            'type',
            'title',
            'description',
            'status',
            'subject_type',
            'subject_id',
            'created_by',
            'created_at',
        ])->latest('created_at')->paginate(5);
    }
}
