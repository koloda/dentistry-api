<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    private int $clinicId = 0;

    public function __construct(?User $doctor = null)
    {
        // if (! $doctor && php_sapi_name() !== 'cli') {
        //     throw new \Exception('Doctor is required');
        // }

        if ($doctor instanceof User) {
            $this->clinicId = $doctor->clinic_id;
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

    /**
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    private function query(): \Illuminate\Database\Eloquent\Builder
    {
        if (! $this->clinicId) {
            return User::query();
        }

        return User::query()->where('clinic_id', $this->clinicId);
    }
}
