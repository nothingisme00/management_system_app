<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\EmployeeServiceInterface;
use App\DTOs\CreateEmployeeDTO;
use App\DTOs\EmployeeDTO;
use App\DTOs\UpdateEmployeeDTO;
use App\Helpers\EmployeeIdGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Employee Service
 *
 * Handles employee management business logic including auto-generation of Employee IDs.
 */
class EmployeeService implements EmployeeServiceInterface
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function getAllEmployees(): array
    {
        $employees = $this->employeeRepository->getAllWithRelations();

        return EmployeeDTO::collection($employees);
    }

    public function getActiveEmployees(): array
    {
        $employees = $this->employeeRepository->getAllActive();

        return EmployeeDTO::collection($employees);
    }

    public function getEmployeesByDepartment(int $departmentId): array
    {
        $employees = $this->employeeRepository->getByDepartment($departmentId);

        return EmployeeDTO::collection($employees);
    }

    public function getEmployeesByPosition(int $positionId): array
    {
        $employees = $this->employeeRepository->getByPosition($positionId);

        return EmployeeDTO::collection($employees);
    }

    public function getEmployeesByStatus(string $status): array
    {
        $employees = $this->employeeRepository->getByStatus($status);

        return EmployeeDTO::collection($employees);
    }

    public function getEmployeeById(int $id): ?EmployeeDTO
    {
        $employee = $this->employeeRepository->find($id);

        return $employee ? EmployeeDTO::fromModel($employee->load(['user', 'department', 'position'])) : null;
    }

    public function getEmployeeByEmployeeId(string $employeeId): ?EmployeeDTO
    {
        $employee = $this->employeeRepository->findByEmployeeId($employeeId);

        return $employee ? EmployeeDTO::fromModel($employee->load(['user', 'department', 'position'])) : null;
    }

    public function getEmployeeByUserId(int $userId): ?EmployeeDTO
    {
        $employee = $this->employeeRepository->findByUserId($userId);

        return $employee ? EmployeeDTO::fromModel($employee->load(['user', 'department', 'position'])) : null;
    }

    public function createEmployee(CreateEmployeeDTO $data): EmployeeDTO
    {
        return DB::transaction(function () use ($data) {
            // Step 1: Create User record
            $userData = [
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'role_id' => $data->roleId,
            ];

            $user = $this->userRepository->create($userData);

            // Step 2: Auto-generate Employee ID
            $employeeId = EmployeeIdGenerator::generate();

            // Step 3: Create Employee record
            $employeeData = [
                'user_id' => $user->id,
                'employee_id' => $employeeId,
                'department_id' => $data->departmentId,
                'position_id' => $data->positionId,
                'phone_number' => $data->phoneNumber,
                'address' => $data->address,
                'join_date' => $data->joinDate,
                'employment_status' => $data->employmentStatus,
            ];

            $employee = $this->employeeRepository->create($employeeData);

            return EmployeeDTO::fromModel($employee->load(['user', 'department', 'position']));
        });
    }

    public function updateEmployee(int $id, UpdateEmployeeDTO $data): ?EmployeeDTO
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                // Get employee with user
                $employee = $this->employeeRepository->find($id);

                if (! $employee) {
                    return null;
                }

                // Update User record
                $userData = [
                    'name' => $data->name,
                    'email' => $data->email,
                    'role_id' => $data->roleId,
                ];

                $this->userRepository->update($employee->user_id, $userData);

                // Update Employee record
                $employeeData = [
                    'department_id' => $data->departmentId,
                    'position_id' => $data->positionId,
                    'phone_number' => $data->phoneNumber,
                    'address' => $data->address,
                    'join_date' => $data->joinDate,
                    'termination_date' => $data->terminationDate,
                    'employment_status' => $data->employmentStatus,
                ];

                $updated = $this->employeeRepository->update($id, $employeeData);

                if (! $updated) {
                    return null;
                }

                $employee = $this->employeeRepository->find($id);

                return $employee ? EmployeeDTO::fromModel($employee->load(['user', 'department', 'position'])) : null;
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteEmployee(int $id): bool
    {
        try {
            // Deleting employee will cascade delete user due to foreign key constraint
            return $this->employeeRepository->delete($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }
    }

    public function updateEmploymentStatus(int $employeeId, string $status): bool
    {
        return $this->employeeRepository->updateEmploymentStatus($employeeId, $status);
    }

    public function terminateEmployee(int $employeeId, ?string $terminationDate = null): bool
    {
        return $this->employeeRepository->terminate($employeeId, $terminationDate);
    }

    public function resetPassword(int $employeeId, string $newPassword): bool
    {
        $employee = $this->employeeRepository->find($employeeId);

        if (! $employee) {
            return false;
        }

        return $this->userRepository->update($employee->user_id, [
            'password' => Hash::make($newPassword),
        ]);
    }
}
