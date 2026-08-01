<?php

namespace App\Http\Controllers;

use App\Http\Requests\subscriptions\StoreSubscriptionRequest;
use App\Models\Plan;
use App\Enums\SubscriptionStatus;
use App\Services\SubscriptionQueryService;
use App\services\SubscriptionService;
use App\Services\SubscriptionStatisticsService;

class SubscriptoinController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private SubscriptionQueryService $subscriptionQueryService,
        private SubscriptionStatisticsService $subscriptionStatusticsService,
    ) {}
    public function index()
    {
        $subscriptions =  $this->subscriptionQueryService->getAll();
        $plans = Plan::get(['id', 'name', 'monthly_price']);
        $stats = $this->subscriptionStatusticsService->getStats();
        $clinics  = $this->subscriptionQueryService->getAll();
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
