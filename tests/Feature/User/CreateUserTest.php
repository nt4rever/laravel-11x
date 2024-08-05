<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthenticatedUser;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use AuthenticatedUser, RefreshDatabase, WithFaker;

    public function test_success_create(): void
    {
        $this->setUpUser([
            'name' => 'Peter',
            'email' => 'peter@mail.com',
        ]);

        $response = $this->post('/api/users', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ], [
            'accept' => 'application/json',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'email',
                    'name',
                ],
            ]);
    }

    public function test_fail_duplicate_email(): void
    {
        $this->setUpUser([
            'name' => 'Peter',
            'email' => 'peter@mail.com',
        ]);

        $response = $this->post('/api/users', [
            'name' => $this->faker->name,
            'email' => 'peter@mail.com',
            'password' => $this->faker->password,
        ], [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(400);
    }
}
