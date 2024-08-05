<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
            'remember_me' => 'true',
        ], [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function test_fail_login(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => '123456',
        ], [
            'accept' => 'application/json',
        ]);

        $response->assertStatus(401);
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

    public function test_login_reach_rate_limit(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        \RateLimiter::increment(
            Str::transliterate(Str::lower('test@example.com|192.168.1.100')),
            config('throttle.login.retry', 5 * 60),
            config('throttle.login.max_attempt', 5)
        );

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->post('/api/auth/login', [
                'email' => 'test@example.com',
                'password' => 'password',
            ], [
                'accept' => 'application/json',
                'X-Locale' => 'en',
            ]);

        $response->assertStatus(429);
        $seconds = config('throttle.login.retry', 5 * 60);
        $response->assertJsonPath('message', "Too many login attempts. Please try again in $seconds seconds.");
    }
}
