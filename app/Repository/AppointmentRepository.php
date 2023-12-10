<?php

namespace App\Repository;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AppointmentRepository
{
    private int $clinicId = 0;

    public function __construct(
    ) {
        // if not cli app - use user's clinic id
        if (auth()->check()) {
            $this->clinicId = auth()->user()->clinic_id;
        }
    }

    public function getById(int $id): Appointment
    {
        return $this->query()->findOrFail($id);
    }

    public function getDoctorAppointmentsForDay(User $doctor, Carbon $date)
    {
        $date = clone $date;

        return $this->query()->where('doctor_id', $doctor->id)
            ->whereBetween('planned_datetime', [
                $date->startOfDay()->toDateTimeString(),
                $date->endOfDay()->toDateTimeString(),
            ])
            ->get();
    }

    private function query(): Builder
    {
        if (! $this->clinicId) {
            return Appointment::query();
        }

        return Appointment::query()->where('clinic_id', $this->clinicId);
    }
}
