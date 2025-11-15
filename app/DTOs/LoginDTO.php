<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Login Data Transfer Object
 *
 * Encapsulates login credentials data.
 */
final readonly class LoginDTO extends BaseDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {
    }

    /**
     * Create from request data.
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
        );
    }

    /**
     * Get credentials array for Auth::attempt().
     */
    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Should remember user.
     */
    public function shouldRemember(): bool
    {
        return $this->remember;
    }
}
