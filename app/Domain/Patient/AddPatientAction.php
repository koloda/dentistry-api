<?php

namespace App\Domain\Patient;

use App\Models\Patient;
use App\Repository\ClinicRepository;

readonly class AddPatientAction
{
    public function __construct(
        private ClinicRepository $clinicRepository,
    ) {
    }

    public function execute(StorePatientDTO $payload): Patient
    {
        $clinic = $this->clinicRepository->getById($payload->clinicId);

        $patient = new Patient();
        $patient->name = $payload->name;
        $patient->phone = $payload->phone;
        $patient->address = $payload->address;
        $patient->date_of_birth = $payload->dateOfBirth;
        $patient->gender = $payload->gender;
        $patient->medical_history = $payload->medicalHistory;
        $patient->allergies = $payload->allergies;

        $patient->clinic()->associate($clinic);

        $patient->save();

        return $patient;
    }
}
