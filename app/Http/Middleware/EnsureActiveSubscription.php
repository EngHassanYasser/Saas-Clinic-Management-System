<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->clinic) {
            abort(403);
        }

        $subscription = $user->clinic->latestSubscription;

        if (! $subscription || ! $subscription->isActive()) {
            return redirect()
                ->route('subscriptions.index')
                ->with('error', 'لازم يكون عندك اشتراك فعال لاستخدام الخدمة.');
        }

        return $next($request);
    }
}
