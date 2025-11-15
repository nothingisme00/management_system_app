<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\UpdateDepartmentDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Department Request
 *
 * Validates department update form data and converts to UpdateDepartmentDTO.
 */
class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update departments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($departmentId)],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($departmentId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
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
            'name.required' => 'Department name is required.',
            'name.unique' => 'This department name already exists.',
            'code.required' => 'Department code is required.',
            'code.unique' => 'This department code already exists.',
            'code.max' => 'Department code must not exceed 20 characters.',
            'is_active.required' => 'Active status is required.',
        ];
    }

    /**
     * Convert validated data to UpdateDepartmentDTO.
     */
    public function toDTO(): UpdateDepartmentDTO
    {
        $validated = $this->validated();

        return new UpdateDepartmentDTO(
            name: $validated['name'],
            code: strtoupper($validated['code']),
            description: $validated['description'] ?? null,
            isActive: $validated['is_active'],
        );
    }
}
