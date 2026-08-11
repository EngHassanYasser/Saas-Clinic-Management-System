<?php

namespace App\Enums;

enum EnPaymentMethod: string
{
    case CARD = 'card';
    case GOOGLE_PAY = 'google_pay';
    case PAYPAL = 'paypal';
}