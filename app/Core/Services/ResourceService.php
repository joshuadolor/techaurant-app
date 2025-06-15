<?php

namespace App\Core\Services;

use App\Core\Interfaces\IResourceRepository;
use App\Core\Interfaces\IResourceService;
use App\Core\Transformers\ResourceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResourceService implements IResourceService
{
    protected IResourceRepository $repo;
    protected array $searchableFields = [];
    protected array $sortableFields = [];
    protected array $filterableFields = [];
    protected ResourceTransformer $transformer;

    public function __construct(IResourceRepository $repo, ResourceTransformer $transformer, array $fields = [])
    {
        $this->repo = $repo;
        $this->transformer = $transformer;
        $this->searchableFields = $fields['searchable'] ?? [];
        $this->sortableFields = $fields['sortable'] ?? [];
        $this->filterableFields = $fields['filterable'] ?? [];
    }

    public function getPaginated(array $params, ?int $perPage = 6): array
    {
        $validatedParams = $this->validateSearchParams($params);

        $result = $this->repo->getPaginated($validatedParams, $perPage);

        $result['data'] = array_map(function ($item) {
            return $this->transformData($item);
        }, $result['data']);

        return $result;
    }

    public function find(string $uuid): ?array
    {
        $result = $this->repo->findByUuid($uuid);
        return $this->transformData($result);
    }

    public function findOrFail(string $uuid): array
    {
        $record = $this->repo->findByUuid($uuid);
        if (!$record) {
            throw new ModelNotFoundException();
        }
        return $this->transformData($record);
    }

    public function create(array $data): array
    {
        $processedData = $this->processCreateData($data);
        $result = $this->repo->create($processedData);
        return $this->transformData($result);
    }

    public function update(string $uuid, array $data): array
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
        $result = $this->repo->findByCriteria($criteria);
        return $this->transformData($result);
    }

    public function exists(string $uuid): bool
    {
        return $this->repo->exists($uuid);
    }

    public function all(): array
    {
        $result = $this->repo->all();
        return $this->transformData($result);
    }

    public function count(): int
    {
        return $this->repo->count();
    }

    public function first(): ?array
    {
        $result = $this->repo->first();
        return $this->transformData($result);
    }

    public function last(): ?array
    {
        $result = $this->repo->last();
        return $this->transformData($result);
    }

    /**
     * Get the searchable fields for this resource
     * Override this in concrete services to specify searchable fields
     */
    protected function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    /**
     * Get the sortable fields for this resource
     * Override this in concrete services to specify sortable fields
     */
    protected function getSortableFields(): array
    {
        return $this->sortableFields;
    }

    /**
     * Get the filterable fields for this resource
     * Override this in concrete services to specify filterable fields
     */
    protected function getFilterableFields(): array
    {
        return $this->filterableFields;
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
    protected function transformData($data): array
    {
        return $this->transformer->transform($data->toArray());
    }
}
