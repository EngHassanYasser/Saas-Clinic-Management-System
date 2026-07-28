<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
}