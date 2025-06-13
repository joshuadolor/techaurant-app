<?php

namespace App\Core\Services;

use App\Core\Interfaces\IResourceRepository;
use App\Core\Interfaces\IResourceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class ResourceService implements IResourceService
{
    protected IResourceRepository $repo;

    public function __construct(IResourceRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getPaginated(array $params, ?int $perPage = 15): array
    {
        $validatedParams = $this->validateSearchParams($params);
        return $this->repo->getPaginated($validatedParams, $perPage);
    }

    public function find(string $uuid): ?object
    {
        return $this->repo->findByUuid($uuid);
    }

    public function findOrFail(string $uuid): object
    {
        $record = $this->repo->findByUuid($uuid);
        if (!$record) {
            throw new ModelNotFoundException();
        }
        return $record;
    }

    public function create(array $data): object
    {
        $processedData = $this->processCreateData($data);
        $result = $this->repo->create($processedData);
        return $this->transformData($result);
    }

    public function update(string $uuid, array $data): object
    {
        $processedData = $this->processUpdateData($data);
        $result = $this->repo->update($uuid, $processedData);
        return $this->transformData($result);
    }

    public function delete(string $uuid): bool
    {
        return $this->repo->delete($uuid);
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->repo->findByCriteria($criteria);
    }

    public function exists(string $uuid): bool
    {
        return $this->repo->exists($uuid);
    }

    public function all(): array
    {
        return $this->repo->all();
    }

    public function count(): int
    {
        return $this->repo->count();
    }

    public function first(): ?object
    {
        return $this->repo->first();
    }

    public function last(): ?object
    {
        return $this->repo->last();
    }

    /**
     * Get the searchable fields for this resource
     * Override this in concrete services to specify searchable fields
     */
    protected function getSearchableFields(): array
    {
        return [];
    }

    /**
     * Get the sortable fields for this resource
     * Override this in concrete services to specify sortable fields
     */
    protected function getSortableFields(): array
    {
        return [];
    }

    /**
     * Get the filterable fields for this resource
     * Override this in concrete services to specify filterable fields
     */
    protected function getFilterableFields(): array
    {
        return [];
    }

    /**
     * Validate search parameters
     * Override this in concrete services to add custom validation
     */
    protected function validateSearchParams(array $params): array
    {
        // Validate search fields
        if (isset($params['search'])) {
            $searchableFields = $this->getSearchableFields();
            if (empty($searchableFields)) {
                unset($params['search']);
            }
        }

        // Validate sort fields
        if (isset($params['sort_by'])) {
            $sortableFields = $this->getSortableFields();
            if (!in_array($params['sort_by'], $sortableFields)) {
                unset($params['sort_by']);
            }
        }

        // Validate filter fields
        if (isset($params['filters'])) {
            $filterableFields = $this->getFilterableFields();
            $params['filters'] = array_intersect_key(
                $params['filters'],
                array_flip($filterableFields)
            );
        }

        return $params;
    }

    /**
     * Process data before creating
     * Override this in concrete services to add custom processing
     */
    protected function processCreateData(array $data): array
    {
        return $data;
    }

    /**
     * Process data before updating
     * Override this in concrete services to add custom processing
     */
    protected function processUpdateData(array $data): array
    {
        return $data;
    }

    /**
     * Transform data before returning
     * Override this in concrete services to add custom transformation
     */
    protected function transformData(object $data): object
    {
        return $data;
    }
}
