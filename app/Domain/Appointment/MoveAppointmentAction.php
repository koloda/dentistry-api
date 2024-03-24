<?php

namespace App\Domain\Appointment;

use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\UserRepository;

class MoveAppointmentAction
{
    public function __construct(
        private AppointmentRepository   $appointmentRepository,
        private readonly UserRepository $userRepository,
    ) {}
    public function execute(Appointment $appointment, MoveAppointmentDTO $dto): Appointment
    {
        if ($dto->planned_datetime->isPast()) {
            throw new AppointmentException('Cannot move appointment to past');
        }

        $doctor = $this->userRepository->getById($appointment->doctor_id);
        //check if there is no other appointment in this time
        $existedAppointments = $this->appointmentRepository->getDoctorAppointments(
            $doctor,
            $dto->planned_datetime,
        );
        foreach ($existedAppointments as $existedAppointment) {
            if ($existedAppointment->id === $appointment->id) {
                continue;
            }

            if ($existedAppointment->planned_datetime->between(
                $dto->planned_datetime,
                $dto->planned_datetime->copy()->addMinutes($dto->planned_duration ?? $existedAppointment->planned_duration),
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
