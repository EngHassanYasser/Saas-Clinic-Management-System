<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Webhooks\PayPalWebhookController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post(
    '/appointments/{appointment}/pay',
    [PaymentController::class, 'pay']
);

Route::post(
    '/webhooks/stripe',
    StripeWebhookController::class
);

Route::post(
    '/webhooks/paypal',
    PayPalWebhookController::class
);
