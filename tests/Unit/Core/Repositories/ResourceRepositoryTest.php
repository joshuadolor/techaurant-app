<?php

namespace Tests\Unit\Core\Repositories;

use App\Core\Repositories\ResourceRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Schema;

class ResourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ResourceRepository $repository;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configure SQLite in memory database
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);
        
        // Create test_resources table
        Schema::create('test_resources', function ($table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
        
        // Create a test model that extends the base model
        $this->model = new class extends Model {
            protected $table = 'test_resources';
            protected $fillable = ['name', 'email', 'uuid'];
            protected $casts = [
                'uuid' => 'string',
            ];

            protected static function boot()
            {
                parent::boot();
                
                static::creating(function ($model) {
                    if (empty($model->uuid)) {
                        $model->uuid = (string) Str::uuid();
                    }
                });
            }
        };
        
        // Create a test repository that extends the base repository
        $this->repository = new class($this->model, ['name', 'email']) extends ResourceRepository {
            public function findByUuidOrFail(string $uuid): object
            {
                return $this->model->where('uuid', $uuid)->firstOrFail();
            }
        };
    }

    /** @test */
    public function it_can_find_by_uuid()
    {
        $uuid = (string) Str::uuid();
        $record = $this->model->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'uuid' => $uuid
        ]);

        $result = $this->repository->findByUuid($uuid);

        $this->assertNotNull($result);
        $this->assertEquals($uuid, $result->uuid);
    }

    /** @test */
    public function it_returns_null_when_uuid_not_found()
    {
        $result = $this->repository->findByUuid(Str::uuid());

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_find_by_uuid_or_fail()
    {
        $uuid = (string) Str::uuid();
        $record = $this->model->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'uuid' => $uuid
        ]);

        $result = $this->repository->findByUuidOrFail($uuid);

        $this->assertNotNull($result);
        $this->assertEquals($uuid, $result->uuid);
    }

    /** @test */
    public function it_throws_exception_when_uuid_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->findByUuidOrFail(Str::uuid());
    }

    /** @test */
    public function it_can_get_paginated_records()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->getPaginated([]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertCount(2, $result['data']);
        $this->assertEquals(1, $result['meta']['current_page']);
        $this->assertEquals(2, $result['meta']['total']);
    }

    /** @test */
    public function it_can_search_records()
    {
        // Create test records
        $this->model->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->model->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

        $result = $this->repository->getPaginated(['search' => 'John']);

        $this->assertCount(1, $result['data']);
        $this->assertEquals('John Doe', $result['data'][0]->name);
    }

    /** @test */
    public function it_can_sort_records()
    {
        // Create test records
        $this->model->create(['name' => 'Zebra', 'email' => 'zebra@example.com']);
        $this->model->create(['name' => 'Apple', 'email' => 'apple@example.com']);

        $result = $this->repository->getPaginated(['sort_by' => 'name', 'sort_direction' => 'asc']);

        $this->assertEquals('Apple', $result['data'][0]->name);
        $this->assertEquals('Zebra', $result['data'][1]->name);

        // Test descending order
        $result = $this->repository->getPaginated(['sort_by' => 'name', 'sort_direction' => 'desc']);

        $this->assertEquals('Zebra', $result['data'][0]->name);
        $this->assertEquals('Apple', $result['data'][1]->name);
    }

    /** @test */
    public function it_can_filter_records()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->getPaginated([
            'filters' => ['email' => 'test1@example.com']
        ]);

        $this->assertCount(1, $result['data']);
        $this->assertEquals('test1@example.com', $result['data'][0]->email);
    }

    /** @test */
    public function it_can_get_all_records()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->all();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_find_by_field()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test1@example.com']);

        $result = $this->repository->findBy('email', 'test1@example.com');

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_find_one_by_field()
    {
        // Create test record
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);

        $result = $this->repository->findOneBy('email', 'test1@example.com');

        $this->assertNotNull($result);
        $this->assertEquals('test1@example.com', $result->email);
    }

    /** @test */
    public function it_can_find_by_criteria()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->findByCriteria([
            'name' => 'Test 1',
            'email' => 'test1@example.com'
        ]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    /** @test */
    public function it_can_count_records()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->count();

        $this->assertEquals(2, $result);
    }

    /** @test */
    public function it_can_check_if_record_exists()
    {
        $record = $this->model->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertTrue($this->repository->exists($record->uuid));
        $this->assertFalse($this->repository->exists(Str::uuid()));
    }

    /** @test */
    public function it_can_get_first_record()
    {
        // Create test records
        $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->first();

        $this->assertNotNull($result);
        $this->assertEquals('Test 1', $result->name);
    }

    /** @test */
    public function it_can_get_last_record()
    {
        // Create test records with explicit ordering
        $first = $this->model->create(['name' => 'Test 1', 'email' => 'test1@example.com']);
        $second = $this->model->create(['name' => 'Test 2', 'email' => 'test2@example.com']);

        $result = $this->repository->last();

        $this->assertNotNull($result);
        $this->assertEquals('Test 2', $result->name);
        $this->assertEquals($second->id, $result->id);
    }

    /** @test */
    public function it_can_apply_relations()
    {
        $result = $this->repository->with(['relation1', 'relation2']);

        $this->assertInstanceOf(ResourceRepository::class, $result);
    }

    /** @test */
    public function it_can_apply_conditions()
    {
        $result = $this->repository->where(['field' => 'value']);

        $this->assertInstanceOf(ResourceRepository::class, $result);
    }

    /** @test */
    public function it_can_apply_order()
    {
        $result = $this->repository->orderBy('name', 'desc');

        $this->assertInstanceOf(ResourceRepository::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
