<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Employee;

/**
 * Employee ID Generator
 *
 * Generates unique employee IDs in the format: EMP-YYYY-XXXX
 * Where YYYY is the current year and XXXX is a sequential number.
 */
class EmployeeIdGenerator
{
    /**
     * Generate a unique employee ID.
     *
     * Format: EMP-YYYY-XXXX (e.g., EMP-2025-0001)
     */
    public static function generate(): string
    {
        $year = now()->year;
        $prefix = "EMP-{$year}-";

        // Get the latest employee ID for the current year
        $latestEmployee = Employee::query()
            ->where('employee_id', 'like', "{$prefix}%")
            ->orderByDesc('employee_id')
            ->first();

        if (! $latestEmployee) {
            // First employee of the year
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
     */
    public static function isValid(string $employeeId): bool
    {
        return (bool) preg_match('/^EMP-\d{4}-\d{4}$/', $employeeId);
    }

    /**
     * Extract year from employee ID.
     */
    public static function extractYear(string $employeeId): ?int
    {
        if (! self::isValid($employeeId)) {
            return null;
        }

        return (int) substr($employeeId, 4, 4);
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
