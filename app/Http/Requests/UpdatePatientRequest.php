<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Patient\StorePatientDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class UpdatePatientRequest extends AddPatientRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'phone' => 'required|string|unique:patients,phone,' . $this->route('patient')->id,
        ]);
    }
}
