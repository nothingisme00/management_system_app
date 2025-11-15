<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use App\Services\AuthorizationService;

beforeEach(function () {
    // Create all roles
    $this->adminRole = Role::factory()->create(['name' => 'Admin']);
    $this->hrdRole = Role::factory()->create(['name' => 'HRD']);
    $this->managerRole = Role::factory()->create(['name' => 'Manager']);
    $this->karyawanRole = Role::factory()->create(['name' => 'Karyawan']);

    // Create users with different roles
    $this->adminUser = User::factory()->create(['role_id' => $this->adminRole->id]);
    $this->hrdUser = User::factory()->create(['role_id' => $this->hrdRole->id]);
    $this->managerUser = User::factory()->create(['role_id' => $this->managerRole->id]);
    $this->karyawanUser = User::factory()->create(['role_id' => $this->karyawanRole->id]);

    // Create service instance
    $this->service = app(AuthorizationService::class);
});

test('can check if user has specific role', function () {
    expect($this->service->hasRole($this->adminUser, 'Admin'))->toBeTrue()
        ->and($this->service->hasRole($this->adminUser, 'HRD'))->toBeFalse()
        ->and($this->service->hasRole($this->hrdUser, 'HRD'))->toBeTrue()
        ->and($this->service->hasRole($this->hrdUser, 'Admin'))->toBeFalse();
});

test('has role returns false for user without role', function () {
    $userWithoutRole = User::factory()->create(['role_id' => null]);

    expect($this->service->hasRole($userWithoutRole, 'Admin'))->toBeFalse();
});

test('can check if user is admin', function () {
    expect($this->service->isAdmin($this->adminUser))->toBeTrue()
        ->and($this->service->isAdmin($this->hrdUser))->toBeFalse()
        ->and($this->service->isAdmin($this->managerUser))->toBeFalse()
        ->and($this->service->isAdmin($this->karyawanUser))->toBeFalse();
});

test('can check if user is HRD', function () {
    expect($this->service->isHRD($this->hrdUser))->toBeTrue()
        ->and($this->service->isHRD($this->adminUser))->toBeFalse()
        ->and($this->service->isHRD($this->managerUser))->toBeFalse()
        ->and($this->service->isHRD($this->karyawanUser))->toBeFalse();
});

test('can check if user is Manager', function () {
    expect($this->service->isManager($this->managerUser))->toBeTrue()
        ->and($this->service->isManager($this->adminUser))->toBeFalse()
        ->and($this->service->isManager($this->hrdUser))->toBeFalse()
        ->and($this->service->isManager($this->karyawanUser))->toBeFalse();
});

test('can check if user is Karyawan', function () {
    expect($this->service->isKaryawan($this->karyawanUser))->toBeTrue()
        ->and($this->service->isKaryawan($this->adminUser))->toBeFalse()
        ->and($this->service->isKaryawan($this->hrdUser))->toBeFalse()
        ->and($this->service->isKaryawan($this->managerUser))->toBeFalse();
});

test('returns correct dashboard route for Admin', function () {
    $route = $this->service->getDashboardRoute($this->adminUser);

    expect($route)->toBe('dashboard.admin');
});

test('returns correct dashboard route for HRD', function () {
    $route = $this->service->getDashboardRoute($this->hrdUser);

    expect($route)->toBe('dashboard.hrd');
});

test('returns correct dashboard route for Manager', function () {
    $route = $this->service->getDashboardRoute($this->managerUser);

    expect($route)->toBe('dashboard.manager');
});

test('returns correct dashboard route for Karyawan', function () {
    $route = $this->service->getDashboardRoute($this->karyawanUser);

    expect($route)->toBe('dashboard.karyawan');
});

test('returns default dashboard route for user without role', function () {
    $userWithoutRole = User::factory()->create(['role_id' => null]);

    $route = $this->service->getDashboardRoute($userWithoutRole);

    expect($route)->toBe('dashboard.karyawan');
});

test('admin can access admin dashboard', function () {
    expect($this->service->canAccessDashboard($this->adminUser, 'Admin'))->toBeTrue();
});

test('admin cannot access HRD dashboard', function () {
    expect($this->service->canAccessDashboard($this->adminUser, 'HRD'))->toBeFalse();
});

test('HRD can access HRD dashboard', function () {
    expect($this->service->canAccessDashboard($this->hrdUser, 'HRD'))->toBeTrue();
});

test('HRD cannot access admin dashboard', function () {
    expect($this->service->canAccessDashboard($this->hrdUser, 'Admin'))->toBeFalse();
});

test('manager can access manager dashboard', function () {
    expect($this->service->canAccessDashboard($this->managerUser, 'Manager'))->toBeTrue();
});

test('karyawan can access karyawan dashboard', function () {
    expect($this->service->canAccessDashboard($this->karyawanUser, 'Karyawan'))->toBeTrue();
});

test('karyawan cannot access manager dashboard', function () {
    expect($this->service->canAccessDashboard($this->karyawanUser, 'Manager'))->toBeFalse();
});

test('role checks are case sensitive', function () {
    expect($this->service->hasRole($this->adminUser, 'admin'))->toBeFalse()
        ->and($this->service->hasRole($this->adminUser, 'Admin'))->toBeTrue();
});
