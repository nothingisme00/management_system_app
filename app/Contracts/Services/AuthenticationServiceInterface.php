<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\LoginDTO;
use App\DTOs\UserDTO;

/**
 * Authentication Service Interface
 *
 * Defines authentication operations including login, logout, and rate limiting.
 */
interface AuthenticationServiceInterface
{
    /**
     * Authenticate user with credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginDTO $credentials): UserDTO;

    /**
     * Logout currently authenticated user.
     */
    public function logout(): void;

    /**
     * Check if login attempts are rate limited.
     */
    public function isRateLimited(string $email, string $ip): bool;

    /**
     * Get remaining seconds for rate limit.
     */
    public function rateLimitSeconds(string $email, string $ip): int;

    /**
     * Clear rate limit for successful login.
     */
    public function clearRateLimit(string $email, string $ip): void;

    /**
     * Hit rate limiter for failed login.
     */
    public function hitRateLimit(string $email, string $ip): void;

    /**
     * Generate throttle key for rate limiting.
     */
    public function generateThrottleKey(string $email, string $ip): string;
}
