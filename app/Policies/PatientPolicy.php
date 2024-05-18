<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $doctor, Patient $patient): bool
    {
        return $doctor->clinic_id === $patient->clinic_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $doctor, Patient $patient): bool
    {
        return $doctor->clinic_id === $patient->clinic_id;
    }

    public function delete(User $doctor, Patient $patient): bool
    {
        return $doctor->clinic_id === $patient->clinic_id;
    }

    public function restore(User $doctor, Patient $patient): bool
    {
        return $doctor->clinic_id === $patient->clinic_id;
    }

    public function forceDelete(User $doctor, Patient $patient): bool
    {
        return $doctor->clinic_id === $patient->clinic_id;
    }
}
