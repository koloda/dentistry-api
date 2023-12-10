<?php

namespace App\Domain\Clinic;

class AddUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public int $clinicId,
    ) {
    }
}
