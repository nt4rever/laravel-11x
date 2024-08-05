<?php

namespace Tests\Unit\Infrastructure;

use App\Http\Resources\Json\ResourceCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ResourceCollectionTest extends TestCase
{
    use RefreshDatabase;

    private $resource;

    private Request|MockInterface $request;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory(20)->create();

        $this->resource = User::paginate(15);

        $this->request = $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->andReturn([['page' => '1']]);
        });

        $this->request = $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->andReturn([['page' => '1']]);
        });
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function test_resource_should_preserve_query_parameters(): void
    {

        $resource = new ResourceCollection($this->resource);

        $resource->preserveQuery();

        $response = $resource->toResponse($this->request);

        $this->assertNotEmpty($response->getContent());
    }

    public function test_resource_should_have_query_parameters(): void
    {

        $resource = new ResourceCollection($this->resource);

        $resource->withQuery([
            'page' => '1',
        ]);

        $response = $resource->toResponse($this->request);

        $this->assertNotEmpty($response->getContent());
    }

    public function test_resource_should_trigger_pagination_information_method(): void
    {
        ResourceCollection::macro('paginationInformation', function ($request, $paginated, $default) {
            return $default;
        });

        $resource = new ResourceCollection($this->resource);

        $response = $resource->toResponse($this->request);

        $this->assertNotEmpty($response->getContent());
    }
}
