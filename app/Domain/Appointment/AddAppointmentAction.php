<?php

namespace App\Domain\Appointment;

use App\Domain\Patient\AddAppointmentDto;
use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\ClinicRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;

final readonly class AddAppointmentAction
{
    public function __construct(
        private PatientRepository $patientRepository,
        private ClinicRepository $clinicRepository,
        private UserRepository $userRepository,
        private AppointmentRepository $appointmentRepository,
    ) {
    }

    public function execute(AddAppointmentDto $payload): Appointment
    {
        $patient = $this->patientRepository->getById($payload->patient_id);
        $clinic = $this->clinicRepository->getById($payload->clinic_id);
        $doctor = $this->userRepository->getById($payload->doctor_id);

        //check if patient is already registered in clinic
        if ($patient->clinic_id !== $clinic->id) {
            throw new AppointmentException(AppointmentException::PATIENT_NOT_REGISTERED);
        }

        //check if doctor is working in clinic
        if ($doctor->clinic_id !== $clinic->id) {
            throw new AppointmentException('Doctor is not working in this clinic');
        }

        if ($payload->planned_datetime->isPast()) {
            throw new AppointmentException('Appointment date is in the past');
        }

        if ($payload->planned_duration < 1 || $payload->planned_duration > 1440) {
            throw new AppointmentException('Appointment duration is invalid: '.$payload->planned_duration);
        }

        //check if doctor is available at this time
        $appointments = $this->appointmentRepository->getDoctorAppointments($doctor, $payload->planned_datetime);
        foreach ($appointments as $appointment) {
            if ($appointment->planned_datetime->between(
                $payload->planned_datetime,
                $payload->planned_datetime->copy()->addMinutes($payload->planned_duration),
            )) {
                throw new AppointmentException('Doctor is not available at this time');
            }
        }

        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->clinic_id = $clinic->id;
        $appointment->doctor_id = $doctor->id;
        $appointment->planned_datetime = $payload->planned_datetime;
        $appointment->planned_duration = $payload->planned_duration;
        $appointment->description = $payload->description;
        $appointment->save();

        return $appointment;
    }
}
