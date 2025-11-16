<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

/**
 * Users Create Component
 *
 * Create new system user (SuperAdmin only).
 */
class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = 'pass1234';

    public ?int $role_id = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'role_id.required' => 'Role is required.',
        ];
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'email_verified_at' => now(),
        ]);

        session()->flash('success', 'User created successfully.');

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.super-admin.users.create', [
            'roles' => $roles,
        ])->layout('layouts.app', ['title' => 'Create User']);
    }
}
