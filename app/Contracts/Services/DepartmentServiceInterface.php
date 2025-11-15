<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\CreateDepartmentDTO;
use App\DTOs\DepartmentDTO;
use App\DTOs\UpdateDepartmentDTO;

/**
 * Department Service Interface
 *
 * Defines department management operations.
 */
interface DepartmentServiceInterface
{
    /**
     * Get all departments.
     *
     * @return array<DepartmentDTO>
     */
    public function getAllDepartments(): array;

    /**
     * Get all active departments.
     *
     * @return array<DepartmentDTO>
     */
    public function getActiveDepartments(): array;

    /**
     * Get department by ID.
     */
    public function getDepartmentById(int $id): ?DepartmentDTO;

    /**
     * Get department by code.
     */
    public function getDepartmentByCode(string $code): ?DepartmentDTO;

    /**
     * Create new department.
     */
    public function createDepartment(CreateDepartmentDTO $data): DepartmentDTO;

    /**
     * Update department.
     */
    public function updateDepartment(int $id, UpdateDepartmentDTO $data): ?DepartmentDTO;

    /**
     * Delete department.
     */
    public function deleteDepartment(int $id): bool;

    /**
     * Check if department code exists.
     */
    public function codeExists(string $code, ?int $exceptId = null): bool;

    /**
     * Check if department can be deleted.
     */
    public function canDelete(int $departmentId): bool;
}
