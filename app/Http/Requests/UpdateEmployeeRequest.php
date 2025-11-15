<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\UpdateEmployeeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Employee Request
 *
 * Validates employee update form data and converts to UpdateEmployeeDTO.
 */
class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update employees');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the employee ID from route to find the user_id for email uniqueness check
        $employeeId = $this->route('employee');

        // We need to get the user_id associated with this employee
        // For now, we'll use a query to get it
        $employee = \App\Models\Employee::find($employeeId);
        $userId = $employee?->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'position_id' => ['nullable', 'integer', Rule::exists('positions', 'id')],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'join_date' => ['required', 'date'],
            'termination_date' => ['nullable', 'date', 'after:join_date'],
            'employment_status' => ['required', 'string', Rule::in(['active', 'inactive', 'on_leave', 'terminated'])],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role_id.required' => 'Role is required.',
            'role_id.exists' => 'The selected role does not exist.',
            'department_id.exists' => 'The selected department does not exist.',
            'position_id.exists' => 'The selected position does not exist.',
            'join_date.required' => 'Join date is required.',
            'termination_date.after' => 'Termination date must be after join date.',
            'employment_status.required' => 'Employment status is required.',
            'employment_status.in' => 'Invalid employment status.',
        ];
    }

    /**
     * Convert validated data to UpdateEmployeeDTO.
     */
    public function toDTO(): UpdateEmployeeDTO
    {
        $validated = $this->validated();

        return new UpdateEmployeeDTO(
            name: $validated['name'],
            email: $validated['email'],
            roleId: $validated['role_id'],
            departmentId: $validated['department_id'] ?? null,
            positionId: $validated['position_id'] ?? null,
            phoneNumber: $validated['phone_number'] ?? null,
            address: $validated['address'] ?? null,
            joinDate: $validated['join_date'],
            terminationDate: $validated['termination_date'] ?? null,
            employmentStatus: $validated['employment_status'],
        );
    }
}
