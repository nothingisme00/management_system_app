<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Role Repository
 *
 * Eloquent implementation for Role data access operations.
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }

    public function findByNameOrFail(string $name): Role
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    public function getAllWithUserCount(): Collection
    {
        return $this->model->withCount('users')->get();
    }

    public function nameExists(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }

    public function getDefaultRole(): ?Role
    {
        // Default role for registration is "Karyawan"
        return $this->findByName('Karyawan');
    }
}
