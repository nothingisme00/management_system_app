<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

/**
 * Employee Repository Interface
 *
 * Defines data access operations specific to Employee entity.
 */
interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find employee by employee ID.
     */
    public function findByEmployeeId(string $employeeId): ?Employee;

    /**
     * Find employee by user ID.
     */
    public function findByUserId(int $userId): ?Employee;

    /**
     * Get all active employees.
     */
    public function getAllActive(): Collection;

    /**
     * Get employees by department.
     */
    public function getByDepartment(int $departmentId): Collection;

    /**
     * Get employees by position.
     */
    public function getByPosition(int $positionId): Collection;

    /**
     * Get employees by employment status.
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get all employees with their relationships.
     */
    public function getAllWithRelations(): Collection;

    /**
     * Check if employee ID exists.
     */
    public function employeeIdExists(string $employeeId): bool;

    /**
     * Check if user already has employee record.
     */
    public function userHasEmployee(int $userId): bool;

    /**
     * Update employment status.
     */
    public function updateEmploymentStatus(int $employeeId, string $status): bool;

    /**
     * Terminate employee.
     */
    public function terminate(int $employeeId, ?string $terminationDate = null): bool;
}
