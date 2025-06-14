<?php

namespace App\Core\Interfaces;

interface IResourceService
{
    /**
     * Get paginated records with search, sort, and filter capabilities
     * 
     * @param array $params Search, sort, and filter parameters
     * @param int|null $perPage Number of records per page
     * @return array{data: array, meta: array} Paginated data with metadata
     */
    public function getPaginated(array $params, ?int $perPage = 15): array;

    /**
     * Find a record by UUID
     * 
     * @param string $uuid The UUID of the record
     * @return object|null The found record or null
     */
    public function find(string $uuid): ?array;

    /**
     * Find a record by UUID or throw an exception
     * 
     * @param string $uuid The UUID of the record
     * @return object The found record
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(string $uuid): array;

    /**
     * Create a new record
     * 
     * @param array $data The data to create the record with
     * @return object The created record
     */
    public function create(array $data): array;

    /**
     * Update a record
     * 
     * @param string $uuid The UUID of the record to update
     * @param array $data The data to update the record with
     * @return object The updated record
     */
    public function update(string $uuid, array $data): array;

    /**
     * Delete a record
     * 
     * @param string $uuid The UUID of the record to delete
     * @return bool Whether the deletion was successful
     */
    public function delete(string $uuid): bool;

    /**
     * Find records by specific criteria
     * 
     * @param array $criteria The search criteria
     * @return array The found records
     */
    public function findByCriteria(array $criteria): array;

    /**
     * Check if a record exists
     * 
     * @param string $uuid The UUID to check
     * @return bool Whether the record exists
     */
    public function exists(string $uuid): bool;

    /**
     * Get all records
     * 
     * @return array All records
     */
    public function all(): array;

    /**
     * Count total records
     * 
     * @return int Total number of records
     */
    public function count(): int;

    /**
     * Get the first record
     * 
     * @return object|null The first record or null
     */
    public function first(): ?array;

    /**
     * Get the last record
     * 
     * @return object|null The last record or null
     */
    public function last(): ?array;
}
