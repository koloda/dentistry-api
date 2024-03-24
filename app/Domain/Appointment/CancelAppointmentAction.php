<?php

namespace App\Domain\Appointment;

use App\Models\Appointment;

class CancelAppointmentAction
{
    public function execute(Appointment $appointment): void
    {
        if ($appointment->status !== AppointmentStatus::Scheduled) {
            throw new AppointmentException('Appointment can be cancelled only if it is scheduled');
        }

        $appointment->status = AppointmentStatus::Cancelled;
        $appointment->save();
    }
}
