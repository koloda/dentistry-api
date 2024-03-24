<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clinic>
 */
class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->e164PhoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->domainName(),
            'logo' => fake()->imageUrl(),
            'description' => fake()->words(4, true),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
