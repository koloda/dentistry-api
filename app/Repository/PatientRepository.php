<?php

namespace App\Repository;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository
{
    private int $clinicId = 0;

    public function __construct(?User $doctor = null)
    {
        if (! $doctor && php_sapi_name() !== 'cli') {
            throw new \Exception('Doctor is required');
        }

        if ($doctor instanceof User) {
            $this->clinicId = $doctor->clinic_id;
        }
    }

    public function getPatientByPhoneNumber(string $phoneNumber): Patient
    {
        return $this->query()->where('number', $phoneNumber)->firstOrFail();
    }

    public function getById(int $id): Patient
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @return Collection<int, Patient>
     */
    public function list(): Collection
    {
        return $this->query()->get();
    }

    /**
     * @return Builder<Patient>
     */
    private function query(): Builder
    {
        if (! $this->clinicId) {
            return Patient::query();
        }

        return Patient::query()->where('clinic_id', $this->clinicId);
    }
}
