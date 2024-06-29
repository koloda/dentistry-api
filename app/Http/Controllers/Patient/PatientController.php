<?php

namespace App\Http\Controllers\Patient;

use App\Domain\Patient\AddPatientAction;
use App\Domain\Patient\UpdatePatientAction;
use App\Http\Controllers\AuthorisedController;
use App\Http\Requests\AddPatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Repository\PatientRepository;
use Illuminate\Database\Eloquent\Collection;

class PatientController extends AuthorisedController
{
    public function __construct()
    {
        $this->authorizeResource(Patient::class);
    }

    public function add(AddPatientRequest $request, AddPatientAction $addPatientAction): Patient
    {
        return $addPatientAction->execute($request->toDTO());
    }

    public function show(Patient $patient, PatientRepository $patientRepository): Patient
    {
        return $patient;
    }

    // @phpstan-ignore-next-line
    public function list(PatientRepository $patientRepository): Collection
    {
        return $patientRepository->list()->transform(function (Patient $patient) {
            $attributes = $patient->getAttributes();
            $attributes['age'] = $patient->getAge();

            return $attributes;
       });
    }

    public function update(
        Patient $patient,
        UpdatePatientRequest $request,
        UpdatePatientAction $updatePatientAction): Patient
    {
        return $updatePatientAction->execute($patient, $request->toDTO());
    }
}
