<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedUser;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use AuthenticatedUser, RefreshDatabase;

    public function test_success_get_me(): void
    {
        $this->setUpUser([
            'name' => 'Peter',
            'email' => 'peter@mail.com',
        ]);

        $response = $this->get('/api/users/me', [
            'accept' => 'application/json',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Peter',
                    'email' => 'peter@mail.com',
                ],
            ]);
    }

    public function test_fail_authentication_get_me(): void
    {
        $response = $this->get('/api/users/me', [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }

    public function test_success_get_list_user(): void
    {
        $this->setUpUser();
        User::factory(20)->create();

        $response = $this->get('/api/users', [
            'accept' => 'application/json',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonCount(20, 'data');
    }

    public function test_success_get_list_user_with_parameters(): void
    {
        $this->setUpUser();
        User::factory(20)->create();

        $response = $this->get('/api/users?page=2&limit=6', [
            'accept' => 'application/json',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    public function test_success_get_list_user_with_search_keyword(): void
    {
        $this->setUpUser();
        User::factory(20)->create();

        $response = $this->get('/api/users?search=a', [
            'accept' => 'application/json',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
}
