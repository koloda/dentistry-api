<?php

namespace Tests\Feature;

use App\Domain\Appointment\AppointmentException;
use App\Domain\Appointment\AppointmentStatus;
use App\Models\Appointment;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\Appointment\AppointmentController::complete
 */
class CompleteAppointmentTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\Appointment\AppointmentController::complete
     *
     * @dataProvider dataProvider
     */
    public function test_complete_appointment(
        ?Carbon $executed_datetime,
        ?string $description
    ): void {
        $doctor = $this->createUser();
        $appointment = Appointment::factory()->create([
            'status' => AppointmentStatus::Scheduled->value,
            'doctor_id' => $doctor->id,
            'clinic_id' => $doctor->clinic_id,
        ]);

        $payload = [];
        if ($executed_datetime) {
            $payload['executed_datetime'] = $executed_datetime;
        }
        if ($description) {
            $payload['description'] = $description;
        }

        $this->authorizedRequest(
            'postJson',
            '/api/appointments/'.$appointment->id.'/complete',
            $payload,
        )->assertOk();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => AppointmentStatus::Executed,
            'executed_datetime' => $executed_datetime ?: now(),
            'description' => $description ?: $appointment->description,
        ]);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            [
                'executed_datetime' => null,
                'description' => null,
            ],
            [
                'executed_datetime' => null,
                'description' => 'Some description',
            ],
            [
                'executed_datetime' => now(),
                'description' => null,
            ],
            [
                'executed_datetime' => now(),
                'description' => 'Some description',
            ],
        ];
    }

    /**
     * @covers \App\Http\Controllers\Appointment\AppointmentController::complete
     *
     * @dataProvider errorsDataProvider
     */
    public function test_complete_appointment_exceptions(
        AppointmentStatus $status,
        int $expectedCode,
        ?bool $otherClinic = false,
    ): void {
        $doctor = $this->createUser();

        if ($otherClinic) {
            $secondDoctor = \App\Models\User::factory()->create();
            $appointment = Appointment::factory()->create([
                'status' => $status->value,
                'doctor_id' => $secondDoctor->id,
                'clinic_id' => $secondDoctor->clinic_id,
            ]);
        } else {
            $appointment = Appointment::factory()->create([
                'status' => $status->value,
                'doctor_id' => $doctor->id,
                'clinic_id' => $doctor->clinic_id,
            ]);
        }

        if (! $otherClinic) {
            $this->expectException(AppointmentException::class);
        }

        $this->authorizedRequest(
            'postJson',
            '/api/appointments/'.$appointment->id.'/complete',
        )->assertStatus($expectedCode);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public static function errorsDataProvider(): array
    {
        return [
            [
                'status' => AppointmentStatus::Executed,
                'expected_code' => 400,
            ],
            [
                'status' => AppointmentStatus::Cancelled,
                'expected_code' => 400,
            ],
            [
                'status' => AppointmentStatus::Scheduled,
                'expected_code' => 403,
                'otherClinic' => true,
            ],
        ];
    }
}
