<?php

namespace App\Domain\Clinic;

enum ClinicStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
