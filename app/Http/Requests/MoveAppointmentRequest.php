<?php

namespace App\Http\Requests;

use App\Domain\Appointment\MoveAppointmentDTO;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class MoveAppointmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'planned_datetime' => ['required', 'date'],
            'planned_duration' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ];
    }

    /**
     * @throws InvalidParameterException
     */
    public function toDTO(): MoveAppointmentDTO
    {
        $planedDatetime = CarbonImmutable::createFromFormat('Y-m-d H:i', $this->string('planned_datetime'));
        if (! $planedDatetime) {
            throw new InvalidParameterException('Cannot parse $plannedDatetime');
        }

        return new MoveAppointmentDTO(
            planned_datetime: $planedDatetime,
            planned_duration: $this->integer('planned_duration'),
        );
    }
}
