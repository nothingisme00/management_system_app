<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

/**
 * Department Repository
 *
 * Eloquent implementation for Department data access operations.
 */
class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code): ?Department
    {
        return $this->model->where('code', $code)->first();
    }

    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getAllWithPositions(): Collection
    {
        return $this->model->with('positions')->get();
    }

    public function getAllWithEmployees(): Collection
    {
        return $this->model->with('employees')->get();
    }

    public function codeExists(string $code, ?int $exceptId = null): bool
    {
        $query = $this->model->where('code', $code);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    public function hasPositions(int $departmentId): bool
    {
        return $this->model
            ->where('id', $departmentId)
            ->has('positions')
            ->exists();
    }

    public function hasEmployees(int $departmentId): bool
    {
        return $this->model
            ->where('id', $departmentId)
            ->has('employees')
            ->exists();
    }
}
