<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

/**
 * Employee Repository
 *
 * Eloquent implementation for Employee data access operations.
 */
class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    public function findByEmployeeId(string $employeeId): ?Employee
    {
        return $this->model->where('employee_id', $employeeId)->first();
    }

    public function findByUserId(int $userId): ?Employee
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function getAllActive(): Collection
    {
        return $this->model->where('employment_status', 'active')->get();
    }

    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    public function getByPosition(int $positionId): Collection
    {
        return $this->model->where('position_id', $positionId)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('employment_status', $status)->get();
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model
            ->with(['user', 'department', 'position'])
            ->get();
    }

    public function employeeIdExists(string $employeeId): bool
    {
        return $this->model->where('employee_id', $employeeId)->exists();
    }

    public function userHasEmployee(int $userId): bool
    {
        return $this->model->where('user_id', $userId)->exists();
    }

    public function updateEmploymentStatus(int $employeeId, string $status): bool
    {
        return (bool) $this->model
            ->where('id', $employeeId)
            ->update(['employment_status' => $status]);
    }

    public function terminate(int $employeeId, ?string $terminationDate = null): bool
    {
        return (bool) $this->model
            ->where('id', $employeeId)
            ->update([
                'employment_status' => 'terminated',
                'termination_date' => $terminationDate ?? now()->toDateString(),
            ]);
    }
}
