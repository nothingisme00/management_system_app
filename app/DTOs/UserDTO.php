<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\User;

/**
 * User Data Transfer Object
 *
 * Encapsulates user data for transfer between application layers.
 */
final readonly class UserDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?int $roleId,
        public ?string $roleName,
        public ?string $createdAt,
        public ?string $lastLoginAt = null,
    ) {
    }

    /**
     * Create from User model.
     */
    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            roleId: $user->role_id,
            roleName: $user->role?->name,
            createdAt: $user->created_at?->toIso8601String(),
            lastLoginAt: $user->last_login_at?->toIso8601String(),
        );
    }

    /**
     * Create collection from multiple User models.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<User>  $users
     * @return array<self>
     */
    public static function collection($users): array
    {
        return $users->map(fn (User $user) => self::fromModel($user))->all();
    }

    /**
     * Get user's initials for avatar.
     */
    public function initials(): string
    {
        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Check if user has specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roleName === $roleName;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is HRD.
     */
    public function isHRD(): bool
    {
        return $this->hasRole('HRD');
    }

    /**
     * Check if user is Manager.
     */
    public function isManager(): bool
    {
        return $this->hasRole('Manager');
    }

    /**
     * Check if user is Karyawan.
     */
    public function isKaryawan(): bool
    {
        return $this->hasRole('Karyawan');
    }
}
