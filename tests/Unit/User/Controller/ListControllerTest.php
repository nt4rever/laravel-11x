<?php

namespace Tests\Unit\User\Controller;

use App\Http\Controllers\User\ListController;
use App\Http\Requests\Request;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    private ListController $controller;

    private Request|MockInterface $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = app(ListController::class);
        $this->request = $this->mock(Request::class);
    }

    public function test_should_return_user_collection()
    {
        User::factory(100)->create();

        $response = $this->controller->__invoke($this->request);

        $this->assertInstanceOf(UserCollection::class, $response);
        $this->assertEquals(15, $response->count());
    }
}
