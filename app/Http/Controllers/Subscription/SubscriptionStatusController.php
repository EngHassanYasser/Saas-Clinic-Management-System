<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Enums\EnSubscriptionStatus;
use App\Models\Subscription;
use App\services\Subscription\EnSubscriptionStatusService;

class EnSubscriptionStatusController extends Controller
{
    public function __construct(private EnSubscriptionStatusService $subscriptionStatusService){}
    public function changeStatus(Subscription $subscription,EnSubscriptionStatus $newStatus)
    {
        $this->authorize('update',$subscription);

        $isUpdated = $this->subscriptionStatusService->changeStatus($subscription, $newStatus);
        $message = $isUpdated ? 'status updated successfully' : 'failed to update status';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
}
