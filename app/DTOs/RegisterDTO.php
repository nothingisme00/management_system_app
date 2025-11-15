<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Register Data Transfer Object
 *
 * Encapsulates user registration data.
 */
final readonly class RegisterDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?int $roleId = null,
    ) {
    }

    /**
     * Create from request data.
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            roleId: $data['role_id'] ?? null,
        );
    }

    /**
     * Convert to array for model creation.
     */
    public function toModelData(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password, // Will be hashed in service layer
        ];

        if ($this->roleId !== null) {
            $data['role_id'] = $this->roleId;
        }

        return $data;
    }
}
