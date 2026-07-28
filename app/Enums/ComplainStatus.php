<?php

namespace App\Enums;

enum ComplainStatus: string
{
    case PENDING = 'pending';
    case REVIEWING = 'reviewing';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';
}
