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
}
