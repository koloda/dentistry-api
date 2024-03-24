<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $doctor, Appointment $appointment): bool
    {
        return $doctor->id === $appointment->doctor_id;
    }

    public function create(User $doctor): bool
    {
        return true;
    }

    public function update(User $doctor, Appointment $appointment): bool
    {
        return $doctor->id === $appointment->doctor_id;
    }

    public function delete(User $doctor, Appointment $appointment): bool
    {
        return $doctor->id === $appointment->doctor_id;
    }

    public function restore(User $doctor, Appointment $appointment): bool
    {
        return $doctor->id === $appointment->doctor_id;
    }

    public function forceDelete(User $doctor, Appointment $appointment): bool
    {
        return $doctor->id === $appointment->doctor_id;
    }
}
