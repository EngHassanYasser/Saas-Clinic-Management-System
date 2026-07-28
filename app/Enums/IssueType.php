<?php

namespace App\Enums;

enum IssueType: string
{
    case COMPLAINT = 'complaint';
    case SUGGESTION = 'suggestion';
    case TECHNICAL_ISSUE = 'technical_issue';
    case BILLING = 'billing';
    case MEDICAL = 'medical';
    case OTHER = 'other';
}
