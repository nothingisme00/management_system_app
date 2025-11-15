<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Employees;

use App\Contracts\Services\DepartmentServiceInterface;
use App\Contracts\Services\EmployeeServiceInterface;
use App\Contracts\Services\PositionServiceInterface;
use App\DTOs\UpdateEmployeeDTO;
use App\Models\Role;
use Livewire\Component;

class Edit extends Component
{
    public int $employeeId;

    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    public ?int $department_id = null;

    public ?int $position_id = null;

    public string $phone_number = '';

    public string $address = '';

    public string $join_date = '';

    public ?string $termination_date = null;

    public string $employment_status = 'active';

    public string $employee_display_id = '';

    public string $newPassword = '';

    public bool $showResetPassword = false;

    public function mount(int $employee, EmployeeServiceInterface $employeeService): void
    {
        $this->employeeId = $employee;
        $employeeData = $employeeService->getEmployeeById($employee);

        if (! $employeeData) {
            abort(404);
        }

        $this->name = $employeeData->userName;
        $this->email = $employeeData->userEmail;
        $this->role_id = $employeeData->userId;
        $this->department_id = $employeeData->departmentId;
        $this->position_id = $employeeData->positionId;
        $this->phone_number = $employeeData->phoneNumber ?? '';
        $this->address = $employeeData->address ?? '';
        $this->join_date = $employeeData->joinDate;
        $this->termination_date = $employeeData->terminationDate;
        $this->employment_status = $employeeData->employmentStatus;
        $this->employee_display_id = $employeeData->employeeId;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'join_date' => ['required', 'date'],
            'termination_date' => ['nullable', 'date', 'after:join_date'],
            'employment_status' => ['required', 'string', 'in:active,inactive,on_leave,terminated'],
            'newPassword' => ['nullable', 'string', 'min:8'],
        ];
    }

    public function update(EmployeeServiceInterface $employeeService): void
    {
        $this->validate();

        $dto = new UpdateEmployeeDTO(
            name: $this->name,
            email: $this->email,
            roleId: $this->role_id,
            departmentId: $this->department_id,
            positionId: $this->position_id,
            phoneNumber: $this->phone_number ?: null,
            address: $this->address ?: null,
            joinDate: $this->join_date,
            terminationDate: $this->termination_date,
            employmentStatus: $this->employment_status,
        );

        $updated = $employeeService->updateEmployee($this->employeeId, $dto);

        if ($updated && $this->newPassword !== '') {
            $employeeService->resetPassword($this->employeeId, $this->newPassword);
        }

        session()->flash('success', 'Employee updated successfully.');
        $this->redirect(route('employees.index'), navigate: true);
    }

    public function terminate(EmployeeServiceInterface $employeeService): void
    {
        $employeeService->terminateEmployee($this->employeeId);
        session()->flash('success', 'Employee terminated successfully.');
        $this->redirect(route('employees.index'), navigate: true);
    }

    public function render(
        DepartmentServiceInterface $departmentService,
        PositionServiceInterface $positionService
    ) {
        $roles = Role::all();
        $departments = collect($departmentService->getActiveDepartments());
        $positions = collect($positionService->getActivePositions());

        return view('livewire.admin.employees.edit', [
            'roles' => $roles,
            'departments' => $departments,
            'positions' => $positions,
        ])->layout('layouts.app', ['title' => 'Edit Employee']);
    }
}
