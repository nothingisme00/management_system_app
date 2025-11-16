<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Users Index Component
 *
 * List all users with search and filtering (SuperAdmin only).
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $roleFilter = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $user = User::find($id);

        if (! $user) {
            session()->flash('error', 'User not found.');

            return;
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');

            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::query()
            ->with('role')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('role', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.super-admin.users.index', [
            'users' => $users,
        ])->layout('layouts.app', ['title' => 'User Management']);
    }
}
