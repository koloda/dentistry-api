<?php

namespace App\Http\Controllers\Patient;

use App\Domain\Appointment\AddAppointmentAction;
use App\Http\Requests\AddAppointmentRequest;
use App\Models\Appointment;

class AppointmentController
{
    public function add(AddAppointmentRequest $request): Appointment
    {
        /** @var AddAppointmentAction $action */
        $action = app(AddAppointmentAction::class);

        return $action->execute($request->toDTO());
    }
}
