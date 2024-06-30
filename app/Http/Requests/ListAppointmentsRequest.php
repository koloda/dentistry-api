<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Appointment\ListAppointmentDTO;
use Illuminate\Foundation\Http\FormRequest;

class ListAppointmentsRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'fromDate'  => 'date|nullable',
            'toDate'    => 'date|nullable',
        ];
    }

    public function toDTO(): ListAppointmentDTO
    {
        $startOfWeek = now()->startOfWeek()->toImmutable();
        $endOfWeek = now()->endOfWeek()->toImmutable();

        return new ListAppointmentDTO(
            fromDate: $this->date('fromDate')?->toImmutable() ?? $startOfWeek,
            toDate: $this->date('toDate')?->toImmutable() ?? $endOfWeek,
        );
    }
}
