<?php

namespace Tests\Unit\User\Controller;

use App\Http\Controllers\User\ListController;
use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    private ListController $controller;

    private IndexRequest|MockInterface $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = app(ListController::class);
    }

    public function test_should_return_user_collection()
    {
        User::factory(100)->create();
        $this->request = $this->mock(IndexRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('buildQueryBuilder')->once()->andReturn(User::query());
            $mock->shouldReceive('getLimit')->once()->andReturn(30);
        });

        $response = $this->controller->__invoke($this->request);

        $this->assertInstanceOf(UserCollection::class, $response);
        $this->assertEquals(30, $response->count());
    }
}
