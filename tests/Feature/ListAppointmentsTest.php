<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListAppointmentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_list_all_appointments()
    {
        $clinic = \App\Models\Clinic::factory()->create();
        $doctor = \App\Models\User::factory()->create([
            'clinic_id' => $clinic->id,
        ]);
        $patient = \App\Models\User::factory()->create([
            'clinic_id' => $clinic->id,
        ]);
        \App\Models\Appointment::factory()->count(30)->create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);

        // second clinic
        $clinic2 = \App\Models\Clinic::factory()->create();
        $doctor2 = \App\Models\User::factory()->create([
            'clinic_id' => $clinic2->id,
        ]);
        $patient2 = \App\Models\User::factory()->create([
            'clinic_id' => $clinic2->id,
        ]);
        \App\Models\Appointment::factory()->count(30)->create([
            'clinic_id' => $clinic2->id,
            'doctor_id' => $doctor2->id,
            'patient_id' => $patient2->id,
        ]);

        $this->actingAs($doctor)
            ->getJson('/api/appointments')
            ->assertOk()
            ->assertJsonCount(30);

        $this->actingAs($doctor2)
            ->getJson('/api/appointments')
            ->assertOk()
            ->assertJsonCount(30);
    }
}
