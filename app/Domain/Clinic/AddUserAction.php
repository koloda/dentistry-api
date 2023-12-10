<?php

namespace App\Domain\Clinic;

use App\Models\User;
use App\Repository\ClinicRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddUserAction
{
    public function __construct(
        private readonly ClinicRepository $clinicRepository,
    ) {
    }

    public function execute(AddUserDTO $payload): User
    {
        $clinic = $this->clinicRepository->getById($payload->clinicId);

        $user = new User();
        $user->name = $payload->name;
        $user->email = $payload->email;
        $user->phone = $payload->phone;
        $user->password = Hash::make(Str::random(8));

        $user->clinic()->associate($clinic);

        $user->save();

        return $user;
    }
}
