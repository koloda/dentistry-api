<?php

namespace App\Domain\Appointment;

use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\UserRepository;

class MoveAppointmentAction
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private UserRepository $userRepository,
    ) {}
    public function execute(int $appointmentId, MoveAppointmentDTO $dto): Appointment
    {
        $appointment = $this->appointmentRepository->getById($appointmentId);

        if ($dto->planned_datetime->isPast()) {
            throw new AppointmentException('Cannot move appointment to past');
        }

        $doctor = $this->userRepository->getById($appointment->doctor_id);
        //check if there is no other appointment in this time
        $appointments = $this->appointmentRepository->getDoctorAppointmentsForDay(
            $doctor,
            $dto->planned_datetime,
        );
        foreach ($appointments as $appointment) {
            if ($appointment->id === $appointmentId) {
                continue;
            }

            if ($appointment->planned_datetime->between(
                $dto->planned_datetime,
                $dto->planned_datetime->copy()->addMinutes($dto->planned_duration ?? $appointment->planned_duration),
            )) {
                throw new AppointmentException('Cannot move appointment to this time');
            }
        }

        $appointment->planned_datetime = $dto->planned_datetime;
        if ($dto->planned_duration) {
            $appointment->planned_duration = $dto->planned_duration;
        }

        $appointment->save();

        return $appointment;
    }
}
