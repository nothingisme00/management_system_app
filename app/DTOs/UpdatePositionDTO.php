<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Update Position Data Transfer Object
 *
 * Encapsulates data required to update an existing position.
 */
final readonly class UpdatePositionDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description,
        public int $level,
        public ?int $departmentId,
        public bool $isActive,
    ) {
    }
}
