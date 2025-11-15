<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Create Position Data Transfer Object
 *
 * Encapsulates data required to create a new position.
 */
final readonly class CreatePositionDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description,
        public int $level,
        public ?int $departmentId,
        public bool $isActive = true,
    ) {}
}
