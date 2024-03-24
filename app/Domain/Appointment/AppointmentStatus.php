<?php

namespace App\Domain\Appointment;

enum AppointmentStatus: string
{
    case Scheduled = 'scheduled';
    case Executed = 'executed';
    case Cancelled = 'cancelled';

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function isScheduled(): bool
    {
        return $this === self::Scheduled;
    }

    public function isExecuted(): bool
    {
        return $this === self::Executed;
    }
}
