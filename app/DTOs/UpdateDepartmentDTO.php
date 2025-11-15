<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Update Department Data Transfer Object
 *
 * Encapsulates data required to update an existing department.
 */
final readonly class UpdateDepartmentDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description,
        public bool $isActive,
    ) {}
}
