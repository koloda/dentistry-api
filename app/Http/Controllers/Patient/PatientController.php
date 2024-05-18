<?php

namespace App\Http\Controllers\Patient;

use App\Domain\Patient\AddPatientAction;
use App\Http\Requests\AddPatientRequest;
use App\Models\Patient;
use App\Repository\PatientRepository;

class PatientController extends \App\Http\Controllers\AuthorisedController
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Patient::class);
    }

    public function add(AddPatientRequest $request, AddPatientAction $addPatientAction): \App\Models\Patient
    {
        return $addPatientAction->execute($request->toDTO());
    }

    public function show(Patient $patient, PatientRepository $patientRepository): \App\Models\Patient
    {
        return $patient;
    }

    // @phpstan-ignore-next-line
    public function list(PatientRepository $patientRepository): \Illuminate\Database\Eloquent\Collection
    {
        return $patientRepository->list();
    }
}
