<?php

namespace App\Domain\Clinic;

use App\Models\Clinic;

class AddClinicAction
{
    public function execute(AddClinicDTO $payload): Clinic
    {
        $clinic = new Clinic();
        $clinic->name = $payload->name;
        $clinic->address = $payload->address;
        $clinic->phone = $payload->phone;
        $clinic->email = $payload->email;
        $clinic->website = $payload->website;
        $clinic->logo = $payload->logo;
        $clinic->description = $payload->description;
        $clinic->status = $payload->status;

        $clinic->save();

        return $clinic;
    }
}
