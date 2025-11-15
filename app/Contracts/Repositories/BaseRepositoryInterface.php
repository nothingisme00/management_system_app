<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Repository Interface
 *
 * Defines standard CRUD operations that all repositories should implement.
 */
interface BaseRepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or fail.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Model;

    /**
     * Find a record by specific column.
     */
    public function findBy(string $column, mixed $value): ?Model;

    /**
     * Get all records matching criteria.
     */
    public function findAllBy(string $column, mixed $value): Collection;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record by ID.
     */
    public function delete(int $id): bool;

    /**
     * Check if record exists.
     */
    public function exists(int $id): bool;

    /**
     * Count all records.
     */
    public function count(): int;
}
