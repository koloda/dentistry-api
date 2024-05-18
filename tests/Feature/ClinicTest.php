<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_clinic_show(): void
    {
        /** @var \App\Models\User $user */
        $user = UserFactory::new()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/clinic');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'address',
            'phone',
            'email',
            'logo',
            'website',
            'description',
            'status',
        ]);
        $response->assertJson([
            'id' => $user->clinic_id,
            'name' => $user->clinic->name,
            'address' => $user->clinic->address,
            'phone' => $user->clinic->phone,
            'email' => $user->clinic->email,
            'logo' => $user->clinic->logo,
            'website' => $user->clinic->website,
            'description' => $user->clinic->description,
            'status' => $user->clinic->status,
        ]);
    }

    public function test_clinic_show_unauthorized(): void
    {
        $response = $this->getJson('/api/clinic');
        $response->assertStatus(401);
    }
}
