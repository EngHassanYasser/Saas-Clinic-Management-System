<?php

namespace App\Http\Controllers\Subscription;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\subscriptions\StoreSubscriptionRequest;
use App\Models\Plan;
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
    ) {}

    public function index()
    {
        [$subscriptions,$plans,$stats,$clinics] = Concurrency::run([
            fn () => $this->subscriptionQueryService->getAll(),
            fn () => Plan::get(['id', 'name', 'monthly_price']),
            fn () => $this->subscriptionStatusticsService->getStats(),
            fn () => $this->subscriptionQueryService->getAll(),
        ]);
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
