<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $token = '';

    public function createUser(): User
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->token = $token;

        return $user;
    }

    public function authorizedRequest(string $method, string $url, array $data = []): \Illuminate\Testing\TestResponse
    {
        $methods = ['get', 'post', 'put', 'patch', 'delete', 'options', 'getJson', 'postJson', 'putJson', 'patchJson', 'deleteJson'];
        if (! in_array($method, $methods)) {
            throw new \Exception('Method not allowed');
        }

        if (! $this->token) {
            $this->createUser();
        }

        return $this->withHeaders(
            ['Authorization' => 'Bearer '.$this->token]
        )->$method($url, $data);
    }

    public function createClinic(): \App\Models\Clinic
    {
        return \App\Models\Clinic::factory()->create();
    }
}
