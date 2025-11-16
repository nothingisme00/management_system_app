<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

/**
 * Users Edit Component
 *
 * Edit existing user with self-edit protection (SuperAdmin only).
 */
class Edit extends Component
{
    public int $userId;

    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    public string $newPassword = '';

    public bool $isSelfEdit = false;

    public function mount(int $user)
    {
        $this->userId = $user;
        $userData = User::find($user);

        if (! $userData) {
            abort(404);
        }

        // Check if editing own account
        $this->isSelfEdit = ($userData->id === auth()->id());

        $this->name = $userData->name;
        $this->email = $userData->email;
        $this->role_id = $userData->role_id;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->userId],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'newPassword' => ['nullable', 'string', 'min:8'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role_id.required' => 'Role is required.',
            'newPassword.min' => 'Password must be at least 8 characters.',
        ];
    }

    public function update()
    {
        $this->validate();

        $user = User::find($this->userId);

        if (! $user) {
            session()->flash('error', 'User not found.');

            return $this->redirect(route('users.index'), navigate: true);
        }

        // Update user data
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
        ]);

        // Update password if provided
        if ($this->newPassword !== '') {
            $user->update([
                'password' => Hash::make($this->newPassword),
            ]);
        }

        session()->flash('success', 'User updated successfully.');

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function resetPasswordToDefault()
    {
        $user = User::find($this->userId);

        if (! $user) {
            session()->flash('error', 'User not found.');

            return;
        }

        $user->update([
            'password' => Hash::make('pass1234'),
        ]);

        session()->flash('success', 'Password reset to default (pass1234) successfully.');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.super-admin.users.edit', [
            'roles' => $roles,
        ])->layout('layouts.app', ['title' => 'Edit User']);
    }
}
