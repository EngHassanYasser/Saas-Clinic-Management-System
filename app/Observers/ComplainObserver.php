<?php

namespace App\Observers;

use App\Models\Complain;

class ComplainObserver
{
    public function updating(Complain $complain): void
    {
        if (
            $complain->isDirty('status') &&
            in_array($complain->status, ['resolved', 'rejected']) &&
            is_null($complain->resolved_at)
        ) {
            $complain->resolved_at = now()->utc();
        }else{
            $complain->resolved_at=null;
        }
    }
    /**
     * Handle the Complain "created" event.
     */
    public function created(Complain $complain): void
    {
        //
    }

    /**
     * Handle the Complain "updated" event.
     */
    public function updated(Complain $complain): void
    {
        //
    }

    /**
     * Handle the Complain "deleted" event.
     */
    public function deleted(Complain $complain): void
    {
        //
    }

    /**
     * Handle the Complain "restored" event.
     */
    public function restored(Complain $complain): void
    {
        //
    }

    /**
     * Handle the Complain "force deleted" event.
     */
    public function forceDeleted(Complain $complain): void
    {
        //
    }
}
