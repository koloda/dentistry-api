<?php

namespace App\Domain\Patient;

use App\Models\Appointment;
use Carbon\CarbonImmutable;

class AddAppointmentDto
{
    public function __construct(
        public int $patient_id,
        public int $clinic_id,
        public int $doctor_id,
        public CarbonImmutable $planned_datetime,
        public string $description,
        public ?int $planned_duration = Appointment::DEFAULT_PLANNED_DURATION,
    ) {
    }
}
