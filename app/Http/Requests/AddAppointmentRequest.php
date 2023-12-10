<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AddAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'patient_id' => 'required|integer',
            'clinic_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'planned_datetime' => 'required|date_format:Y-m-d H:i:s',
            'planned_duration' => 'integer|nullable',
            'description' => 'string|nullable',
        ];
    }

    public function toDTO(): \App\Domain\Patient\AddAppointmentDto
    {
        $planned_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $this->input('planned_datetime'));

        return new \App\Domain\Patient\AddAppointmentDto(
            patient_id: $this->input('patient_id'),
            clinic_id: $this->input('clinic_id'),
            doctor_id: $this->input('doctor_id'),
            planned_datetime: $planned_datetime,
            description: $this->input('description'),
            planned_duration: $this->input('planned_duration') ?? \App\Models\Appointment::DEFAULT_PLANNED_DURATION,
        );
    }
}
