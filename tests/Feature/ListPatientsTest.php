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
        $doctor = $this->createUser();
        \App\Models\Patient::factory()->count(3)->create(['clinic_id' => $doctor->clinic_id]);
        \App\Models\Patient::factory()->count(3)->create();

        $response = $this->actingAs($doctor)->get('/api/patients');

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
