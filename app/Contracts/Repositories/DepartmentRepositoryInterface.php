<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

/**
 * Department Repository Interface
 *
 * Defines data access operations specific to Department entity.
 */
interface DepartmentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find department by code.
     */
    public function findByCode(string $code): ?Department;

    /**
     * Get all active departments.
     */
    public function getAllActive(): Collection;

    /**
     * Get departments with their positions.
     */
    public function getAllWithPositions(): Collection;

    /**
     * Get departments with their employees.
     */
    public function getAllWithEmployees(): Collection;

    /**
     * Check if department code exists.
     */
    public function codeExists(string $code, ?int $exceptId = null): bool;

    /**
     * Check if department has positions.
     */
    public function hasPositions(int $departmentId): bool;

    /**
     * Check if department has employees.
     */
    public function hasEmployees(int $departmentId): bool;
}
