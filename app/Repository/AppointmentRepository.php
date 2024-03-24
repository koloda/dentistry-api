<?php

namespace App\Repository;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    public function list(int $clinicId): Collection
    {
        return $this->query()->where('clinic_id', $clinicId)->get();
    }

    public function getDoctorAppointments(User $doctor, Carbon|CarbonImmutable|null $date)
    {
        $query = $this->query()->where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderByRaw('planned_datetime ASC');


        if ($date) {
            $date = clone $date;
            $query->whereBetween('planned_datetime', [
                $date->startOfDay()->toDateTimeString(),
                $date->endOfDay()->toDateTimeString(),
            ]);
        }

        return $query->get();
    }

    private function query(): Builder
    {
        if (! $this->clinicId) {
            return Appointment::query();
        }

        return Appointment::query()->where('clinic_id', $this->clinicId);
    }
}
