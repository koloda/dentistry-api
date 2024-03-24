<?php

namespace App\Domain\Appointment;

use Carbon\CarbonImmutable;

final class CompleteAppointmentDTO
{
    public function __construct(
        public ?CarbonImmutable $executed_datetime,
        public ?string $description,
    ) {}
}