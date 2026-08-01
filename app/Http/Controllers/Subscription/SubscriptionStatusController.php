<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Services\SubscriptionService;

class SubscriptionStatusController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService){}
    public function changeStatus(int $subscriptionID,SubscriptionStatus $newStatus)
    {
        $isUpdated = $this->subscriptionService->changeStatus($subscriptionID, $newStatus);
        $message = $isUpdated ? 'status updated successfully' : 'failed to update status';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
}
