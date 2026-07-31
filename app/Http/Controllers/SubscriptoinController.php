<?php

namespace App\Http\Controllers;

use App\Http\Requests\subscriptions\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Enums\SubscriptionStatus;
use App\Services\ClinicService;
use App\services\SubscriptionService;

class SubscriptoinController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private ClinicService $clinicService
    ) {}
    public function index()
    {
        $subscriptions =  $this->subscriptionService->getAll();
        $plans = Plan::get(['id', 'name', 'monthly_price']);
        $stats = $this->subscriptionService->getStats();
        $clinics  = $this->clinicService->getAll();
        $statuses = enumToArray(SubscriptionStatus::class);
        return view(
            'subscriptions.index',
            compact(
                'subscriptions',
                'plans',
                'stats',
                'clinics',
                'statuses'
            )
        );
    }
    public function changeStatus(int $subscriptionID,SubscriptionStatus $newStatus)
    {
        $isUpdated = $this->subscriptionService->changeStatus($subscriptionID, $newStatus);
        $message = $isUpdated ? 'status updated successfully' : 'failed to update status';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
    public function renew(int $subscriptionID)
    {
        $isRenewed = $this->subscriptionService->renew($subscriptionID);
        $message = $isRenewed ? 'subscription renewed successfully' : 'failed to isRenewed subscription';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
    public function store(StoreSubscriptionRequest $request)
    {
        $this->subscriptionService->add($request->validated());
        return redirect()->route('subscriptions.index')->with('message', 'subscription added successfully');
    }
}
