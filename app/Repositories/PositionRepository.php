<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\PositionRepositoryInterface;
use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;

/**
 * Position Repository
 *
 * Eloquent implementation for Position data access operations.
 */
class PositionRepository extends BaseRepository implements PositionRepositoryInterface
{
    public function __construct(Position $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code): ?Position
    {
        return $this->model->where('code', $code)->first();
    }

    public function getAllActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    public function getByLevel(int $level): Collection
    {
        return $this->model->where('level', $level)->get();
    }

    public function getAllWithDepartments(): Collection
    {
        return $this->model->with('department')->get();
    }

    public function getOrderedByLevel(string $direction = 'desc'): Collection
    {
        return $this->model->orderBy('level', $direction)->get();
    }

    public function codeExists(string $code, ?int $exceptId = null): bool
    {
        $query = $this->model->where('code', $code);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    public function hasEmployees(int $positionId): bool
    {
        return $this->model
            ->where('id', $positionId)
            ->has('employees')
            ->exists();
    }
}
