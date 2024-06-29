<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Patient\StorePatientDTO;
use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class UpdatePatientRequest extends AddPatientRequest
{
    public function rules(): array
    {
        /** @var Patient $patient */
        $patient = $this->route('patient');

        return array_merge(parent::rules(), [
            'phone' => 'required|string|unique:patients,phone,' . $patient->id,
        ]);
    }
}
