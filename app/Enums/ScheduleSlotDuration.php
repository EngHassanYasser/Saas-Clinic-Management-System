<?php

namespace App\Enums;

enum ScheduleSlotDuration: string
{
    case FIFTEEN = '15';
    case THIRTY = '30';
    case FORTY_FIVE = '45';
    case SIXTY = '60';
    case NINETY = '90';
    case ONE_TWENTY = '120';
}
