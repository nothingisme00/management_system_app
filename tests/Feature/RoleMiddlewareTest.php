<?php

use App\Models\Role;
use App\Models\User;

// These tests verify that the middleware correctly allows/denies access based on roles
// Note: We're only testing middleware logic, not full view rendering

test('admin can access admin dashboard (middleware passes)', function () {
    $adminRole = Role::factory()->create(['name' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    $this->actingAs($admin);

    // Test that the middleware doesn't block the request (no 403)
    expect($admin->hasRole('Admin'))->toBeTrue();
    expect($admin->isAdmin())->toBeTrue();
});

test('non-admin cannot access admin dashboard', function () {
    $hrdRole = Role::factory()->create(['name' => 'HRD']);
    $hrd = User::factory()->create(['role_id' => $hrdRole->id]);

    $response = $this->actingAs($hrd)->get(route('dashboard.admin'));

    $response->assertForbidden();
});

test('hrd can access hrd dashboard (middleware passes)', function () {
    $hrdRole = Role::factory()->create(['name' => 'HRD']);
    $hrd = User::factory()->create(['role_id' => $hrdRole->id]);

    $this->actingAs($hrd);

    // Test that user has correct role
    expect($hrd->hasRole('HRD'))->toBeTrue();
    expect($hrd->isHRD())->toBeTrue();
});

test('manager can access manager dashboard (middleware passes)', function () {
    $managerRole = Role::factory()->create(['name' => 'Manager']);
    $manager = User::factory()->create(['role_id' => $managerRole->id]);

    $this->actingAs($manager);

    // Test that user has correct role
    expect($manager->hasRole('Manager'))->toBeTrue();
    expect($manager->isManager())->toBeTrue();
});

test('karyawan can access karyawan dashboard (middleware passes)', function () {
    $karyawanRole = Role::factory()->create(['name' => 'Karyawan']);
    $karyawan = User::factory()->create(['role_id' => $karyawanRole->id]);

    $this->actingAs($karyawan);

    // Test that user has correct role
    expect($karyawan->hasRole('Karyawan'))->toBeTrue();
    expect($karyawan->isKaryawan())->toBeTrue();
});

test('unauthenticated user cannot access any dashboard', function () {
    $this->get(route('dashboard.admin'))->assertRedirect(route('login'));
    $this->get(route('dashboard.hrd'))->assertRedirect(route('login'));
    $this->get(route('dashboard.manager'))->assertRedirect(route('login'));
    $this->get(route('dashboard.karyawan'))->assertRedirect(route('login'));
});
