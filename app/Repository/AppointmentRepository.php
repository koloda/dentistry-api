<?php

namespace App\Repository;

use App\Domain\Appointment\AppointmentStatus;
use App\Domain\Appointment\ListAppointmentDTO;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
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
    public function list(): Collection
    {
        return $this->query()->get();
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getDoctorAppointments(
        User $doctor,
        ?ListAppointmentDTO $params = null,
    ): Collection
    {
        $query = $this->query()->where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderByRaw('planned_datetime ASC');

        if ($params) {
            $query->where('planned_datetime', '>=', $params->fromDate)
                ->where('planned_datetime', '<=', $params->toDate);
        }

        return $query->get();
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getPatientAppointments(Patient $patient): Collection
    {
        $query = $this->query()->where('patient_id', $patient->id)
            ->with('doctor')
            ->orderByRaw('planned_datetime ASC');

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
