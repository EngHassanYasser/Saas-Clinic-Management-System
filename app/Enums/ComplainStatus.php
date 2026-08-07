<?php

namespace App\Enums;

enum ComplainStatus: string
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';
}
