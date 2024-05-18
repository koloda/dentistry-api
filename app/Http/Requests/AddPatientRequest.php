<?php

namespace App\Http\Requests;

use App\Domain\Patient\AddPatientDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class AddPatientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string|unique:patients,phone',
            'address' => 'string|nullable',
            'gender' => ['required', new In(['male', 'female'])],
            'date_of_birth' => 'required|date',
            'medical_history' => 'string|nullable',
            'allergies' => 'string|nullable',
        ];
    }

    public function toDTO(): AddPatientDTO
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        /** @var \Carbon\Carbon $date */
        $date = $this->date('date_of_birth');

        return new AddPatientDTO(
            clinicId: $user->clinic_id,
            name: $this->string('name'),
            phone: $this->string('phone'),
            address: $this->string('address'),
            dateOfBirth: $date->toImmutable(),
            gender: $this->string('gender'),
            medicalHistory: $this->string('medical_history'),
            allergies: $this->string('allergies'),
        );
    }
}
