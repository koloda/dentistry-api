<?php

namespace Tests\Feature;

use App\Domain\Appointment\AppointmentException;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoveAppointmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_move_appointment_to_another_date()
    {
        $doctor = $this->createUser();
        $planedDatetime = now()->addDay()->setHour(10)->setMinutes(0)->setSeconds(0);
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'planned_datetime' => $planedDatetime,
            'planned_duration' => 30,
            'clinic_id' => $doctor->clinic->id,
        ]);

        $this->actingAs($doctor)
            ->post(route('appointments.move', $appointment->id), [
                'planned_datetime' => $planedDatetime->addHour()->format('Y-m-d H:i'),
            ])
            ->assertOk();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'planned_datetime' => $planedDatetime->format('Y-m-d H:i:s'),
        ]);

        //move and change duration
        $this->actingAs($doctor)
            ->post(route('appointments.move', $appointment->id), [
                'planned_datetime' => $planedDatetime->setHour(12)->format('Y-m-d H:i'),
                'planned_duration' => 60,
            ])
            ->assertOk();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'planned_datetime' => $planedDatetime->format('Y-m-d H:i:s'),
            'planned_duration' => 60,
        ]);

        //move and change duration to 0
        $this->actingAs($doctor)
            ->postJson(route('appointments.move', $appointment->id), [
                'planned_datetime' => $planedDatetime->setHour(13)->setMinutes(0),
                'planned_duration' => 0,
            ])->assertStatus(422);
    }

    public function test_cannot_move_appointment_to_past()
    {
        $doctor = $this->createUser();
        $planedDatetime = now()->addDay()->setHour(10)->setMinutes(0)->setSeconds(0);
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'planned_datetime' => $planedDatetime,
            'planned_duration' => 30,
            'clinic_id' => $doctor->clinic->id,
        ]);

        $this->expectException(AppointmentException::class);

        $this->actingAs($doctor)
            ->postJson(route('appointments.move', $appointment->id), [
                'planned_datetime' => $planedDatetime->subDays(2)->format('Y-m-d H:i'),
            ])
            ->assertStatus(400);
    }

    public function test_cannot_move_appointment_to_busy_time()
    {
        $doctor = $this->createUser();
        $planedDatetime = now()->addDay()->setHour(10)->setMinutes(0)->setSeconds(0);
        $firstAppointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'planned_datetime' => $planedDatetime,
            'planned_duration' => 30,
            'clinic_id' => $doctor->clinic->id,
        ]);
        $secondAppointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'planned_datetime' => $planedDatetime->setHour(11),
            'planned_duration' => 30,
            'clinic_id' => $doctor->clinic->id,
        ]);

        $this->expectException(AppointmentException::class);

        $this->actingAs($doctor)
            ->postJson(route('appointments.move', $firstAppointment->id), [
                'planned_datetime' => $planedDatetime->setHour(11)->format('Y-m-d H:i'),
            ])
            ->assertStatus(400);
    }
}
