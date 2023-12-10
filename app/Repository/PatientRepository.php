<?php

namespace App\Repository;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository
{
    private int $clinicId = 0;

    public function __construct(
    ) {
        // if not cli app - use user's clinic id
        if (auth()->check()) {
            $this->clinicId = auth()->user()->clinic_id;
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

    public function list(): Collection
    {
        return $this->query()->get();
    }

    private function query(): Builder
    {
        if (! $this->clinicId) {
            return Patient::query();
        }

        return Patient::query()->where('clinic_id', $this->clinicId);
    }
}
