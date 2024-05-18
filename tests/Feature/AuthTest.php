<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @covers \App\Http\Controllers\Auth\LoginController::sendSms
     * @covers \App\Http\Controllers\Auth\LoginController::verifySms
     */
    public function test_sms_login(): void
    {
        /** @var \App\Models\User $user */
        $user = UserFactory::new()->create();
        $response = $this->postJson('/api/login/sms', ['phone' => $user->phone]);
        $response->assertStatus(200);

        $user->refresh();
        $smsCode = $user->tmp_sms_code;
        $this->assertDatabaseHas('users', ['id' => $user->id, 'tmp_sms_code' => $smsCode]);

        $response = $this->postJson('/api/login/sms/verify', ['phone' => $user->phone, 'code' => $smsCode]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_sms_login_with_wrong_number(): void
    {
        /** @var \App\Models\User $user */
        $user = UserFactory::new()->create(['phone' => '123456789']);
        $response = $this->postJson('/api/login/sms', ['phone' => '111111111']);
        $response->assertStatus(422);

        $response = $this->postJson('/api/login/sms', ['phone' => $user->phone]);
        $response->assertStatus(200);
        $user->refresh();
        $smsCode = $user->tmp_sms_code;
        $this->assertDatabaseHas('users', ['id' => $user->id, 'tmp_sms_code' => $smsCode]);
        $response = $this->postJson('/api/login/sms/verify', ['phone' => '111111111', 'code' => $smsCode]);
        $response->assertStatus(422);
    }

    public function test_sms_login_with_wrong_code(): void
    {
        /** @var \App\Models\User $user */
        $user = UserFactory::new()->create();
        $response = $this->postJson('/api/login/sms', ['phone' => $user->phone]);
        $response->assertStatus(200);
        $user->refresh();
        $smsCode = $user->tmp_sms_code;
        $this->assertDatabaseHas('users', ['id' => $user->id, 'tmp_sms_code' => $smsCode]);

        if ($smsCode === '1111') {
            $wrongCode = '2222';
        } else {
            $wrongCode = '1111';
        }
        $response = $this->postJson('/api/login/sms/verify', ['phone' => $user->phone, 'code' => $wrongCode]);
        $response->assertStatus(403);
    }
}
