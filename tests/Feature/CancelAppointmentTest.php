<?php

namespace Tests\Feature;

use App\Domain\Appointment\AppointmentException;
use App\Domain\Appointment\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelAppointmentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_cancel_appointment()
    {
        $doctor = $this->createUser();
        $this->withoutExceptionHandling();
        $appointment = Appointment::factory()->create([
            'status' => AppointmentStatus::Scheduled->value,
            'doctor_id' => $doctor->id,
            'clinic_id' => $doctor->clinic_id,
        ]);

        $this->authorizedRequest('postJson', '/api/appointments/' . $appointment->id . '/cancel')
            ->assertOk();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => AppointmentStatus::Cancelled,
        ]);
    }

    public function test_cannot_cancel_appointment_if_not_scheduled()
    {
        $doctor = $this->createUser();
        $appointment = Appointment::factory()->create([
            'status' => AppointmentStatus::Cancelled,
            'doctor_id' => $doctor->id,
            'clinic_id' => $doctor->clinic_id,
        ]);
        $this->expectException(AppointmentException::class);
        $response = $this->authorizedRequest('postJson', '/api/appointments/' . $appointment->id . '/cancel');
            $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Appointment can be cancelled only if it is scheduled',
        ]);

        $appointment->status = AppointmentStatus::Executed;
        $appointment->save();

        $response = $this->authorizedRequest('postJson', '/api/appointments/' . $appointment->id . '/cancel');
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Appointment can be cancelled only if it is scheduled',
        ]);
    }
}
