<?php

namespace App\Http\Controllers\Appointment;

use App\Domain\Appointment\AddAppointmentAction;
use App\Domain\Appointment\CancelAppointmentAction;
use App\Domain\Appointment\CompleteAppointmentAction;
use App\Domain\Appointment\MoveAppointmentAction;
use App\Http\Controllers\AuthorisedController;
use App\Http\Requests\AddAppointmentRequest;
use App\Http\Requests\CompleteAppointmentRequest;
use App\Http\Requests\MoveAppointmentRequest;
use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

/**
 * @see \App\Policies\AppointmentPolicy
 */
class AppointmentController extends AuthorisedController
{
    use AuthorizesRequests;

    public function __construct()
    {
        /** @see AppointmentPolicy */
        $this->authorizeResource(Appointment::class, 'appointment');
    }

    /**
     * @return array<string, string>
     */
    protected function resourceAbilityMap(): array
    {
        return [
            'cancel' => 'update',
            'move' => 'update',
            'complete' => 'update',
        ];
    }

    // @phpstan-ignore-next-line
    public function list(AppointmentRepository $repository): Collection
    {
        return $repository->list();
    }

    // @phpstan-ignore-next-line
    public function agenda(AppointmentRepository $repository)
    {
        return $repository->getDoctorAppointments(Request::user(), null);
    }

    public function add(
        AddAppointmentRequest $request,
        AddAppointmentAction $action,
    ): Appointment {
        return $action->execute($request->toDTO());
    }

    public function cancel(
        Appointment $appointment,
        CancelAppointmentAction $action,
    ): void {
        $action->execute($appointment);
    }

    public function move(
        Appointment $appointment,
        MoveAppointmentRequest $request,
        MoveAppointmentAction $action,
    ): Appointment {
        return $action->execute($appointment, $request->toDTO());
    }

    public function complete(
        Appointment $appointment,
        CompleteAppointmentRequest $request,
        CompleteAppointmentAction $action,
    ): Appointment {
        return $action->execute($appointment, $request->toDTO());
    }
}
