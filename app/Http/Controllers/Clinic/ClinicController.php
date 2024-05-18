<?php

namespace App\Http\Controllers\Clinic;

use App\Repository\ClinicRepository;

class ClinicController
{
    public function show(ClinicRepository $clinicRepository): \App\Models\Clinic
    {
        if (! auth()->user()) {
            abort(403);
        }

        return $clinicRepository->getById(auth()->user()->clinic_id);
    }
}
