<?php

namespace App\Enums;

enum EnIssueType: string
{
    case COMPLAINT = 'complaintt';
    case SUGGESTION = 'suggestion';
    case TECHNICAL_ISSUE = 'technical_issue';
    case BILLING = 'billing';
    case MEDICAL = 'medical';
    case OTHER = 'other';
}
