<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Department;

/**
 * Department Data Transfer Object
 *
 * Encapsulates department data for transfer between application layers.
 */
final readonly class DepartmentDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public ?string $description,
        public bool $isActive,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {}

    /**
     * Create from Department model.
     */
    public static function fromModel(Department $department): self
    {
        return new self(
            id: $department->id,
            name: $department->name,
            code: $department->code,
            description: $department->description,
            isActive: $department->is_active,
            createdAt: $department->created_at?->toIso8601String(),
            updatedAt: $department->updated_at?->toIso8601String(),
        );
    }

    /**
     * Create collection from multiple Department models.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<Department>  $departments
     * @return array<self>
     */
    public static function collection($departments): array
    {
        return $departments->map(fn (Department $department) => self::fromModel($department))->all();
    }
}
