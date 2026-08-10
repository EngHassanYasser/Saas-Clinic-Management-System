<?php

namespace App\Observers;

use App\Models\Complaint;

class ComplaintObserver
{
    public function updating(Complaint $complaint): void
    {
        if (
            $complaint->isDirty('status') &&
            in_array($complaint->status, ['resolved', 'rejected']) &&
            is_null($complaint->resolved_at)
        ) {
            $complaint->resolved_at = now()->utc();
        }else{
            $complaint->resolved_at=null;
        }
    }
    /**
     * Handle the Complaint "created" event.
     */
    public function created(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "updated" event.
     */
    public function updated(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "deleted" event.
     */
    public function deleted(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "restored" event.
     */
    public function restored(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "force deleted" event.
     */
    public function forceDeleted(Complaint $complaint): void
    {
        //
    }
}
