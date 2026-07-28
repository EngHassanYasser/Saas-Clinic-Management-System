<?php

namespace App\Enums;

enum DepartmentType: string
{
    case RADIOLOGY = 'radiology';
    case RECEPTION = 'reception';
    case LABORATORY = 'laboratory';
    case PHARMACY = 'pharmacy';
    case ACCOUNTING = 'accounting';
    case CUSTOMER_SERVICE = 'customer_service';
    case NURSING = 'nursing';
    case ADMINSTRATION = 'administration';
    case CLINICS = 'clinics';
    case TECHNICAL_SUPPORT = 'technical_support';
}
