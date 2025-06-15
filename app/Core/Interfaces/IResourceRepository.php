<?php

namespace App\Core\Interfaces;

interface IResourceRepository
{
    /**
     * Find a record by UUID
     */
    public function findByUuid(string $uuid): ?object;

    /**
     * Find a record by UUID or throw an exception
     */
    public function findByUuidOrFail(string $uuid): object;

    /**
     * Get paginated records with search, sort, and filter capabilities
     */
    public function getPaginated(array $params, ?int $perPage = 6): array;

    /**
     * Get all records
     */
    public function all(): array;

    /**
     * Create a new record
     */
    public function create(array $data): object;

    /**
     * Update a record by UUID
     */
    public function update(string $uuid, array $data): object;

    /**
     * Delete a record by UUID
     */
    public function delete(string $uuid): bool;

    /**
     * Get the base query builder instance
     */
    public function query(): object;

    /**
     * Find records by a specific field
     */
    public function findBy(string $field, mixed $value): array;

    /**
     * Find a single record by a specific field
     */
    public function findOneBy(string $field, mixed $value): ?object;

    /**
     * Find records by multiple criteria
     */
    public function findByCriteria(array $criteria): array;

    /**
     * Count records
     */
    public function count(): int;

    /**
     * Check if a record exists by UUID
     */
    public function exists(string $uuid): bool;

    /**
     * Get the first record
     */
    public function first(): ?object;

    /**
     * Get the last record
     */
    public function last(): ?object;

    /**
     * Get records with specific relationships
     */
    public function with(array $relations): self;

    /**
     * Get records with specific conditions
     */
    public function where(array $conditions): self;

    /**
     * Get records with specific order
     */
    public function orderBy(string $column, string $direction = 'asc'): self;
}
