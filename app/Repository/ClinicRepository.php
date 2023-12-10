<?php

namespace App\Repository;

use App\Models\Clinic;

class ClinicRepository
{
    public function getById(int $id): Clinic
    {
        return Clinic::query()->findOrFail($id);
    }
}
