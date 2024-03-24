<?php

namespace App\Domain\Appointment;

use App\Exceptions\HastHttpStatus;

class AppointmentException extends \DomainException
{
    use HastHttpStatus;

    public const string PATIENT_NOT_REGISTERED = 'Patient is not registered in this clinic';
}
