<?php

namespace App\Domain\Appointment;

use App\Domain\Patient\AddAppointmentDto;

class AddAppointmentAction
{
    public function __construct(
        private \App\Repository\PatientRepository $patientRepository,
        private \App\Repository\ClinicRepository $clinicRepository,
        private \App\Repository\UserRepository $userRepository,
        private \App\Repository\AppointmentRepository $appointmentRepository,
    ) {
    }

    public function execute(AddAppointmentDto $payload)
    {
        $patient = $this->patientRepository->getById($payload->patient_id);
        $clinic = $this->clinicRepository->getById($payload->clinic_id);
        $doctor = $this->userRepository->getById($payload->doctor_id);

        //check if patient is already registered in clinic
        if ($patient->clinic_id != $clinic->id) {
            throw new AppointmentException('Patient is not registered in this clinic');
        }

        //check if doctor is working in clinic
        if ($doctor->clinic_id != $clinic->id) {
            throw new AppointmentException('Doctor is not working in this clinic');
        }

        if ($payload->planned_datetime->isPast()) {
            throw new AppointmentException('Appointment date is in the past');
        }

        if ($payload->planned_duration < 1 || $payload->planned_duration > 1440) {
            throw new AppointmentException('Appointment duration is invalid: ' . $payload->planned_duration);
        }

        //check if doctor is available at this time
        $appointments = $this->appointmentRepository->getDoctorAppointmentsForDay($doctor, $payload->planned_datetime);
        foreach ($appointments as $appointment) {
            //clone to avoid mutation
            $planned_datetime = clone $payload->planned_datetime;
            $appointmentEnd = $planned_datetime->addMinutes($appointment->planned_duration);
            if ($payload->planned_datetime->between($appointment->planned_datetime, $appointmentEnd)) {
                throw new AppointmentException('Doctor is not available at this time');
            }
        }

        $appointment = new \App\Models\Appointment();
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
