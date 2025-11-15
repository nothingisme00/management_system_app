<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Employees;

use App\Contracts\Services\DepartmentServiceInterface;
use App\Contracts\Services\EmployeeServiceInterface;
use App\Contracts\Services\PositionServiceInterface;
use App\DTOs\CreateEmployeeDTO;
use App\Models\Role;
use Livewire\Component;

/**
 * Employee Create Component
 *
 * Form for creating new employees with auto-generated Employee ID.
 * Uses METHOD INJECTION for service dependencies.
 */
class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    public ?int $department_id = null;

    public ?int $position_id = null;

    public string $phone_number = '';

    public string $address = '';

    public string $join_date = '';

    public string $employment_status = 'active';

    /**
     * Mount component with today's date as default join date.
     */
    public function mount(): void
    {
        $this->join_date = now()->format('Y-m-d');
    }

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'join_date' => ['required', 'date', 'before_or_equal:today'],
            'employment_status' => ['required', 'string', 'in:active,inactive,on_leave,terminated'],
        ];
    }

    /**
     * Custom validation messages.
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role_id.required' => 'Role is required.',
            'department_id.required' => 'Department is required.',
            'join_date.required' => 'Join date is required.',
            'join_date.before_or_equal' => 'Join date cannot be in the future.',
        ];
    }

    /**
     * Save employee.
     */
    public function save(EmployeeServiceInterface $employeeService): void
    {
        $this->validate();

        $dto = new CreateEmployeeDTO(
            name: $this->name,
            email: $this->email,
            roleId: $this->role_id,
            departmentId: $this->department_id,
            positionId: $this->position_id,
            phoneNumber: $this->phone_number ?: null,
            address: $this->address ?: null,
            joinDate: $this->join_date,
            employmentStatus: $this->employment_status,
        );

        $employee = $employeeService->createEmployee($dto);

        session()->flash('success', "Employee created successfully with ID: {$employee->employeeId}");

        $this->redirect(route('employees.index'), navigate: true);
    }

    /**
     * Render component.
     */
    public function render(
        DepartmentServiceInterface $departmentService,
        PositionServiceInterface $positionService
    ) {
        $roles = Role::all();
        $departments = collect($departmentService->getActiveDepartments());
        $positions = collect($positionService->getActivePositions());

        return view('livewire.admin.employees.create', [
            'roles' => $roles,
            'departments' => $departments,
            'positions' => $positions,
        ])->layout('layouts.app', ['title' => 'Create Employee']);
    }
}
