<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthenticationServiceInterface;
use App\DTOs\LoginDTO;
use App\DTOs\UserDTO;
use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Authentication Service
 *
 * Handles authentication business logic including login, logout, and rate limiting.
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function login(LoginDTO $credentials): UserDTO
    {
        // Check rate limiting
        $throttleKey = $this->generateThrottleKey($credentials->email, request()->ip());

        if ($this->isRateLimited($credentials->email, request()->ip())) {
            $seconds = $this->rateLimitSeconds($credentials->email, request()->ip());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Attempt authentication
        if (! Auth::attempt($credentials->credentials(), $credentials->shouldRemember())) {
            $this->hitRateLimit($credentials->email, request()->ip());

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        // Clear rate limit on successful login
        $this->clearRateLimit($credentials->email, request()->ip());

        // Regenerate session
        session()->regenerate();

        // Get authenticated user
        $user = Auth::user();

        // Update last login
        $this->userRepository->updateLastLogin($user->id);

        // Fire login event
        event(new UserLoggedIn($user));

        return UserDTO::fromModel($user);
    }

    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
    }

    public function isRateLimited(string $email, string $ip): bool
    {
        $key = $this->generateThrottleKey($email, $ip);

        return RateLimiter::tooManyAttempts($key, 5);
    }

    public function rateLimitSeconds(string $email, string $ip): int
    {
        $key = $this->generateThrottleKey($email, $ip);

        return RateLimiter::availableIn($key);
    }

    public function clearRateLimit(string $email, string $ip): void
    {
        $key = $this->generateThrottleKey($email, $ip);

        RateLimiter::clear($key);
    }

    public function hitRateLimit(string $email, string $ip): void
    {
        $key = $this->generateThrottleKey($email, $ip);

        RateLimiter::hit($key);
    }

    public function generateThrottleKey(string $email, string $ip): string
    {
        return Str::transliterate(Str::lower($email).'|'.$ip);
    }
}
