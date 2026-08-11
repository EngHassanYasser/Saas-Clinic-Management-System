<?php

namespace App\Http\Controllers\Subscription;

use App\Enums\EnSubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\Clinic\ClinicQueryService;
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
        private ClinicQueryService $clinicQueryService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Subscription::class);

        $plans = $this->planQueryService->getAll();
        $stats = $this->subscriptionStatusticsService->getStats();
        [$subscriptions,$clinics] = Concurrency::run([
            fn () => $this->clinicQueryService->getAll(),
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

    public function renew(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $isRenewed = $this->subscriptionService->renew($subscription);
        $message = $isRenewed ? 'subscription renewed successfully' : 'failed to isRenewed subscription';

        return redirect()->route('subscriptions.index')->with('message', $message);
    }

    public function store(int $clinicId, int $planId)
    {
        $this->authorize('create', Subscription::class);

        $this->subscriptionService->add($clinicId, $planId);

        return redirect()->route('subscriptions.index')->with('message', 'subscription added successfully');
    }
}
