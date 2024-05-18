<?php

namespace App\Http\Requests;

use App\Domain\Appointment\CompleteAppointmentDTO;
use Illuminate\Foundation\Http\FormRequest;

class CompleteAppointmentRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'executed_datetime' => 'nullable|date',
            'description' => 'nullable|string',
        ];
    }

    public function toDTO(): CompleteAppointmentDTO
    {
        return new CompleteAppointmentDTO(
            executed_datetime: $this->date('executed_datetime')?->toImmutable(),
            description: $this->input('description'),
        );
    }
}
