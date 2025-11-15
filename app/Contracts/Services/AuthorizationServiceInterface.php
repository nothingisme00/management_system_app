<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\User;

/**
 * Authorization Service Interface
 *
 * Defines authorization operations including role checks and permissions.
 */
interface AuthorizationServiceInterface
{
    /**
     * Check if user has specific role.
     */
    public function hasRole(User $user, string $roleName): bool;

    /**
     * Check if user is admin.
     */
    public function isAdmin(User $user): bool;

    /**
     * Check if user is HRD.
     */
    public function isHRD(User $user): bool;

    /**
     * Check if user is Manager.
     */
    public function isManager(User $user): bool;

    /**
     * Check if user is Karyawan.
     */
    public function isKaryawan(User $user): bool;

    /**
     * Get dashboard route for user's role.
     */
    public function getDashboardRoute(User $user): string;

    /**
     * Check if user can access specific dashboard.
     */
    public function canAccessDashboard(User $user, string $dashboardType): bool;
}
