<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Livewire\Component;

/**
 * SuperAdmin Dashboard Component
 *
 * Dashboard for SuperAdmin with full system overview.
 */
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'total_positions' => Position::count(),
            'active_employees' => Employee::where('employment_status', 'active')->count(),
        ];

        return view('livewire.super-admin.dashboard', [
            'stats' => $stats,
        ])->layout('layouts.app', ['title' => 'SuperAdmin Dashboard']);
    }
}
