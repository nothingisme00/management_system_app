<?php

declare(strict_types=1);

use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTOs\RegisterDTO;
use App\Events\UserRegistered;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    // Create roles for testing
    $this->adminRole = Role::factory()->create(['name' => 'Admin']);
    $this->hrdRole = Role::factory()->create(['name' => 'HRD']);
    $this->karyawanRole = Role::factory()->create(['name' => 'Karyawan']);

    // Create service instance
    $this->service = app(UserService::class);
    $this->repository = app(UserRepositoryInterface::class);
    $this->roleRepository = app(RoleRepositoryInterface::class);
});

test('can create user with default role', function () {
    Event::fake();

    $registerData = new RegisterDTO(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'password123',
        roleId: null
    );

    $userDTO = $this->service->createUser($registerData);

    expect($userDTO->name)->toBe('John Doe')
        ->and($userDTO->email)->toBe('john@example.com')
        ->and($userDTO->roleName)->toBe('Karyawan');

    // Verify user exists in database
    $user = User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->role_id)->toBe($this->karyawanRole->id);

    Event::assertDispatched(UserRegistered::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});

test('can create user with specific role', function () {
    Event::fake();

    $registerData = new RegisterDTO(
        name: 'Jane Admin',
        email: 'jane@example.com',
        password: 'password123',
        roleId: $this->adminRole->id
    );

    $userDTO = $this->service->createUser($registerData);

    expect($userDTO->name)->toBe('Jane Admin')
        ->and($userDTO->email)->toBe('jane@example.com')
        ->and($userDTO->roleName)->toBe('Admin')
        ->and($userDTO->roleId)->toBe($this->adminRole->id);

    Event::assertDispatched(UserRegistered::class);
});

test('password is hashed when creating user', function () {
    $registerData = new RegisterDTO(
        name: 'Test User',
        email: 'test@example.com',
        password: 'plain-password',
        roleId: $this->karyawanRole->id
    );

    $userDTO = $this->service->createUser($registerData);

    $user = User::find($userDTO->id);

    expect($user->password)->not->toBe('plain-password')
        ->and(Hash::check('plain-password', $user->password))->toBeTrue();
});

test('fires user registered event when creating user', function () {
    Event::fake();

    $registerData = new RegisterDTO(
        name: 'Event Test',
        email: 'event@example.com',
        password: 'password',
        roleId: null
    );

    $this->service->createUser($registerData);

    Event::assertDispatched(UserRegistered::class);
});

test('can get all users', function () {
    User::factory()->count(5)->create(['role_id' => $this->karyawanRole->id]);

    $users = $this->service->getAllUsers();

    expect($users)->toHaveCount(5)
        ->and($users->first())->toHaveKey('id')
        ->and($users->first())->toHaveKey('name')
        ->and($users->first())->toHaveKey('email');
});

test('can get user by id', function () {
    $user = User::factory()->create([
        'name' => 'Find Me',
        'email' => 'findme@example.com',
        'role_id' => $this->adminRole->id,
    ]);

    $userDTO = $this->service->getUserById($user->id);

    expect($userDTO)->not->toBeNull()
        ->and($userDTO->id)->toBe($user->id)
        ->and($userDTO->name)->toBe('Find Me')
        ->and($userDTO->email)->toBe('findme@example.com')
        ->and($userDTO->roleName)->toBe('Admin');
});

test('get user by id returns null for non-existent user', function () {
    $userDTO = $this->service->getUserById(99999);

    expect($userDTO)->toBeNull();
});

test('can update user name', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'user@example.com',
        'role_id' => $this->karyawanRole->id,
    ]);

    $updated = $this->service->updateUser($user->id, [
        'name' => 'Updated Name',
    ]);

    expect($updated)->not->toBeNull()
        ->and($updated)->toBeInstanceOf(\App\DTOs\UserDTO::class)
        ->and($updated->name)->toBe('Updated Name')
        ->and($updated->email)->toBe('user@example.com');
});

test('can update user email', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'old@example.com',
        'role_id' => $this->karyawanRole->id,
    ]);

    $updated = $this->service->updateUser($user->id, [
        'email' => 'new@example.com',
    ]);

    expect($updated)->not->toBeNull()
        ->and($updated->email)->toBe('new@example.com');
});

test('can update user password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
        'role_id' => $this->karyawanRole->id,
    ]);

    $updated = $this->service->updateUser($user->id, [
        'password' => 'new-password',
    ]);

    expect($updated)->not->toBeNull();

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue()
        ->and(Hash::check('old-password', $user->password))->toBeFalse();
});

test('password is hashed when updating user', function () {
    $user = User::factory()->create(['role_id' => $this->karyawanRole->id]);

    $this->service->updateUser($user->id, [
        'password' => 'plain-new-password',
    ]);

    $user->refresh();

    expect($user->password)->not->toBe('plain-new-password')
        ->and(Hash::check('plain-new-password', $user->password))->toBeTrue();
});

test('can update user role', function () {
    $user = User::factory()->create(['role_id' => $this->karyawanRole->id]);

    expect($user->role_id)->toBe($this->karyawanRole->id);

    $updated = $this->service->updateUser($user->id, [
        'role_id' => $this->adminRole->id,
    ]);

    expect($updated)->not->toBeNull()
        ->and($updated->roleId)->toBe($this->adminRole->id);
});

test('update returns null for non-existent user', function () {
    $updated = $this->service->updateUser(99999, [
        'name' => 'Should Fail',
    ]);

    expect($updated)->toBeNull();
});

test('can delete user', function () {
    $user = User::factory()->create(['role_id' => $this->karyawanRole->id]);

    expect($this->repository->exists($user->id))->toBeTrue();

    $deleted = $this->service->deleteUser($user->id);

    expect($deleted)->toBeTrue()
        ->and($this->repository->exists($user->id))->toBeFalse();
});

test('delete returns false for non-existent user', function () {
    $deleted = $this->service->deleteUser(99999);

    expect($deleted)->toBeFalse();
});

test('can get users by role', function () {
    User::factory()->count(3)->create(['role_id' => $this->adminRole->id]);
    User::factory()->count(2)->create(['role_id' => $this->hrdRole->id]);

    $admins = $this->service->getUsersByRole('Admin');
    $hrds = $this->service->getUsersByRole('HRD');

    expect($admins)->toHaveCount(3)
        ->and($hrds)->toHaveCount(2);
});

test('can check if email exists', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    expect($this->service->emailExists('existing@example.com'))->toBeTrue()
        ->and($this->service->emailExists('nonexistent@example.com'))->toBeFalse();
});

test('create user returns DTO with all fields', function () {
    $registerData = new RegisterDTO(
        name: 'Complete User',
        email: 'complete@example.com',
        password: 'password123',
        roleId: $this->adminRole->id
    );

    $userDTO = $this->service->createUser($registerData);

    expect($userDTO)->toHaveProperty('id')
        ->and($userDTO)->toHaveProperty('name')
        ->and($userDTO)->toHaveProperty('email')
        ->and($userDTO)->toHaveProperty('roleId')
        ->and($userDTO)->toHaveProperty('roleName')
        ->and($userDTO->id)->toBeGreaterThan(0)
        ->and($userDTO->name)->toBe('Complete User')
        ->and($userDTO->email)->toBe('complete@example.com')
        ->and($userDTO->roleId)->toBe($this->adminRole->id)
        ->and($userDTO->roleName)->toBe('Admin');
});
