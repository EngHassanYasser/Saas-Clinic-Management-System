<?php

namespace App\Enums;

enum VicationStatus: string
{

    case UPCOMING = 'upcoming';
    case ACTIVE = 'active';
    case ENDED  = 'ended';
}
