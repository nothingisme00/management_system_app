<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\AuthorizationServiceInterface;
use App\Models\User;

/**
 * Authorization Service
 *
 * Handles authorization business logic including role checks and permissions.
 */
class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * Role names constants.
     */
    private const ROLE_ADMIN = 'Admin';

    private const ROLE_HRD = 'HRD';

    private const ROLE_MANAGER = 'Manager';

    private const ROLE_KARYAWAN = 'Karyawan';

    public function hasRole(User $user, string $roleName): bool
    {
        return $user->role?->name === $roleName;
    }

    public function isAdmin(User $user): bool
    {
        return $this->hasRole($user, self::ROLE_ADMIN);
    }

    public function isHRD(User $user): bool
    {
        return $this->hasRole($user, self::ROLE_HRD);
    }

    public function isManager(User $user): bool
    {
        return $this->hasRole($user, self::ROLE_MANAGER);
    }

    public function isKaryawan(User $user): bool
    {
        return $this->hasRole($user, self::ROLE_KARYAWAN);
    }

    public function getDashboardRoute(User $user): string
    {
        return match ($user->role?->name) {
            self::ROLE_ADMIN => 'dashboard.admin',
            self::ROLE_HRD => 'dashboard.hrd',
            self::ROLE_MANAGER => 'dashboard.manager',
            self::ROLE_KARYAWAN => 'dashboard.karyawan',
            default => 'dashboard.karyawan',
        };
    }

    public function canAccessDashboard(User $user, string $dashboardType): bool
    {
        $allowedDashboard = match ($user->role?->name) {
            self::ROLE_ADMIN => self::ROLE_ADMIN,
            self::ROLE_HRD => self::ROLE_HRD,
            self::ROLE_MANAGER => self::ROLE_MANAGER,
            self::ROLE_KARYAWAN => self::ROLE_KARYAWAN,
            default => null,
        };

        return $allowedDashboard === $dashboardType;
    }
}
