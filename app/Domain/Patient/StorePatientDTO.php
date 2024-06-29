<?php

namespace App\Domain\Patient;

use Carbon\CarbonImmutable;

class StorePatientDTO
{
    public function __construct(
        public int $clinicId,
        public string $name,
        public string $phone,
        public string $address,
        public CarbonImmutable $dateOfBirth,
        public string $gender,
        public ?string $medicalHistory = null,
        public ?string $allergies = null,
    ) {
    }
}
