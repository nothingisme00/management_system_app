<?php

use App\Models\Role;
use App\Models\User;

test('admin can access admin dashboard', function () {
    $adminRole = Role::factory()->create(['name' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    $response = $this->actingAs($admin)->get(route('dashboard.admin'));

    $response->assertStatus(200);
});

test('non-admin cannot access admin dashboard', function () {
    $hrdRole = Role::factory()->create(['name' => 'HRD']);
    $hrd = User::factory()->create(['role_id' => $hrdRole->id]);

    $response = $this->actingAs($hrd)->get(route('dashboard.admin'));

    $response->assertForbidden();
});

test('hrd can access hrd dashboard', function () {
    $hrdRole = Role::factory()->create(['name' => 'HRD']);
    $hrd = User::factory()->create(['role_id' => $hrdRole->id]);

    $response = $this->actingAs($hrd)->get(route('dashboard.hrd'));

    $response->assertStatus(200);
});

test('manager can access manager dashboard', function () {
    $managerRole = Role::factory()->create(['name' => 'Manager']);
    $manager = User::factory()->create(['role_id' => $managerRole->id]);

    $response = $this->actingAs($manager)->get(route('dashboard.manager'));

    $response->assertStatus(200);
});

test('karyawan can access karyawan dashboard', function () {
    $karyawanRole = Role::factory()->create(['name' => 'Karyawan']);
    $karyawan = User::factory()->create(['role_id' => $karyawanRole->id]);

    $response = $this->actingAs($karyawan)->get(route('dashboard.karyawan'));

    $response->assertStatus(200);
});

test('unauthenticated user cannot access any dashboard', function () {
    $this->get(route('dashboard.admin'))->assertRedirect(route('login'));
    $this->get(route('dashboard.hrd'))->assertRedirect(route('login'));
    $this->get(route('dashboard.manager'))->assertRedirect(route('login'));
    $this->get(route('dashboard.karyawan'))->assertRedirect(route('login'));
});
