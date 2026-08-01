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
        return Subscription::whereKey($subscriptionId)
            ->update([
                'status' => $newStatus,
            ]) > 0;
    }
}
