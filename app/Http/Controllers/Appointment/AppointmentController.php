<?php

namespace App\Http\Controllers\Appointment;

use App\Domain\Appointment\AddAppointmentAction;
use App\Domain\Appointment\CancelAppointmentAction;
use App\Domain\Appointment\MoveAppointmentAction;
use App\Http\Requests\AddAppointmentRequest;
use App\Http\Requests\MoveAppointmentRequest;
use App\Models\Appointment;

class AppointmentController
{
    public function add(AddAppointmentRequest $request): Appointment
    {
        /** @var AddAppointmentAction $action */
        $action = app(AddAppointmentAction::class);

        return $action->execute($request->toDTO());
    }

    public function cancel(int $appointmentId): void
    {
        /** @var CancelAppointmentAction $action */
        $action = app(CancelAppointmentAction::class);

        $action->execute($appointmentId);
    }

    public function move(int $appointmentId, MoveAppointmentRequest $request): Appointment
    {
        /** @var MoveAppointmentAction $action */
        $action = app(MoveAppointmentAction::class);

        return $action->execute($appointmentId, $request->toDTO());
    }
}
