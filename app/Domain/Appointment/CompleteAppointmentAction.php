<?php

namespace App\Domain\Appointment;

use App\Models\Appointment;

class CompleteAppointmentAction
{
    public function execute(Appointment $appointment, CompleteAppointmentDTO $dto): Appointment
    {
        if (! $appointment->status->isScheduled()) {
            throw new AppointmentException('Only scheduled appointments can be completed');
        }

        if ($dto->description) {
            $appointment->description = $dto->description;
        }

        $appointment->status = AppointmentStatus::Executed;
        $appointment->executed_datetime = $dto->executed_datetime ?: now();
        $appointment->save();

        return $appointment;
    }
}