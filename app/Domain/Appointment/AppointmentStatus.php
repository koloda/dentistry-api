<?php

namespace App\Domain\Appointment;

enum AppointmentStatus: string
{
    case Scheduled = 'scheduled';
    case Executed = 'executed';
    case Cancelled = 'cancelled';
}
