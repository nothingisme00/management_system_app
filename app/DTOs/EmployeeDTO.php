<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Employee;

/**
 * Employee Data Transfer Object
 *
 * Encapsulates employee data for transfer between application layers.
 */
final readonly class EmployeeDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $employeeId,
        public ?int $departmentId,
        public ?string $departmentName,
        public ?string $departmentCode,
        public ?int $positionId,
        public ?string $positionName,
        public ?string $positionCode,
        public ?int $positionLevel,
        public ?string $phoneNumber,
        public ?string $address,
        public string $joinDate,
        public ?string $terminationDate,
        public string $employmentStatus,
        public string $userName,
        public string $userEmail,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    /**
     * Create from Employee model.
     */
    public static function fromModel(Employee $employee): self
    {
        return new self(
            id: $employee->id,
            userId: $employee->user_id,
            employeeId: $employee->employee_id,
            departmentId: $employee->department_id,
            departmentName: $employee->department?->name,
            departmentCode: $employee->department?->code,
            positionId: $employee->position_id,
            positionName: $employee->position?->name,
            positionCode: $employee->position?->code,
            positionLevel: $employee->position?->level,
            phoneNumber: $employee->phone_number,
            address: $employee->address,
            joinDate: $employee->join_date->toDateString(),
            terminationDate: $employee->termination_date?->toDateString(),
            employmentStatus: $employee->employment_status,
            userName: $employee->user->name,
            userEmail: $employee->user->email,
            createdAt: $employee->created_at?->toIso8601String(),
            updatedAt: $employee->updated_at?->toIso8601String(),
        );
    }

    /**
     * Create collection from multiple Employee models.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<Employee>  $employees
     * @return array<self>
     */
    public static function collection($employees): array
    {
        return $employees->map(fn (Employee $employee) => self::fromModel($employee))->all();
    }

    /**
     * Get employment status badge color.
     */
    public function statusColor(): string
    {
        return match ($this->employmentStatus) {
            'active' => 'green',
            'inactive' => 'gray',
            'on_leave' => 'yellow',
            'terminated' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get employment status label.
     */
    public function statusLabel(): string
    {
        return match ($this->employmentStatus) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_leave' => 'On Leave',
            'terminated' => 'Terminated',
            default => ucfirst($this->employmentStatus),
        };
    }

    /**
     * Check if employee is active.
     */
    public function isActive(): bool
    {
        return $this->employmentStatus === 'active';
    }

    /**
     * Check if employee is terminated.
     */
    public function isTerminated(): bool
    {
        return $this->employmentStatus === 'terminated';
    }
}
