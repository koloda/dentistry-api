<?php

declare(strict_types=1);

namespace App\Domain\Appointment;

use Carbon\CarbonImmutable;

class ListAppointmentDTO
{
    public function __construct(
        public CarbonImmutable $fromDate,
        public CarbonImmutable $toDate,
    ) {
    }
}
