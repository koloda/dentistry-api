<?php

namespace Database\Factories;

use App\Domain\Appointment\AppointmentStatus;
use App\Models\Clinic;
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
        return [
            'patient_id' => Patient::factory()->create(),
            'clinic_id' => Clinic::factory()->create(),
            'doctor_id' => User::factory()->create(),
            'planned_datetime' => $this->faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d H:i:s'),
            'planned_duration' => $this->faker->numberBetween(10, 120),
            'description' => $this->faker->text(100),
            'status' => AppointmentStatus::Scheduled,
        ];
    }
}
