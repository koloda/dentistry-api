<?php

namespace App\Domain\Clinic;

class AddClinicDTO
{
    public function __construct(
        public string $name,
        public ?string $address,
        public ?string $phone,
        public ?string $email,
        public ?string $website,
        public ?string $logo,
        public ?string $description,
        public string $status,
    ) {
    }
}
