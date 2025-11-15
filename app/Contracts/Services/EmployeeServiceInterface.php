<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\CreateEmployeeDTO;
use App\DTOs\EmployeeDTO;
use App\DTOs\UpdateEmployeeDTO;

/**
 * Employee Service Interface
 *
 * Defines employee management operations.
 */
interface EmployeeServiceInterface
{
    /**
     * Get all employees.
     *
     * @return array<EmployeeDTO>
     */
    public function getAllEmployees(): array;

    /**
     * Get all active employees.
     *
     * @return array<EmployeeDTO>
     */
    public function getActiveEmployees(): array;

    /**
     * Get employees by department.
     *
     * @return array<EmployeeDTO>
     */
    public function getEmployeesByDepartment(int $departmentId): array;

    /**
     * Get employees by position.
     *
     * @return array<EmployeeDTO>
     */
    public function getEmployeesByPosition(int $positionId): array;

    /**
     * Get employees by status.
     *
     * @return array<EmployeeDTO>
     */
    public function getEmployeesByStatus(string $status): array;

    /**
     * Get employee by ID.
     */
    public function getEmployeeById(int $id): ?EmployeeDTO;

    /**
     * Get employee by employee ID.
     */
    public function getEmployeeByEmployeeId(string $employeeId): ?EmployeeDTO;

    /**
     * Get employee by user ID.
     */
    public function getEmployeeByUserId(int $userId): ?EmployeeDTO;

    /**
     * Create new employee (creates both User and Employee records).
     */
    public function createEmployee(CreateEmployeeDTO $data): EmployeeDTO;

    /**
     * Update employee.
     */
    public function updateEmployee(int $id, UpdateEmployeeDTO $data): ?EmployeeDTO;

    /**
     * Delete employee.
     */
    public function deleteEmployee(int $id): bool;

    /**
     * Update employment status.
     */
    public function updateEmploymentStatus(int $employeeId, string $status): bool;

    /**
     * Terminate employee.
     */
    public function terminateEmployee(int $employeeId, ?string $terminationDate = null): bool;

    /**
     * Reset employee password.
     */
    public function resetPassword(int $employeeId, string $newPassword): bool;
}
