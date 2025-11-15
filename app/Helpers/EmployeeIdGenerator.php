<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Employee;

/**
 * Employee ID Generator
 *
 * Generates unique employee IDs in the format: EMP-DEPT-XXXX
 * Where DEPT is the department code and XXXX is a sequential number.
 */
class EmployeeIdGenerator
{
    /**
     * Generate a unique employee ID.
     *
     * Format: EMP-DEPT-XXXX (e.g., EMP-IT-0001, EMP-HR-0023)
     */
    public static function generate(string $departmentCode): string
    {
        $prefix = 'EMP-'.strtoupper($departmentCode).'-';

        // Get the latest employee ID with this prefix
        $latestEmployee = Employee::query()
            ->where('employee_id', 'like', "{$prefix}%")
            ->orderByDesc('employee_id')
            ->first();

        if (! $latestEmployee) {
            // First employee with this department code
            return $prefix.'0001';
        }

        // Extract the sequence number from the latest employee ID
        $latestId = $latestEmployee->employee_id;
        $sequence = (int) substr($latestId, -4);

        // Increment and format with leading zeros
        $newSequence = $sequence + 1;

        return $prefix.str_pad((string) $newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validate employee ID format.
     *
     * Valid format: EMP-DEPT-XXXX (e.g., EMP-IT-0001)
     */
    public static function isValid(string $employeeId): bool
    {
        return (bool) preg_match('/^EMP-[A-Z]+-\d{4}$/', $employeeId);
    }

    /**
     * Extract department code from employee ID.
     */
    public static function extractDepartmentCode(string $employeeId): ?string
    {
        if (! self::isValid($employeeId)) {
            return null;
        }

        $parts = explode('-', $employeeId);

        return $parts[1] ?? null;
    }

    /**
     * Extract sequence number from employee ID.
     */
    public static function extractSequence(string $employeeId): ?int
    {
        if (! self::isValid($employeeId)) {
            return null;
        }

        return (int) substr($employeeId, -4);
    }
}
