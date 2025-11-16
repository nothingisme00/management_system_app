<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Employees;

use App\Contracts\Services\DepartmentServiceInterface;
use App\Contracts\Services\EmployeeServiceInterface;
use App\Contracts\Services\PositionServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Employees Index Component
 *
 * Displays list of all employees with search and filter functionality.
 * Uses METHOD INJECTION for service dependencies.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public ?int $departmentFilter = null;

    public ?int $positionFilter = null;

    public string $sortField = 'userName';

    public string $sortDirection = 'asc';

    public ?int $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'departmentFilter' => ['except' => null],
        'positionFilter' => ['except' => null],
        'sortField' => ['except' => 'userName'],
        'sortDirection' => ['except' => 'asc'],
    ];

    /**
     * Reset pagination when search/filter changes.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when filter changes.
     */
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when department filter changes.
     */
    public function updatingDepartmentFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when position filter changes.
     */
    public function updatingPositionFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by field.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Confirm employee deletion.
     */
    public function confirmDelete(int $employeeId): void
    {
        $this->deleteId = $employeeId;
    }

    /**
     * Cancel deletion.
     */
    public function cancelDelete(): void
    {
        $this->deleteId = null;
    }

    /**
     * Delete employee.
     */
    public function delete(EmployeeServiceInterface $employeeService): void
    {
        if ($this->deleteId === null) {
            return;
        }

        $deleted = $employeeService->deleteEmployee($this->deleteId);

        if ($deleted) {
            session()->flash('success', 'Employee deleted successfully.');
        } else {
            session()->flash('error', 'Failed to delete employee.');
        }

        $this->deleteId = null;
        $this->resetPage();
    }

    /**
     * Render component.
     */
    public function render(
        EmployeeServiceInterface $employeeService,
        DepartmentServiceInterface $departmentService,
        PositionServiceInterface $positionService
    ) {
        $employees = collect($employeeService->getAllEmployees());

        // Apply search filter
        if ($this->search !== '') {
            $employees = $employees->filter(function ($employee) {
                $searchLower = strtolower($this->search);

                return str_contains(strtolower($employee->userName), $searchLower)
                    || str_contains(strtolower($employee->userEmail), $searchLower)
                    || str_contains(strtolower($employee->employeeId), $searchLower)
                    || str_contains(strtolower($employee->departmentName ?? ''), $searchLower)
                    || str_contains(strtolower($employee->positionName ?? ''), $searchLower);
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $employees = $employees->filter(function ($employee) {
                return $employee->employmentStatus === $this->statusFilter;
            });
        }

        // Apply department filter
        if ($this->departmentFilter !== null) {
            $employees = $employees->filter(function ($employee) {
                return $employee->departmentId === $this->departmentFilter;
            });
        }

        // Apply position filter
        if ($this->positionFilter !== null) {
            $employees = $employees->filter(function ($employee) {
                return $employee->positionId === $this->positionFilter;
            });
        }

        // Apply sorting
        if ($this->sortDirection === 'asc') {
            $employees = $employees->sortBy($this->sortField)->values();
        } else {
            $employees = $employees->sortByDesc($this->sortField)->values();
        }

        // Paginate manually
        $perPage = 10;
        $currentPage = $this->getPage();
        $total = $employees->count();
        $employees = $employees->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Get departments and positions for filters
        $departments = collect($departmentService->getActiveDepartments());
        $positions = collect($positionService->getActivePositions());

        return view('livewire.admin.employees.index', [
            'employees' => $employees,
            'total' => $total,
            'perPage' => $perPage,
            'departments' => $departments,
            'positions' => $positions,
        ])->layout('layouts.app', ['title' => 'Employees Management']);
    }
}
