<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Create Department Data Transfer Object
 *
 * Encapsulates data required to create a new department.
 */
final readonly class CreateDepartmentDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description,
        public bool $isActive = true,
    ) {}
}
