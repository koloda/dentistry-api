<?php

namespace Tests\Feature;

use Tests\TestCase;

class ListPatientsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list_patients(): void
    {
        $user = $this->createUser();
        \App\Models\Patient::factory()->count(3)->create(['clinic_id' => $user->clinic_id]);
        \App\Models\Patient::factory()->count(3)->create();

        $response = $this->authorizedRequest('getJson', '/api/patients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'phone',
                'address',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertJsonCount(3);
    }
}
