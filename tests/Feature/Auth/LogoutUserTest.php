<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedUser;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    public function test_success_logout(): void
    {
        $this->withoutExceptionHandling();
        $this->setUpUser();

        $response = $this->get('/api/auth/logout', [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(204);
    }
}
