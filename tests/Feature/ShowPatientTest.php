<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShowPatientTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @covers \App\Http\Controllers\Patient\PatientController::show
     */
    public function test_show_patient(): void
    {
        $user = \App\Models\User::factory()->create();
        $patient = \App\Models\Patient::factory()->create(['clinic_id' => $user->clinic_id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(
            ['Authorization' => 'Bearer '.$token]
        )->getJson('/api/patients/'.$patient->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'phone',
            'address',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_show_patient_without_auth(): void
    {
        $patient = \App\Models\Patient::factory()->create();

        $response = $this->getJson('/api/patients/'.$patient->id);

        $response->assertStatus(401);
    }

    public function test_show_patient_from_another_clinic(): void
    {
        $user = \App\Models\User::factory()->create();
        $patient = \App\Models\Patient::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(
            ['Authorization' => 'Bearer '.$token]
        )->getJson('/api/patients/'.$patient->id);

        $response->assertStatus(403);
    }

    public function test_show_patient_not_found(): void
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(
            ['Authorization' => 'Bearer '.$token]
        )->getJson('/api/patients/1');

        $response->assertStatus(404);
    }
}
