<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Update Employee Data Transfer Object
 *
 * Encapsulates data required to update an existing employee.
 */
final readonly class UpdateEmployeeDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public int $roleId,
        public ?int $departmentId,
        public ?int $positionId,
        public ?string $phoneNumber,
        public ?string $address,
        public string $joinDate,
        public ?string $terminationDate,
        public string $employmentStatus,
    ) {}
}
