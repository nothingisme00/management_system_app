<?php

declare(strict_types=1);

use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTOs\LoginDTO;
use App\Events\UserLoggedIn;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    // Create roles for testing
    $this->role = Role::factory()->create(['name' => 'Admin']);

    // Create test user with known password
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role_id' => $this->role->id,
    ]);

    // Create service instance
    $this->service = app(AuthenticationService::class);
    $this->repository = app(UserRepositoryInterface::class);
});

test('can login with valid credentials', function () {
    Event::fake();

    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'password',
        remember: false
    );

    $userDTO = $this->service->login($credentials);

    expect($userDTO->id)->toBe($this->user->id)
        ->and($userDTO->email)->toBe('test@example.com')
        ->and(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($this->user->id);

    Event::assertDispatched(UserLoggedIn::class, function ($event) {
        return $event->user->id === $this->user->id;
    });
});

test('can login with remember me enabled', function () {
    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'password',
        remember: true
    );

    $userDTO = $this->service->login($credentials);

    expect($userDTO->id)->toBe($this->user->id)
        ->and(Auth::check())->toBeTrue()
        ->and(Auth::viaRemember())->toBeFalse(); // Will be true after next request
});

test('login fails with invalid credentials', function () {
    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'wrong-password',
        remember: false
    );

    expect(fn () => $this->service->login($credentials))
        ->toThrow(ValidationException::class);

    expect(Auth::check())->toBeFalse();
});

test('login fails with non-existent email', function () {
    $credentials = new LoginDTO(
        email: 'nonexistent@example.com',
        password: 'password',
        remember: false
    );

    expect(fn () => $this->service->login($credentials))
        ->toThrow(ValidationException::class);
});

test('login updates last login timestamp', function () {
    expect($this->user->last_login_at)->toBeNull();

    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'password',
        remember: false
    );

    $this->service->login($credentials);

    $this->user->refresh();

    expect($this->user->last_login_at)->not->toBeNull();
});

test('login regenerates session', function () {
    $oldSessionId = session()->getId();

    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'password',
        remember: false
    );

    $this->service->login($credentials);

    expect(session()->getId())->not->toBe($oldSessionId);
});

test('rate limiting kicks in after failed attempts', function () {
    RateLimiter::clear($this->service->generateThrottleKey('test@example.com', request()->ip()));

    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'wrong-password',
        remember: false
    );

    // First 4 attempts should fail with validation error
    for ($i = 0; $i < 4; $i++) {
        try {
            $this->service->login($credentials);
        } catch (ValidationException $e) {
            expect($e->errors())->toHaveKey('email');
        }
    }

    // 5th attempt should be rate limited
    expect(fn () => $this->service->login($credentials))
        ->toThrow(ValidationException::class);

    expect($this->service->isRateLimited('test@example.com', request()->ip()))
        ->toBeTrue();
});

test('rate limit is cleared after successful login', function () {
    // Hit rate limit a few times (but not enough to trigger full rate limiting)
    for ($i = 0; $i < 3; $i++) {
        $this->service->hitRateLimit('test@example.com', request()->ip());
    }

    // Successful login should clear it
    $credentials = new LoginDTO(
        email: 'test@example.com',
        password: 'password',
        remember: false
    );

    $this->service->login($credentials);

    // Verify rate limit was cleared (we can hit it 5 more times before being rate limited)
    for ($i = 0; $i < 4; $i++) {
        $this->service->hitRateLimit('test@example.com', request()->ip());
    }

    expect($this->service->isRateLimited('test@example.com', request()->ip()))
        ->toBeFalse();
});

test('can check rate limit status', function () {
    expect($this->service->isRateLimited('test@example.com', request()->ip()))
        ->toBeFalse();

    for ($i = 0; $i < 5; $i++) {
        $this->service->hitRateLimit('test@example.com', request()->ip());
    }

    expect($this->service->isRateLimited('test@example.com', request()->ip()))
        ->toBeTrue();
});

test('can get rate limit seconds remaining', function () {
    $this->service->hitRateLimit('test@example.com', request()->ip());

    $seconds = $this->service->rateLimitSeconds('test@example.com', request()->ip());

    expect($seconds)->toBeGreaterThan(0)
        ->and($seconds)->toBeLessThanOrEqual(60);
});

test('can manually clear rate limit', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->service->hitRateLimit('test@example.com', request()->ip());
    }
    expect($this->service->isRateLimited('test@example.com', request()->ip()))->toBeTrue();

    $this->service->clearRateLimit('test@example.com', request()->ip());

    expect($this->service->isRateLimited('test@example.com', request()->ip()))
        ->toBeFalse();
});

test('logout clears authentication', function () {
    // Login first
    Auth::login($this->user);
    expect(Auth::check())->toBeTrue();

    $this->service->logout();

    expect(Auth::check())->toBeFalse();
});

test('logout regenerates session token', function () {
    Auth::login($this->user);
    $oldToken = session()->token();

    $this->service->logout();

    expect(session()->token())->not->toBe($oldToken);
});
