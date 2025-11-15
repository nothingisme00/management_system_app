<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\RegisterDTO;
use App\DTOs\UserDTO;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Service Interface
 *
 * Defines user management operations.
 */
interface UserServiceInterface
{
    /**
     * Get all users.
     */
    public function getAllUsers(): Collection;

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): ?UserDTO;

    /**
     * Get user by email.
     */
    public function getUserByEmail(string $email): ?UserDTO;

    /**
     * Create new user.
     */
    public function createUser(RegisterDTO $data): UserDTO;

    /**
     * Update user.
     */
    public function updateUser(int $id, array $data): UserDTO;

    /**
     * Delete user.
     */
    public function deleteUser(int $id): bool;

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $roleName): Collection;

    /**
     * Assign role to user.
     */
    public function assignRole(int $userId, int $roleId): bool;

    /**
     * Update user's last login timestamp.
     */
    public function updateLastLogin(int $userId): bool;

    /**
     * Check if email already exists.
     */
    public function emailExists(string $email): bool;
}
