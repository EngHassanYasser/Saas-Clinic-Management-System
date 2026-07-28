<?php

namespace App\Enums;

enum RoleType: string
{
    case SUPER_ADMIN = 'super_admin';
    case CLINIC = 'clinic';
    case PATIENT = 'patient';
}
