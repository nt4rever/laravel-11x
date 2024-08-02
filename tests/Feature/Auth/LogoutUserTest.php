<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedUser;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    use AuthenticatedUser, RefreshDatabase;

    public function test_success_logout(): void
    {
        $this->setUpUser();

        $response = $this->get('/api/auth/logout', [
            'accept' => 'application/json',
            'X-Locale' => 'vi',
        ]);

        $response->assertStatus(204);
    }

    public function test_fail_logout(): void
    {
        $response = $this->get('/api/auth/logout', [
            'accept' => 'application/json',
            'authentication' => 'Bearer 1|nmpmdsHHSaePHnOD5K3nA3rzlaLayX7cN5h5XbHKf65c6364',
        ]);

        $response->assertStatus(401);
    }
}
