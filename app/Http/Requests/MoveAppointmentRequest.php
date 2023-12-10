<?php

namespace App\Http\Requests;

use App\Domain\Appointment\MoveAppointmentDTO;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;

class MoveAppointmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'planned_datetime' => ['required', 'date'],
            'planned_duration' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ];
    }

    public function toDTO(): MoveAppointmentDTO
    {
        var_dump($this->input('planned_datetime'));
        $planedDatetime = CarbonImmutable::createFromFormat('Y-m-d H:i', $this->input('planned_datetime'));

        return new MoveAppointmentDTO(
            planned_datetime: $planedDatetime,
            planned_duration: $this->input('planned_duration'),
        );
    }
}
