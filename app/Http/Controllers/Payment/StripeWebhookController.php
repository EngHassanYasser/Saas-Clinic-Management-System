<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Payment\Webhooks\StripeWebhookHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        StripeWebhookHandler $handler
    ): Response {
        $handler->handle(
            payload: $request->getContent(),
            signature: $request->header('Stripe-Signature')
        );

        return response()->noContent();
    }
}