<?php

namespace Database\Factories;

use App\Domain\Appointment\AppointmentStatus;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patient = Patient::factory()->create();
        $clinic = $patient->clinic;
        $doctor = User::factory()->create(['clinic_id' => $clinic->id]);

        return [
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'planned_datetime' => $this->faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d H:i:s'),
            'planned_duration' => $this->faker->numberBetween(10, 120),
            'description' => $this->faker->text(100),
            'status' => AppointmentStatus::Scheduled,
        ];
    }
}
