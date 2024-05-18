<?php

namespace Tests\Feature;

use App\Domain\Appointment\AppointmentException;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddAppointmentTest extends TestCase
{
    use InteractsWithExceptionHandling;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_add_appointment(): void
    {
        $user = $this->createUser();
        $patient = \App\Models\Patient::factory()->create(['clinic_id' => $user->clinic_id]);
        $doctor = \App\Models\User::factory()->create(['clinic_id' => $user->clinic_id]);
        $appointment_fields = \App\Models\Appointment::factory()->make([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => $user->clinic_id,
            'planned_datetime' => now()->addDay()->format('Y-m-d H:i:s'),
            'planned_duration' => 60,
        ])->toArray();

        $response = $this->authorizedRequest('postJson', '/api/appointments', $appointment_fields);

        $response->assertStatus(201);
        $this->assertDatabaseHas('appointments', $appointment_fields);
        $response->assertJsonStructure([
            'id',
            'patient_id',
            'clinic_id',
            'doctor_id',
            'planned_datetime',
            'description',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_add_appointment_without_auth(): void
    {
        $appointment_fields = \App\Models\Appointment::factory()->make()->toArray();

        $response = $this->postJson('/api/appointments', $appointment_fields);

        $response->assertStatus(401);
    }

    public function test_add_appointment_to_patient_from_another_clinic(): void
    {
        $user = $this->createUser();
        $patient = \App\Models\Patient::factory()->create();
        $appointment_fields = \App\Models\Appointment::factory()->make([
            'patient_id' => $patient->id,
            'doctor_id' => $user->id,
            'clinic_id' => $user->clinic_id,
        ])->toArray();

        $response = $this->authorizedRequest('postJson', '/api/appointments', $appointment_fields);

        $response->assertStatus(404);
    }

    public function test_add_appointment_to_doctor_from_another_clinic(): void
    {
        $user = $this->createUser();
        $patient = \App\Models\Patient::factory()->create(['clinic_id' => $user->clinic_id]);
        $doctor = \App\Models\User::factory()->create();
        $appointment_fields = \App\Models\Appointment::factory()->make([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => $user->clinic_id,
        ])->toArray();

        $response = $this->authorizedRequest('postJson', '/api/appointments', $appointment_fields);

        $response->assertStatus(404);
    }

    public function test_add_appointment_for_reserved_time(): void
    {
        $user = $this->createUser();
        $patient = \App\Models\Patient::factory()->create(['clinic_id' => $user->clinic_id]);
        $doctor = \App\Models\User::factory()->create(['clinic_id' => $user->clinic_id]);
        $appointment_fields = \App\Models\Appointment::factory()->make([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => $user->clinic_id,
            'planned_datetime' => now()->addDay()->format('Y-m-d H:i:s'),
            'planned_duration' => 60,
        ])->toArray();

        $this->authorizedRequest('postJson', '/api/appointments', $appointment_fields);

        $this->expectException(AppointmentException::class);
        $this->authorizedRequest('postJson', '/api/appointments', $appointment_fields);

        $this->assertDatabaseCount('appointments', 1);
    }
}
