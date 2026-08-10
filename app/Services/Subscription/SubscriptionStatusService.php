<?php

namespace App\Services\Subscription;

use App\Enums\EnSubscriptionStatus;
use App\Models\Subscription;

class EnSubscriptionStatusService
{
    public function changeStatus(
        Subscription $subscription,
        EnSubscriptionStatus $newStatus
    ): bool {
        if (! $subscription) {
            return false;
        }

        $subscription->update([
            'status' => $newStatus->value,
        ]);

        return true;
    }
}
