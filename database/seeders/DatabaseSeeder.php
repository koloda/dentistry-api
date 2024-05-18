<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Appointment::query()->delete();
        Patient::query()->delete();
        User::query()->delete();
        Clinic::query()->delete();

        $clinics = Clinic::factory(1)->create();

        User::factory(1)->create([
            // @phpstan-ignore-next-line
            'clinic_id' => $clinics->first()->id,
            'phone' => '1234567890',
        ]);

        foreach ($clinics as $clinic) {
            // create doctors for each clinic
            User::factory(10)->create([
                'clinic_id' => $clinic->id,
            ]);

            //create patients for each clinic
            Patient::factory(100)->create([
                'clinic_id' => $clinic->id,
            ]);

            // create appointments for each doctor
            $clinic->users->each(function (User $doctor) use ($clinic) {
                $patients = $clinic->patients;

                $patients = $patients->random(random_int(1, 100));
                \App\Models\Appointment::factory(100)->create([
                    // @phpstan-ignore-next-line
                    'patient_id' => $patients->first()->id,
                    'clinic_id' => $clinic->id,
                    'doctor_id' => $doctor->id,
                ]);
            });
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
