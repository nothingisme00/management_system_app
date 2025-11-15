<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Repository Interface
 *
 * Defines data access operations specific to User entity.
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by email or fail.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByEmailOrFail(string $email): User;

    /**
     * Get users by role.
     */
    public function getUsersByRole(int $roleId): Collection;

    /**
     * Get users by role name.
     */
    public function getUsersByRoleName(string $roleName): Collection;

    /**
     * Check if email exists.
     */
    public function emailExists(string $email): bool;

    /**
     * Get users with their roles.
     */
    public function getAllWithRoles(): Collection;

    /**
     * Update user's last login timestamp.
     */
    public function updateLastLogin(int $userId): bool;

    /**
     * Assign role to user.
     */
    public function assignRole(int $userId, int $roleId): bool;

    /**
     * Get user with role relationship loaded.
     */
    public function findWithRole(int $id): ?User;
}
