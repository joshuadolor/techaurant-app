<?php

namespace Tests\Unit\Core\Services;

use App\Core\Interfaces\IResourceRepository;
use App\Core\Services\ResourceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Str;

class ResourceServiceTest extends TestCase
{
    private ResourceService $service;
    private IResourceRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = Mockery::mock(IResourceRepository::class);
        
        // Create a test service that extends the base service
        $this->service = new class($this->repository) extends ResourceService {
            protected function getSearchableFields(): array
            {
                return ['name', 'email'];
            }

            protected function getSortableFields(): array
            {
                return ['name', 'email', 'created_at'];
            }

            protected function getFilterableFields(): array
            {
                return ['name', 'email'];
            }

            protected function processCreateData(array $data): array
            {
                $data['processed'] = true;
                return $data;
            }

            protected function processUpdateData(array $data): array
            {
                $data['updated'] = true;
                return $data;
            }

            protected function transformData(object $data): object
            {
                $data->transformed = true;
                return $data;
            }
        };
    }

    /** @test */
    public function it_can_get_paginated_records()
    {
        $expectedData = [
            'data' => [
                (object)['id' => 1, 'name' => 'Test 1'],
                (object)['id' => 2, 'name' => 'Test 2'],
            ],
            'meta' => [
                'current_page' => 1,
                'total' => 2
            ]
        ];

        $this->repository->shouldReceive('getPaginated')
            ->once()
            ->with([], 15)
            ->andReturn($expectedData);

        $result = $this->service->getPaginated([]);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_find_by_uuid()
    {
        $uuid = Str::uuid();
        $expectedData = (object)['id' => 1, 'name' => 'Test User'];

        $this->repository->shouldReceive('findByUuid')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($expectedData);

        $result = $this->service->find($uuid);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_find_or_fail()
    {
        $uuid = Str::uuid();
        $expectedData = (object)['id' => 1, 'name' => 'Test User'];

        $this->repository->shouldReceive('findByUuid')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($expectedData);

        $result = $this->service->findOrFail($uuid);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_throws_exception_when_find_or_fail_not_found()
    {
        $uuid = Str::uuid();

        $this->repository->shouldReceive('findByUuid')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->service->findOrFail($uuid);
    }

    /** @test */
    public function it_can_create_record()
    {
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $processedData = ['name' => 'Test User', 'email' => 'test@example.com', 'processed' => true];
        $rawResult = (object)['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com', 'processed' => true];
        $expectedData = (object)['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com', 'processed' => true, 'transformed' => true];

        $this->repository->shouldReceive('create')
            ->once()
            ->with($processedData)
            ->andReturn($rawResult);

        $result = $this->service->create($data);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_update_record()
    {
        $uuid = Str::uuid();
        $data = ['name' => 'Updated User'];
        $processedData = ['name' => 'Updated User', 'updated' => true];
        $rawResult = (object)['id' => 1, 'name' => 'Updated User', 'updated' => true];
        $expectedData = (object)['id' => 1, 'name' => 'Updated User', 'updated' => true, 'transformed' => true];

        $this->repository->shouldReceive('update')
            ->once()
            ->with(Mockery::type('string'), $processedData)
            ->andReturn($rawResult);

        $result = $this->service->update($uuid, $data);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_delete_record()
    {
        $uuid = Str::uuid();

        $this->repository->shouldReceive('delete')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(true);

        $result = $this->service->delete($uuid);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_find_by_criteria()
    {
        $criteria = ['name' => 'Test User'];
        $expectedData = [(object)['id' => 1, 'name' => 'Test User']];

        $this->repository->shouldReceive('findByCriteria')
            ->once()
            ->with($criteria)
            ->andReturn($expectedData);

        $result = $this->service->findByCriteria($criteria);

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_check_if_record_exists()
    {
        $uuid = Str::uuid();

        $this->repository->shouldReceive('exists')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(true);

        $result = $this->service->exists($uuid);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_all_records()
    {
        $expectedData = [
            (object)['id' => 1, 'name' => 'Test 1'],
            (object)['id' => 2, 'name' => 'Test 2']
        ];

        $this->repository->shouldReceive('all')
            ->once()
            ->andReturn($expectedData);

        $result = $this->service->all();

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_count_records()
    {
        $this->repository->shouldReceive('count')
            ->once()
            ->andReturn(2);

        $result = $this->service->count();

        $this->assertEquals(2, $result);
    }

    /** @test */
    public function it_can_get_first_record()
    {
        $expectedData = (object)['id' => 1, 'name' => 'Test 1'];

        $this->repository->shouldReceive('first')
            ->once()
            ->andReturn($expectedData);

        $result = $this->service->first();

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_can_get_last_record()
    {
        $expectedData = (object)['id' => 2, 'name' => 'Test 2'];

        $this->repository->shouldReceive('last')
            ->once()
            ->andReturn($expectedData);

        $result = $this->service->last();

        $this->assertEquals($expectedData, $result);
    }

    /** @test */
    public function it_validates_search_parameters()
    {
        $params = [
            'search' => 'test',
            'sort_by' => 'invalid_field',
            'filters' => [
                'name' => 'test',
                'invalid_field' => 'value'
            ]
        ];

        $validatedParams = [
            'search' => 'test',
            'filters' => [
                'name' => 'test'
            ]
        ];

        $expectedResult = [
            'data' => [],
            'meta' => ['total' => 0]
        ];

        $this->repository->shouldReceive('getPaginated')
            ->once()
            ->with($validatedParams, 15)
            ->andReturn($expectedResult);

        $result = $this->service->getPaginated($params);

        $this->assertEquals($expectedResult, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
