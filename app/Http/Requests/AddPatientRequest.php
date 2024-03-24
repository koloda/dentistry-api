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
        return new AddPatientDTO(
            clinicId: $this->user()->clinic_id,
            name: $this->input('name'),
            phone: $this->input('phone'),
            address: $this->input('address'),
            dateOfBirth: $this->input('date_of_birth'),
            gender: $this->input('gender'),
            medicalHistory: $this->input('medical_history'),
            allergies: $this->input('allergies'),
        );
    }
}
