<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;

beforeEach(function () {
    // Create roles for testing
    $this->adminRole = Role::factory()->create(['name' => 'Admin']);
    $this->hrdRole = Role::factory()->create(['name' => 'HRD']);

    // Create test repository
    $this->repository = new UserRepository(new User);
});

test('can find user by email', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'role_id' => $this->adminRole->id,
    ]);

    $found = $this->repository->findByEmail('test@example.com');

    expect($found)->not->toBeNull()
        ->and($found->email)->toBe('test@example.com')
        ->and($found->id)->toBe($user->id);
});

test('returns null when user email not found', function () {
    $found = $this->repository->findByEmail('nonexistent@example.com');

    expect($found)->toBeNull();
});

test('can check if email exists', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    expect($this->repository->emailExists('existing@example.com'))->toBeTrue()
        ->and($this->repository->emailExists('nonexistent@example.com'))->toBeFalse();
});

test('can get users by role', function () {
    User::factory()->count(3)->create(['role_id' => $this->adminRole->id]);
    User::factory()->count(2)->create(['role_id' => $this->hrdRole->id]);

    $admins = $this->repository->getUsersByRole($this->adminRole->id);
    $hrds = $this->repository->getUsersByRole($this->hrdRole->id);

    expect($admins)->toHaveCount(3)
        ->and($hrds)->toHaveCount(2);
});

test('can get users by role name', function () {
    User::factory()->count(3)->create(['role_id' => $this->adminRole->id]);

    $admins = $this->repository->getUsersByRoleName('Admin');

    expect($admins)->toHaveCount(3);
});

test('can get all users with roles', function () {
    User::factory()->count(5)->create(['role_id' => $this->adminRole->id]);

    $users = $this->repository->getAllWithRoles();

    expect($users)->toHaveCount(5)
        ->and($users->first()->role)->not->toBeNull();
});

test('can update last login timestamp', function () {
    $user = User::factory()->create(['role_id' => $this->adminRole->id]);

    expect($user->last_login_at)->toBeNull();

    $this->repository->updateLastLogin($user->id);

    $user->refresh();

    expect($user->last_login_at)->not->toBeNull();
});

test('can assign role to user', function () {
    $user = User::factory()->create(['role_id' => $this->adminRole->id]);

    expect($user->role_id)->toBe($this->adminRole->id);

    $this->repository->assignRole($user->id, $this->hrdRole->id);

    $user->refresh();

    expect($user->role_id)->toBe($this->hrdRole->id);
});

test('can find user with role relationship', function () {
    $user = User::factory()->create(['role_id' => $this->adminRole->id]);

    $found = $this->repository->findWithRole($user->id);

    expect($found)->not->toBeNull()
        ->and($found->role)->not->toBeNull()
        ->and($found->role->name)->toBe('Admin');
});

test('can create user', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
        'role_id' => $this->adminRole->id,
    ];

    $user = $this->repository->create($userData);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');
});

test('can update user', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'role_id' => $this->adminRole->id,
    ]);

    $updated = $this->repository->update($user->id, ['name' => 'Updated Name']);

    expect($updated)->toBeTrue();

    $user->refresh();

    expect($user->name)->toBe('Updated Name');
});

test('can delete user', function () {
    $user = User::factory()->create(['role_id' => $this->adminRole->id]);

    expect($this->repository->exists($user->id))->toBeTrue();

    $deleted = $this->repository->delete($user->id);

    expect($deleted)->toBeTrue()
        ->and($this->repository->exists($user->id))->toBeFalse();
});
