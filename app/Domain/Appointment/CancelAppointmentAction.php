<?php

namespace App\Domain\Appointment;

use App\Repository\AppointmentRepository;

class CancelAppointmentAction
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    ) {
    }

    public function execute(int $appointmentId): void
    {
        $appointment = $this->appointmentRepository->getById($appointmentId);
        if ($appointment->status !== AppointmentStatus::Scheduled) {
            throw new AppointmentException('Appointment can be cancelled only if it is scheduled');
        }

        $appointment->status = AppointmentStatus::Cancelled;
        $appointment->save();
    }
}
