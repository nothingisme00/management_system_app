<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;

beforeEach(function () {
    // Create test repository
    $this->repository = new RoleRepository(new Role);
});

test('can find role by name', function () {
    $role = Role::factory()->create(['name' => 'Admin']);

    $found = $this->repository->findByName('Admin');

    expect($found)->not->toBeNull()
        ->and($found->name)->toBe('Admin')
        ->and($found->id)->toBe($role->id);
});

test('returns null when role name not found', function () {
    $found = $this->repository->findByName('NonExistent');

    expect($found)->toBeNull();
});

test('can get default role', function () {
    Role::factory()->create(['name' => 'Admin']);
    $karyawan = Role::factory()->create(['name' => 'Karyawan']);

    $defaultRole = $this->repository->getDefaultRole();

    expect($defaultRole)->not->toBeNull()
        ->and($defaultRole->name)->toBe('Karyawan')
        ->and($defaultRole->id)->toBe($karyawan->id);
});

test('returns null when default role does not exist', function () {
    Role::factory()->create(['name' => 'Admin']);

    $defaultRole = $this->repository->getDefaultRole();

    expect($defaultRole)->toBeNull();
});

test('can get all roles with user count', function () {
    $adminRole = Role::factory()->create(['name' => 'Admin']);
    $hrdRole = Role::factory()->create(['name' => 'HRD']);

    User::factory()->count(3)->create(['role_id' => $adminRole->id]);
    User::factory()->count(2)->create(['role_id' => $hrdRole->id]);

    $roles = $this->repository->getAllWithUserCount();

    expect($roles)->toHaveCount(2);

    $admin = $roles->firstWhere('name', 'Admin');
    $hrd = $roles->firstWhere('name', 'HRD');

    expect($admin->users_count)->toBe(3)
        ->and($hrd->users_count)->toBe(2);
});

test('can get all roles', function () {
    Role::factory()->count(4)->create();

    $roles = $this->repository->all();

    expect($roles)->toHaveCount(4);
});

test('can create role', function () {
    $role = $this->repository->create([
        'name' => 'New Role',
    ]);

    expect($role)->toBeInstanceOf(Role::class)
        ->and($role->name)->toBe('New Role');

    $exists = Role::where('name', 'New Role')->exists();
    expect($exists)->toBeTrue();
});

test('can update role', function () {
    $role = Role::factory()->create(['name' => 'Original Name']);

    $updated = $this->repository->update($role->id, ['name' => 'Updated Name']);

    expect($updated)->toBeTrue();

    $role->refresh();

    expect($role->name)->toBe('Updated Name');
});

test('can delete role', function () {
    $role = Role::factory()->create(['name' => 'To Delete']);

    expect($this->repository->exists($role->id))->toBeTrue();

    $deleted = $this->repository->delete($role->id);

    expect($deleted)->toBeTrue()
        ->and($this->repository->exists($role->id))->toBeFalse();
});

test('can check if role exists', function () {
    $role = Role::factory()->create();

    expect($this->repository->exists($role->id))->toBeTrue()
        ->and($this->repository->exists(99999))->toBeFalse();
});

test('can count roles', function () {
    Role::factory()->count(5)->create();

    $count = $this->repository->count();

    expect($count)->toBe(5);
});

test('can find role by id', function () {
    $role = Role::factory()->create(['name' => 'Manager']);

    $found = $this->repository->find($role->id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($role->id)
        ->and($found->name)->toBe('Manager');
});

test('find returns null for non-existent id', function () {
    $found = $this->repository->find(99999);

    expect($found)->toBeNull();
});

test('find or fail returns role for valid id', function () {
    $role = Role::factory()->create(['name' => 'Test Role']);

    $found = $this->repository->findOrFail($role->id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($role->id);
});

test('find or fail throws exception for invalid id', function () {
    expect(fn () => $this->repository->findOrFail(99999))
        ->toThrow(Illuminate\Database\Eloquent\ModelNotFoundException::class);
});
