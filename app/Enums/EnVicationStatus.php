<?php

namespace App\Enums;

enum EnVacationStatus: string
{

    case UPCOMING = 'upcoming';
    case ACTIVE = 'active';
    case ENDED  = 'ended';
}
