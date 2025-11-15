<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Repository
 *
 * Eloquent implementation for User data access operations.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmailOrFail(string $email): User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    public function getUsersByRole(int $roleId): Collection
    {
        return $this->model->where('role_id', $roleId)->get();
    }

    public function getUsersByRoleName(string $roleName): Collection
    {
        return $this->model->whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }

    public function emailExists(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function getAllWithRoles(): Collection
    {
        return $this->model->with('role')->get();
    }

    public function updateLastLogin(int $userId): bool
    {
        return (bool) $this->model
            ->where('id', $userId)
            ->update(['last_login_at' => now()]);
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        return (bool) $this->model
            ->where('id', $userId)
            ->update(['role_id' => $roleId]);
    }

    public function findWithRole(int $id): ?User
    {
        return $this->model->with('role')->find($id);
    }
}
