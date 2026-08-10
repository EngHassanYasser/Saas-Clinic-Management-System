<?php

namespace App\Enums;

enum EnRoleType: string
{
    case SUPER_ADMIN = 'super_admin';
    case CLINIC = 'clinic';
    case PATIENT = 'patient';
}
