<?php

namespace App\Domain\Appointment;

use App\Exceptions\HastHttpStatus;

class AppointmentException extends \DomainException
{
    use HastHttpStatus;
}
