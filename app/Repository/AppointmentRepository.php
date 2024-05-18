<?php

namespace App\Repository;

use App\Domain\Appointment\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository
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

    /**
     * @return Collection<int, Appointment>
     */
    public function list(int $clinicId): Collection
    {
        return $this->query()->where('clinic_id', $clinicId)->get();
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getDoctorAppointments(User $doctor, Carbon|CarbonImmutable|null $date): Collection
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

    /**
     * @return Builder<Appointment>
     */
    private function query(): Builder
    {
        if (! $this->clinicId) {
            return Appointment::query();
        }

        return Appointment::query()->where('clinic_id', $this->clinicId);
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getCancelledAppointments(Clinic $clinic): Collection
    {
        return $this->query()
            ->where('clinic_id', $clinic->id)
            ->where('status', AppointmentStatus::Cancelled->value)
            ->get();
    }
}
