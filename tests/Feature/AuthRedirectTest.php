<?php

use App\Models\Role;
use App\Models\User;

test('authenticated admin cannot access login page and is redirected to admin dashboard', function () {
    $adminRole = Role::factory()->create(['name' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    $response = $this->actingAs($admin)->get('/login');

    $response->assertRedirect(route('dashboard.admin'));
});

test('authenticated hrd cannot access login page and is redirected to hrd dashboard', function () {
    $hrdRole = Role::factory()->create(['name' => 'HRD']);
    $hrd = User::factory()->create(['role_id' => $hrdRole->id]);

    $response = $this->actingAs($hrd)->get('/login');

    $response->assertRedirect(route('dashboard.hrd'));
});

test('authenticated manager cannot access login page and is redirected to manager dashboard', function () {
    $managerRole = Role::factory()->create(['name' => 'Manager']);
    $manager = User::factory()->create(['role_id' => $managerRole->id]);

    $response = $this->actingAs($manager)->get('/login');

    $response->assertRedirect(route('dashboard.manager'));
});

test('authenticated karyawan cannot access login page and is redirected to karyawan dashboard', function () {
    $karyawanRole = Role::factory()->create(['name' => 'Karyawan']);
    $karyawan = User::factory()->create(['role_id' => $karyawanRole->id]);

    $response = $this->actingAs($karyawan)->get('/login');

    $response->assertRedirect(route('dashboard.karyawan'));
});

test('unauthenticated users can access login page', function () {
    $response = $this->get('/login');

    $response->assertSuccessful();
});

test('unauthenticated users cannot access dashboard and are redirected to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});
