<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Payment\Webhooks\PayPalWebhookHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayPalWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        PayPalWebhookHandler $handler
    ): Response {

        $handler->handle(
            payload: $request->all(),
            headers: $request->headers->all()
        );

        return response()->noContent();
    }
}