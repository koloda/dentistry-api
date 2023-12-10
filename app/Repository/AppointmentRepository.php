<?php

namespace App\Repository;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Doctor;

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
            return \App\Models\Appointment::query();
        }

        return \App\Models\Appointment::query()->where('clinic_id', $this->clinicId);
    }
}
