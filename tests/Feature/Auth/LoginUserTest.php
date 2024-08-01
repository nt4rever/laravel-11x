<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_login(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ], [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    #[DataProvider('dataSetToFailResponse')]
    public function test_fail_response($data, $expected): void
    {
        $response = $this->post('/api/auth/login', $data, [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $response->assertJson($expected);
    }

    public static function dataSetToFailResponse(): array
    {
        return [
            'empty request' => [
                'data' => [],
                'expected' => [
                    'message' => 'The email field is required. (and 1 more error)',
                    'errors' => [
                        'email' => [
                            'The email field is required.',
                        ],
                        'password' => [
                            'The password field is required.',
                        ],
                    ],
                ],
            ],
            'with email and no password' => [
                'data' => [
                    'email' => 'test@mail.com',
                ],
                'expected' => [
                    'message' => 'The password field is required.',
                    'errors' => [
                        'password' => [
                            'The password field is required.',
                        ],
                    ],
                ],

            ],
            'with password and no email' => [
                'data' => [
                    'password' => 'password',
                ],
                'expected' => [
                    'message' => 'The email field is required.',
                    'errors' => [
                        'email' => [
                            'The email field is required.',
                        ],
                    ],
                ],

            ],
        ];
    }
}
