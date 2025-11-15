<?php

use App\Models\Role;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users with role are redirected to their dashboard', function () {
    $role = Role::firstOrCreate(['name' => 'Karyawan']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('dashboard.karyawan'));
});

test('authenticated users without role get 403', function () {
    $user = User::factory()->create(['role_id' => null]);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertStatus(403);
});
