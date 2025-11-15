<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Role Repository Interface
 *
 * Defines data access operations specific to Role entity.
 */
interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find role by name.
     */
    public function findByName(string $name): ?Role;

    /**
     * Find role by name or fail.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByNameOrFail(string $name): Role;

    /**
     * Get all roles with user count.
     */
    public function getAllWithUserCount(): Collection;

    /**
     * Check if role name exists.
     */
    public function nameExists(string $name): bool;

    /**
     * Get default role for registration.
     */
    public function getDefaultRole(): ?Role;
}
