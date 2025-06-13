<?php

namespace Tests\Unit\Core\Controllers;

use App\Core\Controllers\ResourceController;
use App\Core\Interfaces\IResourceService;
use App\Core\Services\RequestValidatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    private ResourceController $controller;
    private IResourceService $service;
    private RequestValidatorService $validator;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = Mockery::mock(IResourceService::class);
        $this->validator = new RequestValidatorService();
        
        $validationRules = [
            'store' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email'
            ],
            'update' => [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email'
            ]
        ];
        
        $this->controller = new class($this->service, $this->validator, $validationRules, 'Test Resource') extends ResourceController {
            protected function getResourceName(): string
            {
                return 'test-resource';
            }
        };
    }

    /** @test */
    public function it_can_list_resources()
    {
        $request = new Request();
        $expectedData = [
            'data' => [
                ['id' => 1, 'name' => 'Test 1'],
                ['id' => 2, 'name' => 'Test 2'],
            ],
            'meta' => [
                'current_page' => 1,
                'total' => 2
            ]
        ];

        $this->service->shouldReceive('getPaginated')
            ->once()
            ->with([])
            ->andReturn($expectedData);

        $response = $this->controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertEquals($expectedData, $responseData['data']);
    }

    /** @test */
    public function it_can_create_a_resource()
    {
        $request = new Request();
        $request->merge([
            'name' => 'Test Resource',
            'email' => 'test@example.com'
        ]);

        $expectedData = (object)[
            'id' => 1,
            'name' => 'Test Resource',
            'email' => 'test@example.com'
        ];

        $this->service->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Test Resource',
                'email' => 'test@example.com'
            ])
            ->andReturn($expectedData);

        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Test Resource created successfully', $responseData['message']);
        $this->assertEquals((array)$expectedData, $responseData['data']);
    }

    /** @test */
    public function it_validates_input_before_creating()
    {
        $request = new Request();
        $request->merge([
            'email' => 'invalid-email'
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->store($request);
    }

    /** @test */
    public function it_can_show_a_resource()
    {
        $uuid = 'test-uuid';
        $expectedData = (object)[
            'id' => 1,
            'name' => 'Test Resource',
            'email' => 'test@example.com'
        ];

        $this->service->shouldReceive('find')
            ->once()
            ->with($uuid)
            ->andReturn($expectedData);

        $response = $this->controller->show($uuid);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertEquals((array)$expectedData, $responseData['data']);
    }

    /** @test */
    public function it_can_update_a_resource()
    {
        $uuid = 'test-uuid';
        $request = new Request();
        $request->merge([
            'name' => 'Updated Resource'
        ]);

        $expectedData = (object)[
            'id' => 1,
            'name' => 'Updated Resource',
            'email' => 'test@example.com'
        ];

        $this->service->shouldReceive('update')
            ->once()
            ->with($uuid, ['name' => 'Updated Resource'])
            ->andReturn($expectedData);

        $response = $this->controller->update($request, $uuid);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Test Resource updated successfully', $responseData['message']);
        $this->assertEquals((array)$expectedData, $responseData['data']);
    }

    /** @test */
    public function it_validates_input_before_updating()
    {
        $uuid = 'test-uuid';
        $request = new Request();
        $request->merge([
            'email' => 'invalid-email'
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->update($request, $uuid);
    }

    /** @test */
    public function it_can_delete_a_resource()
    {
        $uuid = 'test-uuid';

        $this->service->shouldReceive('delete')
            ->once()
            ->with($uuid)
            ->andReturn(true);

        $response = $this->controller->destroy($uuid);

        $this->assertEquals(204, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Test Resource deleted successfully', $responseData['message']);
        $this->assertNull($responseData['data']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
