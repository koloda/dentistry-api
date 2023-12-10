<?php

namespace App\Repository;

use App\Models\Clinic;
use App\Models\User;

class UserRepository
{
    private int $clinicId = 0;

    public function __construct(
    ) {
        // if not cli app - use user's clinic id
        if (auth()->check()) {
            $this->clinicId = auth()->user()->clinic_id;
        }
    }

    public function getById(int $id): User
    {
        return $this->query()->findOrFail($id);
    }

    public function getUserByPhone(string $phone): User
    {
        return $this->query()->where('phone', $phone)->firstOrFail();
    }

    private function query(): \Illuminate\Database\Eloquent\Builder
    {
        if (! $this->clinicId) {
            return User::query();
        }

        return User::query()->where('clinic_id', $this->clinicId);
    }
}
