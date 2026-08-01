<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Enums\SubscriptionStatus;
use App\services\Subscription\SubscriptionStatusService;

class SubscriptionStatusController extends Controller
{
    public function __construct(private SubscriptionStatusService $subscriptionStatusService){}
    public function changeStatus(int $subscriptionID,SubscriptionStatus $newStatus)
    {
        $isUpdated = $this->subscriptionStatusService->changeStatus($subscriptionID, $newStatus);
        $message = $isUpdated ? 'status updated successfully' : 'failed to update status';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
}
