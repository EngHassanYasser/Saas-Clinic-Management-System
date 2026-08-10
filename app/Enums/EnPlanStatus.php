<?php

namespace App\Enums;

enum EnPlanStatus: string
{
    case ACTIVE = 'active';

    case INACTIVE = 'inactive';

    case ARCHIVED = 'archived';
}