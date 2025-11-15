<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\DTOs\RegisterDTO;
use App\DTOs\UserDTO;
use App\Events\UserRegistered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * User Service
 *
 * Handles user management business logic.
 */
class UserService implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAllWithRoles();
    }

    public function getUserById(int $id): ?UserDTO
    {
        $user = $this->userRepository->findWithRole($id);

        return $user ? UserDTO::fromModel($user) : null;
    }

    public function getUserByEmail(string $email): ?UserDTO
    {
        $user = $this->userRepository->findByEmail($email);

        return $user ? UserDTO::fromModel($user) : null;
    }

    public function createUser(RegisterDTO $data): UserDTO
    {
        // Get role ID - use provided or default to Karyawan
        $roleId = $data->roleId;

        if ($roleId === null) {
            $defaultRole = $this->roleRepository->getDefaultRole();
            $roleId = $defaultRole?->id;
        }

        // Prepare user data
        $userData = [
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'role_id' => $roleId,
        ];

        // Create user
        $user = $this->userRepository->create($userData);

        // Fire registration event
        event(new UserRegistered($user));

        return UserDTO::fromModel($user);
    }

    public function updateUser(int $id, array $data): ?UserDTO
    {
        try {
            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $updated = $this->userRepository->update($id, $data);

            if (! $updated) {
                return null;
            }

            $user = $this->userRepository->findWithRole($id);

            return $user ? UserDTO::fromModel($user) : null;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            return $this->userRepository->delete($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }
    }

    public function getUsersByRole(string $roleName): Collection
    {
        return $this->userRepository->getUsersByRoleName($roleName);
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        return $this->userRepository->assignRole($userId, $roleId);
    }

    public function updateLastLogin(int $userId): bool
    {
        return $this->userRepository->updateLastLogin($userId);
    }

    public function emailExists(string $email): bool
    {
        return $this->userRepository->emailExists($email);
    }
}
