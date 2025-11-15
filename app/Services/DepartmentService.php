<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\DepartmentRepositoryInterface;
use App\Contracts\Services\DepartmentServiceInterface;
use App\DTOs\CreateDepartmentDTO;
use App\DTOs\DepartmentDTO;
use App\DTOs\UpdateDepartmentDTO;

/**
 * Department Service
 *
 * Handles department management business logic.
 */
class DepartmentService implements DepartmentServiceInterface
{
    public function __construct(
        protected DepartmentRepositoryInterface $departmentRepository
    ) {}

    public function getAllDepartments(): array
    {
        $departments = $this->departmentRepository->all();

        return DepartmentDTO::collection($departments);
    }

    public function getActiveDepartments(): array
    {
        $departments = $this->departmentRepository->getAllActive();

        return DepartmentDTO::collection($departments);
    }

    public function getDepartmentById(int $id): ?DepartmentDTO
    {
        $department = $this->departmentRepository->find($id);

        return $department ? DepartmentDTO::fromModel($department) : null;
    }

    public function getDepartmentByCode(string $code): ?DepartmentDTO
    {
        $department = $this->departmentRepository->findByCode($code);

        return $department ? DepartmentDTO::fromModel($department) : null;
    }

    public function createDepartment(CreateDepartmentDTO $data): DepartmentDTO
    {
        $departmentData = [
            'name' => $data->name,
            'code' => $data->code,
            'description' => $data->description,
            'is_active' => $data->isActive,
        ];

        $department = $this->departmentRepository->create($departmentData);

        return DepartmentDTO::fromModel($department);
    }

    public function updateDepartment(int $id, UpdateDepartmentDTO $data): ?DepartmentDTO
    {
        try {
            $departmentData = [
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'is_active' => $data->isActive,
            ];

            $updated = $this->departmentRepository->update($id, $departmentData);

            if (! $updated) {
                return null;
            }

            $department = $this->departmentRepository->find($id);

            return $department ? DepartmentDTO::fromModel($department) : null;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteDepartment(int $id): bool
    {
        try {
            return $this->departmentRepository->delete($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }
    }

    public function codeExists(string $code, ?int $exceptId = null): bool
    {
        return $this->departmentRepository->codeExists($code, $exceptId);
    }

    public function canDelete(int $departmentId): bool
    {
        // Cannot delete if department has positions or employees
        return ! $this->departmentRepository->hasPositions($departmentId)
            && ! $this->departmentRepository->hasEmployees($departmentId);
    }
}
