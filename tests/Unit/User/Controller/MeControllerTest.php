<?php

namespace Tests\Unit\User\Controller;

use App\Http\Controllers\User\MeController;
use App\Http\Requests\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    use RefreshDatabase;

    private MeController $controller;
    private Request|MockInterface $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = app(MeController::class);
    }

    public function test_should_return_user_resource()
    {
        $this->request = $this->mock(Request::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('user')
                ->once()
                ->andReturn(User::factory()->create());
        });

        $response = $this->controller->__invoke($this->request);

        $this->assertInstanceOf(UserResource::class, $response);
    }
}
