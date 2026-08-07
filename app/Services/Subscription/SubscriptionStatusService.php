<?php

namespace App\Services\Subscription;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

class SubscriptionStatusService
{
    public function changeStatus(
        int $subscriptionId,
        SubscriptionStatus $newStatus
    ): bool {
        $subscription = Subscription::find($subscriptionId);

        if (! $subscription) {
            return false;
        }

        $subscription->update([
            'status' => $newStatus->value,
        ]);

        return true;
    }
}
