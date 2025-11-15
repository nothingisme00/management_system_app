<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Create Employee Data Transfer Object
 *
 * Encapsulates data required to create a new employee.
 * Employee ID will be auto-generated in the service layer.
 */
final readonly class CreateEmployeeDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $roleId,
        public ?int $departmentId,
        public ?int $positionId,
        public ?string $phoneNumber,
        public ?string $address,
        public string $joinDate,
        public string $employmentStatus = 'active',
    ) {}
}
