<?php

namespace App\Domain\Patient;

class AddPatientDTO
{
    public function __construct(
        public int $clinicId,
        public string $name,
        public string $phone,
        public string $address,
        public string $dateOfBirth,
        public string $gender,
        public ?string $medicalHistory = null,
    ) {
    }
}
