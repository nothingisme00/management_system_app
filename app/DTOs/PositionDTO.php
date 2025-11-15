<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Position;

/**
 * Position Data Transfer Object
 *
 * Encapsulates position data for transfer between application layers.
 */
final readonly class PositionDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public ?string $description,
        public int $level,
        public ?int $departmentId,
        public ?string $departmentName,
        public ?string $departmentCode,
        public bool $isActive,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    /**
     * Create from Position model.
     */
    public static function fromModel(Position $position): self
    {
        return new self(
            id: $position->id,
            name: $position->name,
            code: $position->code,
            description: $position->description,
            level: $position->level,
            departmentId: $position->department_id,
            departmentName: $position->department?->name,
            departmentCode: $position->department?->code,
            isActive: $position->is_active,
            createdAt: $position->created_at?->toIso8601String(),
            updatedAt: $position->updated_at?->toIso8601String(),
        );
    }

    /**
     * Create collection from multiple Position models.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<Position>  $positions
     * @return array<self>
     */
    public static function collection($positions): array
    {
        return $positions->map(fn (Position $position) => self::fromModel($position))->all();
    }

    /**
     * Get level label.
     */
    public function levelLabel(): string
    {
        return match ($this->level) {
            5 => 'C-Level / Executive',
            4 => 'Manager',
            3 => 'Senior',
            2 => 'Junior / Specialist',
            1 => 'Staff',
            default => "Level {$this->level}",
        };
    }
}
