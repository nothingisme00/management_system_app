<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\CreateEmployeeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Employee Request
 *
 * Validates employee creation form data and converts to CreateEmployeeDTO.
 */
class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create employees');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'position_id' => ['nullable', 'integer', Rule::exists('positions', 'id')],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'join_date' => ['required', 'date', 'before_or_equal:today'],
            'employment_status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'on_leave', 'terminated'])],
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
            'join_date.before_or_equal' => 'Join date cannot be in the future.',
            'employment_status.in' => 'Invalid employment status.',
        ];
    }

    /**
     * Convert validated data to CreateEmployeeDTO.
     */
    public function toDTO(): CreateEmployeeDTO
    {
        $validated = $this->validated();

        return new CreateEmployeeDTO(
            name: $validated['name'],
            email: $validated['email'],
            roleId: $validated['role_id'],
            departmentId: $validated['department_id'] ?? null,
            positionId: $validated['position_id'] ?? null,
            phoneNumber: $validated['phone_number'] ?? null,
            address: $validated['address'] ?? null,
            joinDate: $validated['join_date'],
            employmentStatus: $validated['employment_status'] ?? 'active',
        );
    }
}
