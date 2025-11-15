<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;

/**
 * Position Repository Interface
 *
 * Defines data access operations specific to Position entity.
 */
interface PositionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find position by code.
     */
    public function findByCode(string $code): ?Position;

    /**
     * Get all active positions.
     */
    public function getAllActive(): Collection;

    /**
     * Get positions by department.
     */
    public function getByDepartment(int $departmentId): Collection;

    /**
     * Get positions by level.
     */
    public function getByLevel(int $level): Collection;

    /**
     * Get all positions with their departments.
     */
    public function getAllWithDepartments(): Collection;

    /**
     * Get positions ordered by level (descending by default).
     */
    public function getOrderedByLevel(string $direction = 'desc'): Collection;

    /**
     * Check if position code exists.
     */
    public function codeExists(string $code, ?int $exceptId = null): bool;

    /**
     * Check if position has employees.
     */
    public function hasEmployees(int $positionId): bool;
}
