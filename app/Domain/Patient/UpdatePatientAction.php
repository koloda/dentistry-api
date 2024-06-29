<?php

declare(strict_types=1);

namespace App\Domain\Patient;

use App\Models\Patient;

class UpdatePatientAction
{
    public function execute(Patient $patient, StorePatientDTO $storePatientDTO): Patient
    {
        $patient->clinic_id = $storePatientDTO->clinicId;
        $patient->name = $storePatientDTO->name;
        $patient->phone = $storePatientDTO->phone;
        $patient->address = $storePatientDTO->address;
        $patient->date_of_birth = $storePatientDTO->dateOfBirth;

        $patient->allergies = $storePatientDTO->allergies;
        $patient->medical_history = $storePatientDTO->medicalHistory;

        $patient->save();

        return $patient;
    }
}
