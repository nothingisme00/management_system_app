<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\CreatePositionDTO;
use App\DTOs\PositionDTO;
use App\DTOs\UpdatePositionDTO;

/**
 * Position Service Interface
 *
 * Defines position management operations.
 */
interface PositionServiceInterface
{
    /**
     * Get all positions.
     *
     * @return array<PositionDTO>
     */
    public function getAllPositions(): array;

    /**
     * Get all active positions.
     *
     * @return array<PositionDTO>
     */
    public function getActivePositions(): array;

    /**
     * Get positions by department.
     *
     * @return array<PositionDTO>
     */
    public function getPositionsByDepartment(int $departmentId): array;

    /**
     * Get positions ordered by level.
     *
     * @return array<PositionDTO>
     */
    public function getPositionsOrderedByLevel(string $direction = 'desc'): array;

    /**
     * Get position by ID.
     */
    public function getPositionById(int $id): ?PositionDTO;

    /**
     * Get position by code.
     */
    public function getPositionByCode(string $code): ?PositionDTO;

    /**
     * Create new position.
     */
    public function createPosition(CreatePositionDTO $data): PositionDTO;

    /**
     * Update position.
     */
    public function updatePosition(int $id, UpdatePositionDTO $data): ?PositionDTO;

    /**
     * Delete position.
     */
    public function deletePosition(int $id): bool;

    /**
     * Check if position code exists.
     */
    public function codeExists(string $code, ?int $exceptId = null): bool;

    /**
     * Check if position can be deleted.
     */
    public function canDelete(int $positionId): bool;
}
