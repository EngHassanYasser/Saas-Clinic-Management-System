<?php

namespace App\Enums;

enum EnSubscriptionStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case EXPIRED = 'expired';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
}