<?php

namespace App\services\ActivityLog;

use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogQueryService
{
    public function getLastActivities(): LengthAwarePaginator
    {
        return ActivityLog::select([
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
