<?php

namespace App\Http\Controllers\Subscription;

use App\Enums\EnSubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Services\Plan\PlanQueryService;
use App\Services\Subscription\SubscriptionQueryService;
use App\services\Subscription\SubscriptionService;
use App\Services\Subscription\SubscriptionStatisticsService;
use Illuminate\Support\Facades\Concurrency;

class SubscriptoinController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private SubscriptionQueryService $subscriptionQueryService,
        private SubscriptionStatisticsService $subscriptionStatusticsService,
        private PlanQueryService $planQueryService,
    ) {}

    public function index()
    {
        [$subscriptions,$plans,$stats,$clinics] = Concurrency::run([
            fn () => $this->subscriptionQueryService->getAll(),
            fn () => $this->planQueryService->getAll(),
            fn () => $this->subscriptionStatusticsService->getStats(),
            fn () => $this->subscriptionQueryService->getAll(),
        ]);
        $statuses = enumToArray(EnSubscriptionStatus::class);

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

    public function store(int $clinicId,int $planId)
    {
        $this->subscriptionService->add($clinicId,$planId);

        return redirect()->route('subscriptions.index')->with('message', 'subscription added successfully');
    }
}
