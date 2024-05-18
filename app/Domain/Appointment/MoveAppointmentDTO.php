<?php

namespace App\Domain\Appointment;

use Carbon\CarbonImmutable;

class MoveAppointmentDTO
{
    public function __construct(
        public CarbonImmutable $planned_datetime,
        public ?int $planned_duration,
    ) {
    }
}
