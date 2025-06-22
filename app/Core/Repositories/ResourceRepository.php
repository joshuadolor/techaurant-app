<?php

namespace App\Core\Repositories;

use App\Core\Interfaces\IResourceRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResourceRepository implements IResourceRepository
{
    protected Model $model;
    protected array $searchableFields = [];
    protected array $relations = [];
    protected array $conditions = [];
    protected array $orderBy = [];

    public function __construct(Model $model, array $searchableFields = [])
    {
        $this->model = $model;
        $this->searchableFields = $searchableFields;
    }

    public function findByUuid(string $uuid): ?object
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function findByUuidOrFail(string $uuid): object
    {
        $model = $this->findByUuid($uuid);
        if (!$model) {
            throw new ModelNotFoundException();
        }
        return $model;
    }

    public function getPaginated(array $params, ?int $perPage = 6): array
    {
        $query = $this->buildQuery($params);
        $paginator = $query->paginate($perPage);

        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    public function all(): array
    {
        $query = $this->buildQuery();
        return $query->get()->all();
    }

    public function query(): object
    {
        return $this->model->query();
    }

    public function findBy(string $field, mixed $value): array
    {
        return $this->model->where($field, $value)->get()->all();
    }

    public function findOneBy(string $field, mixed $value): ?object
    {
        return $this->model->where($field, $value)->first();
    }

    public function findByCriteria(array $criteria): array
    {
        $query = $this->model->query();

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query->get()->all();
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function exists(string $uuid): bool
    {
        return $this->model->where('uuid', $uuid)->exists();
    }

    public function first(): ?object
    {
        return $this->model->first();
    }

    public function last(): ?object
    {
        return $this->model->orderBy('id', 'desc')->first();
    }

    public function with(array $relations): self
    {
        $this->relations = $relations;
        return $this;
    }

    public function where(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = ['column' => $column, 'direction' => $direction];
        return $this;
    }

    /**
     * Build the query with all applied conditions
     */
    protected function buildQuery(array $params = []): object
    {
        $query = $this->model->query();

        // Apply relations
        if (!empty($this->relations)) {
            $query->with($this->relations);
        }

        // Apply conditions
        if (!empty($this->conditions)) {
            foreach ($this->conditions as $field => $value) {
                $query->where($field, $value);
            }
        }

        // Apply order
        if (!empty($this->orderBy)) {
            $query->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        }

        // Handle search
        if (isset($params['search'])) {
            $query->where(function ($q) use ($params) {
                foreach ($this->searchableFields as $field) {
                    $q->orWhere($field, 'like', "%{$params['search']}%");
                }
            });
        }

        // Handle sorting
        if (isset($params['sort_by'])) {
            $sort_direction = $params['sort_direction'] ?? 'asc';
            $query->orderBy($params['sort_by'], $sort_direction);
        }

        // Handle filtering
        if (isset($params['filters'])) {
            foreach ($params['filters'] as $field => $value) {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Reset the query builder state
     */
    protected function resetQuery(): void
    {
        $this->relations = [];
        $this->conditions = [];
        $this->orderBy = [];
    }

    protected function executeInTransaction(callable $callback)
    {
        try {
            return \DB::transaction($callback);
        } catch (\Exception $e) {
            \Log::error('Transaction failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): object
    {
        return $this->executeInTransaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(string $uuid, array $data): object
    {
        return $this->executeInTransaction(function () use ($uuid, $data) {
            $model = $this->findByUuidOrFail($uuid);
            $model->update($data);
            return $model;
        });
    }

    public function delete(string $uuid): bool
    {
        return $this->executeInTransaction(function () use ($uuid) {
            $model = $this->findByUuidOrFail($uuid);
            return $model->delete();
        });
    }
}
