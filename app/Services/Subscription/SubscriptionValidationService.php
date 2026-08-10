<?php

namespace App\Services\Subscription;

use App\Enums\EnSubscriptionStatus;
use App\Models\Subscription;

class SubscriptionValidationService
{
    public function hasActiveSubscription(
        int $clinicId,
        ?int $ignoreSubscriptionId = null
    ): bool {
        return Subscription::where('clinic_id', $clinicId)
            ->where('status', EnSubscriptionStatus::ACTIVE)
            ->when(
                $ignoreSubscriptionId,
                fn($query) => $query->whereKeyNot($ignoreSubscriptionId)
            )->exists();
    }

    public function canCancel() {}
    public function isExpired() {}
    public function validateRenewal() {}
}
