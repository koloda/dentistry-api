<?php

namespace App\Http\Controllers\Patient;

use App\Domain\Patient\AddPatientAction;
use App\Http\Requests\AddPatientRequest;
use App\Repository\PatientRepository;

class PatientController
{
    public function add(AddPatientRequest $request, AddPatientAction $addPatientAction)
    {
        return $addPatientAction->execute($request->toDTO());
    }

    public function show(int $id, PatientRepository $patientRepository)
    {
        return $patientRepository->getById($id);
    }

    public function list(PatientRepository $patientRepository)
    {
        return $patientRepository->list();
    }
}
