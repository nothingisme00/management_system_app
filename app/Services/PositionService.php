<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\PositionRepositoryInterface;
use App\Contracts\Services\PositionServiceInterface;
use App\DTOs\CreatePositionDTO;
use App\DTOs\PositionDTO;
use App\DTOs\UpdatePositionDTO;

/**
 * Position Service
 *
 * Handles position management business logic.
 */
class PositionService implements PositionServiceInterface
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository
    ) {}

    public function getAllPositions(): array
    {
        $positions = $this->positionRepository->getAllWithDepartments();

        return PositionDTO::collection($positions);
    }

    public function getActivePositions(): array
    {
        $positions = $this->positionRepository->getAllActive();

        return PositionDTO::collection($positions);
    }

    public function getPositionsByDepartment(int $departmentId): array
    {
        $positions = $this->positionRepository->getByDepartment($departmentId);

        return PositionDTO::collection($positions);
    }

    public function getPositionsOrderedByLevel(string $direction = 'desc'): array
    {
        $positions = $this->positionRepository->getOrderedByLevel($direction);

        return PositionDTO::collection($positions);
    }

    public function getPositionById(int $id): ?PositionDTO
    {
        $position = $this->positionRepository->find($id);

        return $position ? PositionDTO::fromModel($position->load('department')) : null;
    }

    public function getPositionByCode(string $code): ?PositionDTO
    {
        $position = $this->positionRepository->findByCode($code);

        return $position ? PositionDTO::fromModel($position->load('department')) : null;
    }

    public function createPosition(CreatePositionDTO $data): PositionDTO
    {
        $positionData = [
            'name' => $data->name,
            'code' => $data->code,
            'description' => $data->description,
            'level' => $data->level,
            'department_id' => $data->departmentId,
            'is_active' => $data->isActive,
        ];

        $position = $this->positionRepository->create($positionData);

        return PositionDTO::fromModel($position->load('department'));
    }

    public function updatePosition(int $id, UpdatePositionDTO $data): ?PositionDTO
    {
        try {
            $positionData = [
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'level' => $data->level,
                'department_id' => $data->departmentId,
                'is_active' => $data->isActive,
            ];

            $updated = $this->positionRepository->update($id, $positionData);

            if (! $updated) {
                return null;
            }

            $position = $this->positionRepository->find($id);

            return $position ? PositionDTO::fromModel($position->load('department')) : null;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deletePosition(int $id): bool
    {
        try {
            return $this->positionRepository->delete($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }
    }

    public function codeExists(string $code, ?int $exceptId = null): bool
    {
        return $this->positionRepository->codeExists($code, $exceptId);
    }

    public function canDelete(int $positionId): bool
    {
        // Cannot delete if position has employees
        return ! $this->positionRepository->hasEmployees($positionId);
    }
}
