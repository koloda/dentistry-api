<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\PatientFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddPatientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_add_patient(): void
    {
        /** @var User $user */
        $user = UserFactory::new()->create();
        $token = $user->createToken('test')->plainTextToken;
        $patient_fields = PatientFactory::new()->make(['clinic_id' => $user->clinic_id])->toArray();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/patients', $patient_fields);

        $response->assertStatus(201);
        $this->assertDatabaseHas('patients', ['phone' => $patient_fields['phone'], 'clinic_id' => $user->clinic_id]);
        $response->assertJsonStructure([
            'id',
            'name',
            'phone',
            'address',
            'created_at',
            'updated_at',
        ]);

        $response->assertJson($patient_fields);
        $this->assertDatabaseCount('patients', 1);

        // check err for same email and phone
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->post('/api/patients', $patient_fields);
        $response->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_add_patient_without_auth(): void
    {
        $patient_fields = PatientFactory::new()->make()->toArray();

        $response = $this->postJson('/api/patients', $patient_fields);

        $response->assertStatus(401);
    }
}
